<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ControllerMakerService
{
    protected $rawArray;
    protected $outputDirectory;
    protected $template;
    protected $configurations;
    protected $namespace;

    public function __construct($config)
    {
        $this->outputDirectory = $config["outputDirectory"];
        $this->configurations = $config["configurations"];

        $this->template = file_get_contents(public_path("templates/controller.text"));

        $this->namespace = "App\Http\Requests";
    }

    public function make()
    {
        $payload = [
            "[CONTROLLER_NAME]" => "{$this->configurations['controller']['name']}",
            "[SERVICE_NAME]" => "{$this->configurations['service']['name']}",
            "[SERVICE_VARIABLE_NAME]" => "{$this->configurations['service']['variable']}",
            "[VIEW_DIRECTORY]" => "{$this->configurations['view']['directory']}",
            "[SAVE_FORM_REQUEST]" => $this->configurations['validator']['save']['name'] ?? "Request",
            "[UPDATE_FORM_REQUEST]" => $this->configurations['validator']['update']['name'] ?? "Request",
            "[MODEL_NAME]" => $this->configurations["model"]["name"],
            "[MODEL_VARIABLE_NAME_SINGULAR]" => $this->configurations["model"]["variable_singular"],
            "[MODEL_VARIABLE_NAME_PLURAL]" => $this->configurations["model"]["variable_plural"],
        ];

        if (!empty($this->configurations['validator']['save']['directory'])) {
            $dirName = ucfirst($this->configurations['validator']['save']['directory']);
            $namespace = "{$this->namespace}\\{$dirName}";
        }else{
            $namespace = "{$this->namespace}";
        }
        $payload["[SAVE_VALIDATOR_NAMESPACE]"] = "{$namespace}\\{$this->configurations['validator']['save']['name']}";

        if (!empty($this->configurations['validator']['update']['directory'])) {
            $dirName = ucfirst($this->configurations['validator']['update']['directory']);
            $namespace = "{$this->namespace}\\{$dirName}";
        }else{
            $namespace = "{$this->namespace}";
        }
        $payload["[UPDATE_VALIDATOR_NAMESPACE]"] = "{$namespace}\\{$this->configurations['validator']['update']['name']}";

        $search = array_keys($payload);
        $replace = array_values($payload);

        $output = str_replace($search, $replace, $this->template);

        Storage::put("{$this->outputDirectory}/{$this->configurations['controller']['name']}.php", $output);

        return true;
    }
}
