<?php

namespace Dipenparmar12\ExportCsv\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ExportCsvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:export 
            {tables? : Export some tables (tables=users,posts) }
            {--a|--all-table=0: Export All tables }
            {--f|--force : Force the operation to run when csv file already exists }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export database tables to csv file (default dir is database/csv/<file_name>.csv )';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ///// For all table export
        if ($this->option('all-table') and !$this->argument('tables')) {
            $this->info('Exporting all tables......');
            foreach ($this->getTables() as $table) {
                $this->writeCsv(DB::table($table)->get(), $table);
            }
            $this->info('');
            $this->info('Csv files exported Successfully.');
            return true;
        }

        $tables_str = str_replace("tables=", "", $this->argument('tables'));
        $tables_arr = explode(',', $tables_str);
        $this->info('Exporting ' . join(', ', $tables_arr) . ' tables.......');

        foreach (collect($tables_arr) as $table) {
            if (Schema::hasTable($table)) {
                $this->writeCsv(DB::table($table)->get(), $table);
            } else {
                $this->warn("table $table is not exists.");
            }
        }
        return true;
    }

    protected function getTables()
    {
        $tables = [];
        foreach (DB::connection()->getDoctrineSchemaManager()->listTableNames() as $table) {
            if (Schema::hasTable($table)) {
                $tables[] = $table;
            }
        }
        return $tables;
    }

    protected function writeCsv($data, $table_name, $directory = 'database/csv')
    {
        $csv_directory = $this->checkOrMakeDir(base_path() . '/' . $directory);
        $csv_file_path = $csv_directory . '/' . $table_name . '.csv';

        ///// METHOD ONE
        /*if (!$this->hasData($data)) {
            return 1;
        }*/

        $this->isForcefully($csv_file_path);

        $csv_file_handler = fopen($csv_file_path, 'w');
        $file_headers = null;

        $bar = $this->output->createProgressBar(count($data));
        $bar->start();

        if (!$file_headers && empty($file_headers)) {
            $file_headers = $this->writeCsvHeading($csv_file_handler, $table_name);
        }

        foreach ($data as $key => $row) {
            $row = collect($row)->toArray();
            fputcsv($csv_file_handler, $row);
            $bar->advance();
        }

        fclose($csv_file_handler);
        $bar->finish();

        $this->info(" <fg=black;bg=blue>$table_name.csv</>");
        return 1;
    }

    protected function checkOrMakeDir($path)
    {
        if (!File::exists($path)) {
            if (File::makeDirectory($path)) {
                return $path;
            }
        }
        return $path;
    }

    protected function isForcefully($path)
    {
        if (
            !$this->option('force')
            && File::exists($path)
            && !$this->confirm("The $path already exists. do you want to overwrite data ?")
        ) {
            $this->line('operation canceled.');
            die();
        }
        return 1;
    }

    protected function writeCsvHeading($file_handler, $table)
    {
        /// Doctrine DBAL Enum issue resolver
        /// Unknown database type enum, MySQL57Platform may not support it #3161
        $conn = DB::connection()->getDoctrineSchemaManager();
        $conn->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        /// Ref:1 https://github.com/doctrine/dbal/issues/3161
        /// Ref:2 https://github.com/laravel/framework/issues/1186#issuecomment-17541553
        
        $column_names = array_keys($conn->listTableColumns($table));
        return fputcsv($file_handler, $column_names);
    }

    protected function hasData($data)
    {
        return (is_countable($data) and count($data) > 0);
    }
}
