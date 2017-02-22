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
    protected $description = 'Command description';

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
        $path = app_path('Http/DataModels/'.$DMName.'.php');
        $template = fopen('DataModelTemplate.php', "r") or die("Unable to open file!");
        $myfile = fopen($path, "w") or die("Unable to open file!");
          $this->info( $template.' DataModel created.');
          die;

        $txt = "<?php
namespace App\Http\DataModels;

use Hammal\DataModel\DataModel;

class $DMName extends DataModel
{
    protected \$model = '".lcFirst($modelName)."';
    protected \$table = '".lcfirst($DMName)."';

    protected \$tableFields = [
        'id'
    ];
    protected \$formFields = [
        'id'
    ];
    protected \$hiddenFields = [

    ];
}";
            fwrite($myfile, $txt);
            fclose($myfile);
            $this->info($DMName.' DataModel created.');
    }
}
