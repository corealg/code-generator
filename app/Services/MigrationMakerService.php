<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class MigrationMakerService
{
    protected $outputDirectory;
    protected $template;
    protected $configurations;

    public function __construct($config)
    {
        $this->outputDirectory = $config["outputDirectory"];
        $this->configurations = $config["configurations"];

        $this->template = file_get_contents(public_path("templates/migration.text"));
    }

    public function make()
    {
        $body_array = [];

        foreach ($this->configurations["migration"]["table"]["columns"] as $columnName => $property) {

            $length = "";

            if (!empty($property["length"])) {
                $length = ", {$property['length']}";
            }

            $statement = "
            " . '$' . "table->{$property['type']}('{$columnName}'{$length})";

            if (isset($property["nullable"]) && $property["nullable"] === true) {
                $statement .= "->nullable()";
            }

            if (isset($property["unique"]) && $property["unique"] === true) {
                $statement .= "->unique()";
            }

            if (!empty($property["default"])) {
                $statement .= "->default('{$property['default']}')";
            }

            if (isset($property["index"]) && $property["index"] === true) {
                $statement .= "->index()";
            }

            $statement .= ";";

            // $body_array[] = str_replace("\n", "", $statement);
            $body_array[] = $statement;
        }

        $BODY = implode("", $body_array);

        $payload = [
            "[CLASS_NAME]" => "{$this->configurations['migration']['class_name']}",
            "[TABLE_NAME]" => $this->configurations["migration"]["table"]["name"],
            "[BODY]" => $BODY
        ];

        $search = array_keys($payload);
        $replace = array_values($payload);

        $output = str_replace($search, $replace, $this->template);

        $now = now()->format("Y_m_d_his");

        $migrationName = "{$now}_{$this->configurations['migration']['name']}.php";

        Storage::put("{$this->outputDirectory}/{$migrationName}", $output);

        return true;
    }
}
