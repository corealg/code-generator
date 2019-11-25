<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ViewMakerService
{
    protected $outputDirectory;
    protected $create_template;
    protected $edit_template;
    protected $list_template;
    protected $view_template;
    protected $form_template;
    protected $configurations;

    public function __construct($config)
    {
        $this->outputDirectory = $config["outputDirectory"];
        $this->configurations = $config["configurations"];

        $theme = $this->configurations["theme"];

        $this->create_template = file_get_contents(public_path("templates/views/{$theme}/create.text"));
        $this->edit_template = file_get_contents(public_path("templates/views/{$theme}/edit.text"));
        $this->list_template = file_get_contents(public_path("templates/views/{$theme}/list.text"));
        $this->view_template = file_get_contents(public_path("templates/views/{$theme}/view.text"));
        $this->form_template = file_get_contents(public_path("templates/views/{$theme}/form.text"));
    }

    public function make(){
        $this->makeCreateBlade();
        $this->makeEditBlade();
        $this->makeSingleViewBlade();
        $this->makeListBlade();
        $this->makeFormBlade();

        return true;
    }

    public function makeCreateBlade()
    {
        $payload = [
            "[FEATURE_NAME]" => $this->configurations["model"]["name"],
            "[LIST_ROUTE]" => $this->configurations["table"]["name"],
            "[VIEW_FOLDER]" => $this->configurations["viewFolder"],
            "[MODEL_VARIABLE_NAME]" => $this->configurations["model"]["variable"],
        ];

        $search = array_keys($payload);
        $replace = array_values($payload);

        $output = str_replace($search, $replace, $this->create_template);

        Storage::put("{$this->outputDirectory}/{$this->configurations['viewFolder']}/create.blade.php", $output);

        return true;
    }

    public function makeEditBlade()
    {
        $payload = [
            "[FEATURE_NAME]" => $this->configurations["model"]["name"],
            "[LIST_ROUTE]" => $this->configurations["table"]["name"],
            "[VIEW_FOLDER]" => $this->configurations["viewFolder"],
            "[MODEL_VARIABLE_NAME]" => $this->configurations["model"]["variable"],
        ];

        $search = array_keys($payload);
        $replace = array_values($payload);

        $output = str_replace($search, $replace, $this->edit_template);

        Storage::put("{$this->outputDirectory}/{$this->configurations['viewFolder']}/edit.blade.php", $output);

        return true;
    }

    public function makeSingleViewBlade()
    {
        $payload = [
            "[FEATURE_NAME]" => $this->configurations["model"]["name"],
            "[LIST_ROUTE]" => $this->configurations["table"]["name"],
            "[VIEW_FOLDER]" => $this->configurations["viewFolder"],
            "[MODEL_VARIABLE_NAME]" => $this->configurations["model"]["variable"],
        ];

        $search = array_keys($payload);
        $replace = array_values($payload);

        $output = str_replace($search, $replace, $this->view_template);

        Storage::put("{$this->outputDirectory}/{$this->configurations['viewFolder']}/view.blade.php", $output);

        return true;
    }

    public function makeListBlade()
    {
        $payload = [
            "[FEATURE_NAME]" => $this->configurations["model"]["name"],
            "[LIST_ROUTE]" => $this->configurations["table"]["name"],
            "[VIEW_FOLDER]" => $this->configurations["viewFolder"],
            "[MODEL_VARIABLE_NAME]" => $this->configurations["model"]["variable"],
        ];

        $search = array_keys($payload);
        $replace = array_values($payload);

        $output = str_replace($search, $replace, $this->list_template);

        Storage::put("{$this->outputDirectory}/{$this->configurations['viewFolder']}/list.blade.php", $output);

        return true;
    }

    public function makeFormBlade()
    {
        $payload = [
            "[FEATURE_NAME]" => $this->configurations["model"]["name"],
            "[LIST_ROUTE]" => $this->configurations["table"]["name"],
            "[VIEW_FOLDER]" => $this->configurations["viewFolder"],
            "[MODEL_VARIABLE_NAME]" => $this->configurations["model"]["variable"],
        ];

        $search = array_keys($payload);
        $replace = array_values($payload);

        $output = str_replace($search, $replace, $this->form_template);

        Storage::put("{$this->outputDirectory}/{$this->configurations['viewFolder']}/form.blade.php", $output);

        return true;
    }
}