<?php

namespace App\Console\Commands;

use DB;
use Doctrine\DBAL\Schema\Column;
use Illuminate\Console\GeneratorCommand;
use App\Traits\ConsoleCodeAutoGenHelper;

class RequestMakeCommand extends GeneratorCommand
{
    use ConsoleCodeAutoGenHelper;

    protected $skipColumns = ["updated_at", "deleted_at", "merchant_id", "id", "created_at", "created_by", "updated_by", "deleted_by", "created_datetime", "updated_datetime", "deleted_datetime", "restored_datetime", 'restored_by'];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new request class';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/request.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Requests';
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return str_singular(ucfirst(camel_case(trim($this->argument('name'))))) . "Request";
    }

     /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $requestsFields = "";
        $table = $this->argument('name');
        $columns = $this->getSchemaDetails($table);

        foreach ($columns as $column => $columnType) {
            if (!in_array($column, $this->skipColumns)) {
                $tableInfo = DB::connection()->getDoctrineColumn($table, $column);
                $requestsFields .= $this->dataTypeRequestRule($column, $columnType, $tableInfo, empty($requestsFields) ?? false);
            }
        }
        $stub = $this->files->get($this->getStub());

        $stub = str_replace("DummyRequestRule", rtrim($requestsFields, ",\n"), $stub);

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    private function dataTypeRequestRule($column, $columnType, $tableInfo, $isFirst) : string
    {
        $rule = "";
        if ($tableInfo->getNotnull()) {
            $rule .= "required|";
        } else {
            $rule .= "nullable|";
        }
        switch ($columnType) {
            case 'integer':
            case 'int':
                $rule .= "int";
                break;
            case 'string':
                $rule .= "string";
                break;
            case 'bool':
            case 'boolean':
                $rule .= "boolean";
                break;
            case 'json':
                $rule .= "array";
                break;
            case 'real':
            case 'float':
            case 'double':
                $rule .= "numeric";
                break;
            case 'datetime':
                $rule .= "date_format:Y-m-d H:i:s";
                break;
            case 'date':
                $rule .= "date_format:Y-m-d";
                break;
            default:
                $rule = rtrim($rule, "|");
                break;
        }

        $spaces = 12;
        if ($isFirst) {
            $spaces = 0;
        }

        return sprintf("%{$spaces}s'%s' => '%s',\n", "", $column, $rule);
    }
}
