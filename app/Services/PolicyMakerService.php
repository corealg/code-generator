<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class PolicyMakerService
{
    protected $outputDirectory;
    protected $template;
    protected $configurations;

    public function __construct($config)
    {
        $this->outputDirectory = $config["outputDirectory"];
        $this->configurations = $config["configurations"];

        $this->template = file_get_contents(public_path("templates/policy.text"));
    }

    public function make()
    {
        $payload = [
            "[MODEL_NAME]" => "{$this->configurations['model']['name']}",
            "[POLICY_NAME]" => "{$this->configurations['policy']['name']}"
        ];

        $search = array_keys($payload);
        $replace = array_values($payload);

        $output = str_replace($search, $replace, $this->template);

        Storage::put("{$this->outputDirectory}/{$this->configurations['policy']['name']}.php", $output);

        return true;
    }
}
