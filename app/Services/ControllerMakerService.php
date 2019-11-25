<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ControllerMakerService
{
    protected $rawArray;
    protected $outputDirectory;
    protected $template;
    protected $configurations;

    public function __construct($config)
    {
        $this->outputDirectory = $config["outputDirectory"];
        $this->configurations = $config["configurations"];

        $this->template = file_get_contents(public_path("templates/controller.text"));
    }

    public function make()
    {
        $payload = [
            "[CONTROLLER_NAME]" => "{$this->configurations['controller']['name']}",
            "[SERVICE_NAME]" => "{$this->configurations['service']['name']}",
            "[SERVICE_VARIABLE_NAME]" => "{$this->configurations['service']['variable']}",
            "[VIEW_FOLDER]" => "{$this->configurations['viewFolder']}",
            "[CREATE_FORM_REQUEST]" => "Request",
            "[UPDATE_FORM_REQUEST]" => "Request"
        ];

        $search = array_keys($payload);
        $replace = array_values($payload);

        $output = str_replace($search, $replace, $this->template);

        Storage::put("{$this->outputDirectory}/{$this->configurations['controller']['name']}.php", $output);

        return true;
    }
}