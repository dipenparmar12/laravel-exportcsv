<?php

namespace Dipenparmar12\ExportCsv\Facades;

use Illuminate\Support\Facades\Facade;

class ExportCsv extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'exportcsv';
    }
}
