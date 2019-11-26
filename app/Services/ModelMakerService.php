<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ModelMakerService
{
    protected $outputDirectory;
    protected $template;
    protected $configurations;

    public function __construct($config)
    {
        $this->outputDirectory = $config["outputDirectory"];
        $this->configurations = $config["configurations"];

        $this->template = file_get_contents(public_path("templates/model.text"));
    }

    public function make()
    {
        $payload = [
            "[MODEL_NAME]" => "{$this->configurations['model']['name']}"
        ];

        $fillable_property = [];

        foreach($this->configurations["migration"]["table"]["columns"] as $columnName => $property){

            if(empty($property["nullable"]) || $property["nullable"] === false){
                $fillable_property[] = "'{$columnName}'";
            }
        }

        if(count($fillable_property) > 0){
            $payload["FILLABLE_PROPERTY"] = implode(", ", $fillable_property);        
        }else{
            $payload["FILLABLE_PROPERTY"] = "[]";
        }        

        $search = array_keys($payload);
        $replace = array_values($payload);

        $output = str_replace($search, $replace, $this->template);

        Storage::put("{$this->outputDirectory}/{$this->configurations['model']['name']}.php", $output);

        return true;
    }
}