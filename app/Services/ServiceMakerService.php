<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ServiceMakerService
{
    protected $outputDirectory;
    protected $template;
    protected $configurations;

    public function __construct($config)
    {
        $this->outputDirectory = $config["outputDirectory"];
        $this->configurations = $config["configurations"];

        $this->template = file_get_contents(public_path("templates/service.text"));
    }

    public function make()
    {
        $body_array = [];

        foreach ($this->configurations["migration"]["table"]["columns"] as $columnName => $property) {

            if (!isset($property["html_element"]) || is_null($property["html_element"]) === true) {
                continue;
            }

            if (empty($property["nullable"]) || $property["nullable"] === false) {
                $body_array[] =
                    "
        " . '$' . "{$this->configurations["model"]["variable_singular"]}->{$columnName} = " . '$' . "data['{$columnName}'];
                ";
            } else {

                $body_array[] =
                    "
        if(isset(" . '$' . "data['{$columnName}'])){
            " . '$' . "{$this->configurations["model"]["variable_singular"]}->{$columnName} = " . '$' . "data['{$columnName}'];
        }
            ";
            }
        }

        $BODY = implode("\n", $body_array);

        $payload = [
            "[CLASS_NAME]" => $this->configurations["service"]["name"],
            "[MODEL_NAME]" => $this->configurations["model"]["name"],
            "[MODEL_VARIABLE_SINGULAR]" => $this->configurations["model"]["variable_singular"],
            "[MODEL_VARIABLE_PLURAL]" => $this->configurations["model"]["variable_plural"],
            "[CREATE_UPDATE_BODY]" => $BODY
        ];

        $search = array_keys($payload);
        $replace = array_values($payload);

        $output = str_replace($search, $replace, $this->template);

        Storage::put("{$this->outputDirectory}/{$this->configurations['service']['name']}.php", $output);

        return true;
    }
}
