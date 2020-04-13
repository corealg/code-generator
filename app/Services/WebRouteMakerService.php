<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class WebRouteMakerService
{
    protected $rawArray;
    protected $outputDirectory;
    protected $template;
    protected $configurations;

    public function __construct($config)
    {
        $this->outputDirectory = $config["outputDirectory"];
        $this->configurations = $config["configurations"];

        $this->template = file_get_contents(public_path("templates/web.text"));
    }

    public function make()
    {
        $payload = [
            "[FEATURE_NAME]" => "{$this->configurations['model']['name']}",
            "[ROUTE_NAME]" => "{$this->configurations['route']['name']}",
            "[CONTROLLER_NAME]" => "{$this->configurations['controller']['name']}",
        ];

        $search = array_keys($payload);
        $replace = array_values($payload);

        $output = str_replace($search, $replace, $this->template);

        Storage::put("{$this->outputDirectory}/web.php", $output);

        return true;
    }
}
