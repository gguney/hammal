<?php
namespace GGuney\Hammal\Commands;

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
        $modelName = str_singular(studly_case($this->argument('modelName')));
        $tableName = str_plural(snake_case($this->argument('modelName')));
        $DMName = str_plural(studly_case($this->argument('modelName')));

        $hiddenFields = "'id', 'created_at', 'updated_at', 'deleted_at'";
        $fieldsString = $this->getFields($tableName);

        if ($this->option('m')) {
            $this->saveStub(
                'Model.stub',
                ['{NAMESPACE}','{MODEL_NAME}', '{TABLE_NAME}', '{FILLABLE_FIELDS}', '{HIDDEN_FIELDS}'],
                ['App\\'.config('hammal.models_path'), $modelName, $tableName, $fieldsString, $hiddenFields],
                config('hammal.models_path').'/' . $modelName . '.php');
        }
        $this->saveStub('/DataModel.stub',
            ['{NAMESPACE}','{DM_NAME}', '{MODEL_NAME}', '{TABLE_NAME}', '{TABLE_FIELDS}', '{FORM_FIELDS}', '{HIDDEN_FIELDS}'],
            ['App\\'.config('hammal.data_models_path'), $DMName, $modelName, $tableName, $fieldsString, $fieldsString, $hiddenFields],
            config('hammal.data_models_path').'/' . $DMName . '.php');
    }

    /**
     * @param $modelName
     * @param $tableName
     * @param $fieldsString
     * @param $hiddenFields
     * @param $ModelFolderPath
     */
    public function saveStub($stubFileName, $places, $vars, $savePath)
    {
        $stub = $this->getStub($stubFileName);
        $stub = $this->replaceStub($places, $vars, $stub);
        $file = fopen(app_path($savePath), "w") or die("Unable to open file!");
        fwrite($file, $stub);
        fclose($file);
        $this->info($savePath . ' has been created.');
    }

    /**
     * @return string
     */
    public function getStub($fileName)
    {
        $stub = file_get_contents(__DIR__ . '/' . $fileName) or die("Unable to open " . $fileName . " file!");
        return $stub;
    }

    /**
     * @param $modelName
     * @param $tableName
     * @param $fieldsString
     * @param $hiddenFields
     * @param $modelStub
     * @return mixed
     */
    public function replaceStub($places, $vars, $stub)
    {
        $modelStub = str_replace($places, $vars, $stub);
        return $modelStub;
    }

    /**
     * @param $tableName
     * @return string
     */
    public function getFields($tableName)
    {
        if (!$this->option('fill')) {
            $fieldsString = "'id'";
            return $fieldsString;
        } else {
            $fieldsArray = Schema::getColumnListing($tableName);
            $fieldsArray = array_map(function ($value) {
                return "'" . $value . "'";
            }, $fieldsArray);
            $fieldsString = implode(', ', $fieldsArray);
            return $fieldsString;
        }
    }

}
