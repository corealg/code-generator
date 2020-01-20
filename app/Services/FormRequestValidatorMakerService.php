<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FormRequestValidatorMakerService
{
    protected $outputDirectory;
    protected $configurations;
    protected $save_template;
    protected $update_template;
    protected $namespace;

    public function __construct($config)
    {
        $this->outputDirectory = $config["outputDirectory"];
        $this->configurations = $config["configurations"];

        $this->save_template = file_get_contents(public_path("templates/request-validator/save.text"));
        $this->update_template = file_get_contents(public_path("templates/request-validator/update.text"));

        $this->namespace = "App\Http\Requests";
    }

    public function make()
    {
        $this->makeSaveValidator();

        $this->makeUpdateValidator();

        return true;
    }

    private function makeSaveValidator()
    {
        $body_array = [];

        foreach ($this->configurations["migration"]["table"]["columns"] as $columnName => $property) {

            if (!isset($property["html_element"]) || is_null($property["html_element"]) === true) {
                continue;
            }

            $rule = "
            '{$columnName}'=>'";

            if (isset($property["nullable"]) && $property["nullable"] === true) {
                $rule .= "nullable";
            } else {
                $rule .= "required";
            }

            if (isset($property["unique"]) && $property["unique"] === true) {
                $rule .= "|unique:{$this->configurations['migration']['table']['name']}";
            }

            if (!empty($property["length"]) && is_numeric($property["length"])) {
                $rule .= "|max:{$property['length']}";
            }

            $rule .= "'";

            $body_array[$columnName] = $rule;
        }

        $BODY = implode(",\n", $body_array);

        $namespace = $this->namespace;

        $directory = "";
        if (!empty($this->configurations['validator']['save']['directory'])) {
            $dirName = ucfirst($this->configurations['validator']['save']['directory']);
            $namespace .= "\\{$dirName}";
            $directory = "{$dirName}/";
        }

        $payload = [
            "[CLASS_NAME]" => $this->configurations["validator"]["save"]["name"],
            "[BODY]" => $BODY,
            "[NAMESPACE]" => $namespace
        ];

        $search = array_keys($payload);
        $replace = array_values($payload);

        $output = str_replace($search, $replace, $this->save_template);

        Storage::put("{$this->outputDirectory}/$directory{$this->configurations['validator']['save']['name']}.php", $output);

        return true;
    }

    private function makeUpdateValidator()
    {
        $body_array = [];

        foreach ($this->configurations["migration"]["table"]["columns"] as $columnName => $property) {

            if (!isset($property["html_element"]) || is_null($property["html_element"]) === true) {
                continue;
            }

            $rule = "
            '{$columnName}'=>'";

            if (isset($property["nullable"]) && $property["nullable"] === true) {
                $rule .= "nullable";
            } else {
                $rule .= "required";
            }

            // if (isset($property["unique"]) && $property["unique"] === true) {
            //     $rule .= "|unique:{$this->configurations['migration']['table']['name']},{$columnName}";
            // }

            if (!empty($property["length"]) && is_numeric($property["length"])) {
                $rule .= "|max:{$property['length']}";
            }

            $rule .= "'";

            $body_array[$columnName] = $rule;
        }

        $BODY = implode(",\n", $body_array);

        $namespace = $this->namespace;

        $directory = "";
        if (!empty($this->configurations['validator']['update']['directory'])) {
            $dirName = ucfirst($this->configurations['validator']['update']['directory']);
            $namespace .= "\\{$dirName}";
            $directory = "{$dirName}/";
        }

        $payload = [
            "[CLASS_NAME]" => $this->configurations["validator"]["update"]["name"],
            "[BODY]" => $BODY,
            "[NAMESPACE]" => $namespace
        ];

        $search = array_keys($payload);
        $replace = array_values($payload);

        $output = str_replace($search, $replace, $this->update_template);

        Storage::put("{$this->outputDirectory}/$directory{$this->configurations['validator']['update']['name']}.php", $output);

        return true;
    }
}
