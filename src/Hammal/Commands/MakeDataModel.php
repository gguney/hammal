<?php
namespace Hammal\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class MakeDataModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dataModel {modelName} {--m} {--fill}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It creates Data Model from given model name.';

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
        $shouldFill = $this->option('fill');
        $withModel = $this->option('m');
        $modelName = $this->argument('modelName');

        $tableNameArray = preg_split('/(?=[A-Z])/',str_plural(lcfirst($modelName)));
        $tableName = strtolower(implode('_',$tableNameArray));
        $DMName = ucfirst(str_plural($modelName));

        $hiddenFields = "'id', 'created_at', 'updated_at', 'deleted_at'";

        $DMFolderPath = app_path('Http/DataModels/'.$DMName.'.php');;
        $ModelFolderPath = app_path('Http/Models/'.$modelName.'.php');;

        $fieldsArray = Schema::getColumnListing($tableName);
        $fieldsArray = array_map(function($value){
          return "'".$value."'";
        },$fieldsArray);
        $fieldsString = implode(', ',$fieldsArray);

        if($withModel)
        {
            $modelTemplate = file_get_contents(__DIR__.'/ModelTemplate.txt') or die("Unable to open ModelTemplate file!");
            $modelTemplate = str_replace(
                ['{MODEL_NAME}', '{TABLE_NAME}', '{FILLABLE_FIELDS}', '{HIDDEN_FIELDS}'], 
                [$modelName, $tableName, $fieldsString, $hiddenFields], 
                $modelTemplate);
            $ModelFile = fopen($ModelFolderPath, "w") or die("Unable to open file!");
            fwrite($ModelFile, $modelTemplate);
            fclose($ModelFile);
            $this->info($modelName.' named Model created in '.$ModelFolderPath.'.');
        }

        if(!$shouldFill)
        {
            $fieldsString = "'id'";
        }
        $dataModelTemplate = file_get_contents(__DIR__.'/DataModelTemplate.txt') or die("Unable to open DataModelTemplate file!");
        $dataModelTemplate = str_replace(
            ['{DM_NAME}', '{MODEL_NAME}', '{TABLE_NAME}', '{TABLE_FIELDS}', '{FORM_FIELDS}', '{HIDDEN_FIELDS}'],
            [$DMName, $modelName, $tableName, $fieldsString, $fieldsString, $hiddenFields],
             $dataModelTemplate);
        $DMFile = fopen($DMFolderPath, "w") or die("Unable to open file!");
        fwrite($DMFile, $dataModelTemplate);
        fclose($DMFile);

        $this->info($DMName.' named DataModel created in '.$DMFolderPath.'.');
    }
}
