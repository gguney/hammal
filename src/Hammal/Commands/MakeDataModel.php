<?php
namespace Hammal\Commands;

use Illuminate\Console\Command;

class MakeDataModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dataModel {modelName}';

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
        $modelName = $this->argument('modelName');
        $DMName = ucfirst(str_plural($modelName));
        $tableName = lcfirst($DMName);

        $txt = file_get_contents(__DIR__.'/DataModelTemplate.php') or die("Unable to open file!");
        $txt = str_replace('{DM_NAME}', $DMName, $txt);
        $txt = str_replace('{MODEL_NAME}', $modelName, $txt);
        $txt = str_replace('{TABLE_NAME}', $tableName, $txt);

        $DMFolderPath = 'Http/DataModels/';
        $path = app_path($DMFolderPath.$DMName.'.php');
        $myfile = fopen($path, "w") or die("Unable to open file!");

        fwrite($myfile, $txt);
        fclose($myfile);
        $this->info($DMName.' named DataModel created in '.$DMFolderPath.'.');
    }
}
