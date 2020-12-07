<?php

namespace App\Utils;

use Illuminate\Support\Facades\Schema;

trait ConsoleCodeAutoGenHelper
{
	public $dbSchemaDetails = [];

	public function getSchemaDetails($inputName) : array
	{
		$tableName = str_plural(snake_case(trim($inputName)));

		if (empty($this->dbSchemaDetails[$tableName]) === true) {
			$schemaManager = Schema::getConnection()->getDoctrineSchemaManager();
			$platform = $schemaManager->getDatabasePlatform();
	        $platform->registerDoctrineTypeMapping('enum', 'string');
	        $columns = Schema::getColumnListing($tableName);
	        $indexedColumns = array_map(function($key) {
				return $key->getColumns()[0];
		    }, $schemaManager->listTableForeignKeys($tableName));

	        foreach ($columns as $key => $column) {
	        	$connection = Schema::getConnection();
	        	$columnObj = $connection->getDoctrineColumn($tableName, $column);
	            $this->dbSchemaDetails[$tableName][$column]['type'] = $columnObj->getType()->getName();
	            $this->dbSchemaDetails[$tableName][$column]['auto_increment'] = $columnObj->getAutoincrement();
	            $this->dbSchemaDetails[$tableName][$column]['index'] = in_array($column, $indexedColumns);
	        }
	    }

        return $this->dbSchemaDetails[$tableName];
	}
}