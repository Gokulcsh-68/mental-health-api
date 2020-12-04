<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use App\Traits\ConsoleCodeAutoGenHelper;

class EntityMakeCommand extends GeneratorCommand
{
    use ConsoleCodeAutoGenHelper;

    protected $skipFillableColumns = ["id", "created_at", "updated_at", "deleted_at", "restored_datetime", "updated_datetime", "created_datetime", "deleted_datetime"];

    protected $eventsDateFillColumns = [];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:entity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new entity class';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/entity.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Entities';
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return str_singular(ucfirst(camel_case(trim($this->argument('name')))));
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $entityFields = ["fillable" => "", "casts" => "",  "dates" => "", "dispatchesEvents" => ""];
        $columns = $this->getSchemaDetails($this->argument('name'));

        foreach ($columns as $column => $columnType) {
            if (!in_array($column, $this->skipFillableColumns)) {
                $entityFields['fillable'] .= sprintf('"%s", ', $column);
                if ($columnType === "json") {
                    $entityFields['casts'] .= sprintf("'%s' => 'json',\n%8s", $column, "");
                }

                if ($columnType === "datetime" || $columnType === "date") {
                    $entityFields['dates'] .= sprintf('"%s", ', $column);
                }

                if (in_array($column, $this->eventsDateFillColumns)) {
                    $entityFields['dispatchesEvents'] .= $this->getEventType($column);
                }
            }
        }
        $stub = $this->files->get($this->getStub());

        foreach ($entityFields as $key => $var) {
            $stub = str_replace("Dummy" . ucfirst($key), rtrim($var, sprintf(",\n%8s", "")), $stub);
        }

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    private function getEventType($column)
    {
        $event = "";
        switch ($column) {
            case 'created_by':
                $event = "'creating' => \App\Events\ModelCreating::class,\n";
                break;
            case 'updated_by':
                $event = "'updating' => \App\Events\ModelUpdating::class,\n";
                break;
            case 'deleted_by':
                $event = "'deleting' => \App\Events\ModelDeleting::class,\n";
                break;
        }

        return sprintf("%s%8s", $event, "");
    }
}
