<?php

namespace Dipenparmar12\ExportCsv;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ExportCsv
{
    protected $tables = [];

    /**
     * ExportCsv constructor.
     * @param array $tables
     */
    public function __construct(array $tables = [])
    {
        $this->setTables($tables);
    }

    public function export()
    {
        foreach ($this->getTables() as $table) {
            $this->writeCsv($this->getTableData($table), $table);
        }
        return true;
    }

    public function getTables()
    {
        return $this->tables;
    }

    public function setTables($tables = [])
    {
        if ($tables) {
            return $this->tables = $tables;
        }

        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->tables[] = $table;
            }
        }
        return $this->tables;
    }

    public function writeCsv($tableData, $filename, $directory = 'database/csv')
    {
        $csv_directory = $this->getDir(base_path() . '/' . $directory);
        $csv_file_path = $csv_directory . '/' . $filename . '.csv';
        $file_handler = fopen($csv_file_path, 'w');
        $headings_row = null;

        if (!$this->tableHasData($tableData)) {
            $this->writeCsvHeading($file_handler, $filename);
            fclose($file_handler);
            return false;
        }

        foreach ($tableData as $tableRow) {
            if (empty($headings_row)) {
                $headings_row = $this->writeCsvRow($file_handler, array_keys($tableRow));
            }
            $this->writeCsvRow($file_handler, $tableRow);
        }

        return fclose($file_handler);
    }

    protected function getDir($path)
    {
        if (!File::exists($path) && File::makeDirectory($path)) {
            return $path;
        }
        return $path;
    }

    protected function tableHasData($table)
    {
        return (is_countable($table) and count($table) > 0);
    }

    protected function writeCsvHeading($file_handler, $table)
    {
        $column_names = array_keys(DB::connection()->getDoctrineSchemaManager()->listTableColumns($table));
        return fputcsv($file_handler, $column_names);
    }

    protected function writeCsvRow($file, $data)
    {
        return fputcsv($file, $data);
    }

    public function getTableData($table)
    {
        return json_decode(json_encode(DB::table($table)->get()), true);
        //        return DB::table($table)->get()->toArray() ?? [];
    }

    public function tables()
    {
        return $this->tables;
    }
}
