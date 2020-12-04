<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use App\Traits\ConsoleCodeAutoGenHelper;

class ResourceMakeCommand extends GeneratorCommand
{
    use ConsoleCodeAutoGenHelper;

    protected $skipTransformColumns = ["updated_at", "deleted_at", "merchant_id", "id", "created_at", "created_by", "updated_by", "deleted_by", "created_datetime", "updated_datetime", "deleted_datetime", "restored_datetime", "password", "restored_by"];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource class';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/resource.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Transformers';
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return str_singular(ucfirst(camel_case(trim($this->argument('name'))))) . "Transformer";
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $transformersFields = "";
        $columns = $this->getSchemaDetails($this->argument('name'));

        foreach ($columns as $column => $columnType) {
            if (!in_array($column, $this->skipTransformColumns)) {
                $transformersFields .= $this->dataTypeTransform($column, $columnType, empty($transformersFields) ?? false);
            }
        }
        $stub = $this->files->get($this->getStub());

        $stub = str_replace("DummyResource", rtrim($transformersFields, ",\n"), $stub);

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    private function dataTypeTransform($column, $columnType, $isFirst) : string
    {
        $transformer = "";
        $dataProcess = sprintf('$this->%s', $column);
        switch ($columnType) {
            case 'integer':
            case 'int':
                $transformer = "(int)";
                break;
            case 'string':
                $transformer = "(string)";
                break;
            case 'boolean':
            case 'bool':
                $transformer = "(boolean)";
                break;
            case 'json':
                $transformer = "(object)";
                break;
            case 'real':
            case 'float':
            case 'double':
                $transformer = "(float)";
                break;
            case 'datetime':
                $dataProcess = sprintf('$this->%s ? $this->%s->toDateTimeString() : null', $column, $column);
                break;
            case 'date':
                $dataProcess = sprintf('$this->%s ? $this->%s->toDateString() : null', $column, $column);
                break;
        }
        $spaces = 12;
        if ($isFirst) {
            $spaces = 0;
        }

        return sprintf("%{$spaces}s'%s' => %s %s,\n", "", $column, $transformer, $dataProcess);
    }
}
