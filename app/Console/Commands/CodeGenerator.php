<?php

namespace App\Console\Commands;

use App\Services\ControllerMakerService;
use App\Services\MigrationMakerService;
use App\Services\ModelMakerService;
use App\Services\ServiceMakerService;
use App\Services\BladeMakerService;
use App\Services\WebRouteMakerService;
use Exception;
use Illuminate\Console\Command;

class CodeGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:code {configurationFileName}';

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
        try{
            $configurationFileName = $this->argument("configurationFileName");
            $configurationJson = file_get_contents(public_path("configurations/{$configurationFileName}"));
            $configurationArray = json_decode($configurationJson, true);
        }catch(Exception $ex){
            $this->error($ex->getMessage());
            return false;
        }

        $now = now()->format("Y-m-d-His");
        $outputDirectory = "output/{$configurationFileName}-{$now}";

        $argument = [
            "outputDirectory" => $outputDirectory,
            "configurations" => $configurationArray
        ];

        $migrationMaker = new MigrationMakerService($argument);
        $migrationMaker->make();

        $serviceMaker = new ServiceMakerService($argument);
        $serviceMaker->make();

        $modelMaker = new ModelMakerService($argument);
        $modelMaker->make();

        $controllerMaker = new ControllerMakerService($argument);
        $controllerMaker->make();

        $bladeMaker = new BladeMakerService($argument);
        $bladeMaker->make();

        $webRouteMaker = new WebRouteMakerService($argument);
        $webRouteMaker->make();

        $this->info("DONE!");
    }
}
