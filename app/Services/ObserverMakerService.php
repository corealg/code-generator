<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ObserverMakerService
{
    protected $outputDirectory;
    protected $template;
    protected $configurations;

    public function __construct($config)
    {
        $this->outputDirectory = $config["outputDirectory"];
        $this->configurations = $config["configurations"];

        $this->template = file_get_contents(public_path("templates/observer.text"));
    }

    public function make()
    {
        $payload = [
            "[MODEL_NAME]" => "{$this->configurations['model']['name']}",
            "[OBSERVER_NAME]" => "{$this->configurations['observer']['name']}",
            "[MODEL_VARIABLE_NAME_SINGULAR]" => "{$this->configurations['model']['variable_singular']}",
        ];

        $search = array_keys($payload);
        $replace = array_values($payload);

        $output = str_replace($search, $replace, $this->template);

        Storage::put("{$this->outputDirectory}/{$this->configurations['observer']['name']}.php", $output);

        return true;
    }
}
