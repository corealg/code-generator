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
            "[LIST_ROUTE]" => $this->configurations["routes"]["list"],
            "[VIEW_DIRECTORY]" => $this->configurations["view"]["directory"],
            "[SAVE_ROUTE]" => $this->configurations["routes"]["save"],
        ];

        $search = array_keys($payload);
        $replace = array_values($payload);

        $output = str_replace($search, $replace, $this->create_template);

        Storage::put("{$this->outputDirectory}/{$this->configurations['view']['directory']}/create.blade.php", $output);

        return true;
    }

    public function makeEditBlade()
    {
        $payload = [
            "[FEATURE_NAME]" => $this->configurations["model"]["name"],
            "[LIST_ROUTE]" => $this->configurations["routes"]["list"],
            "[VIEW_DIRECTORY]" => $this->configurations["view"]["directory"],
            "[MODEL_VARIABLE_NAME_SINGULAR]" => $this->configurations["model"]["variable_singular"],
            "[UPDATE_ROUTE]" => $this->configurations["routes"]["update"],
        ];

        $search = array_keys($payload);
        $replace = array_values($payload);

        $output = str_replace($search, $replace, $this->edit_template);

        Storage::put("{$this->outputDirectory}/{$this->configurations['view']['directory']}/edit.blade.php", $output);

        return true;
    }

    public function makeSingleViewBlade()
    {
        $payload = [
            "[FEATURE_NAME]" => $this->configurations["model"]["name"],
            "[LIST_ROUTE]" => $this->configurations["routes"]["list"],
            "[VIEW_DIRECTORY]" => $this->configurations["view"]["directory"],
            "[MODEL_VARIABLE_NAME_SINGULAR]" => $this->configurations["model"]["variable_singular"],
        ];

        $search = array_keys($payload);
        $replace = array_values($payload);

        $output = str_replace($search, $replace, $this->view_template);

        Storage::put("{$this->outputDirectory}/{$this->configurations['view']['directory']}/view.blade.php", $output);

        return true;
    }

    public function makeListBlade()
    {
        $payload = [
            "[FEATURE_NAME]" => $this->configurations["model"]["name"],
            "[LIST_ROUTE]" => $this->configurations["routes"]["list"],
            "[VIEW_DIRECTORY]" => $this->configurations["view"]["directory"],
            "[MODEL_VARIABLE_NAME_SINGULAR]" => $this->configurations["model"]["variable_singular"],
        ];

        $search = array_keys($payload);
        $replace = array_values($payload);

        $output = str_replace($search, $replace, $this->list_template);

        Storage::put("{$this->outputDirectory}/{$this->configurations['view']['directory']}/list.blade.php", $output);

        return true;
    }

    public function makeFormBlade()
    {
        $payload = [
            "[FEATURE_NAME]" => $this->configurations["model"]["name"],
            "[LIST_ROUTE]" => $this->configurations["routes"]["list"],
            "[VIEW_DIRECTORY]" => $this->configurations["view"]["directory"],
            "[MODEL_VARIABLE_NAME_SINGULAR]" => $this->configurations["model"]["variable_singular"],
        ];

        $search = array_keys($payload);
        $replace = array_values($payload);

        $output = str_replace($search, $replace, $this->form_template);

        Storage::put("{$this->outputDirectory}/{$this->configurations['view']['directory']}/form.blade.php", $output);

        return true;
    }
}