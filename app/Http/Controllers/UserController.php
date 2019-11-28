<?php

namespace App\Http\Controllers;

use App\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Requests\Users\SaveFormRequest;
use App\Http\Requests\Users\UpdateFormRequest;
use Gate;

class UserController extends Controller
{
    protected $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function index(Request $request)
    {
        if (Gate::denies('list', User::class)) {
            return view("errors.403");
        }

        $data = $request->all();
        $users = $this->userService->lists($data);

        $search = $request->search;

        return view("users.list", compact(["users", "search"]));
    }

    public function create()
    {
        if (Gate::denies('create', User::class)) {
            return view("errors.403");
        }

        return view("users.create");
    }

    public function edit($id)
    {
        $user = $this->userService->getById($id);

        if (Gate::denies('edit', $user)) {
            return view("errors.403");
        }

        return view("users.edit", compact(["user"]));
    }

    public function save(SaveFormRequest $request)
    {
        if (Gate::denies('create', User::class)) {
            return view("errors.403");
        }

        $validatedData = $request->validated();

        $user = $this->userService->updateOrCreate($validatedData);

        if(is_null($user) === false){
            $message = message("User has been successfully created.");
        }else{
            $message = message("User has not created.", "error");
        }

        session()->flash("message", $message);
        return redirect()->back();
    }

    public function update(UpdateFormRequest $request)
    {
        $validatedData = $request->validated();

        $user = $this->userService->updateOrCreate($validatedData);

        if(is_null($user) === false){
            $message = message("User has been successfully updated.");
        }else{
            $message = message("User has not updated.", "error");
        }

        session()->flash("message", $message);
        return redirect()->back();
    }
    
    public function view($id)
    {
        $user = $this->userService->getById($id);

        if (Gate::denies('view', $user)) {
            return view("errors.403");
        }

        return view("users.view", compact(["user"]));
    }

    public function delete(Request $request)
    {
        $id = $request->id;

        $user = $this->userService->getById($id);

        if (Gate::denies('delete', $user)) {
            return view("errors.403");
        }

        $response = $this->userService->delete($user);
        
        if($response === true){
            $message = message("User has been successfully deleted.");
        }else{
            $message = message("User has not deleted.", "error");
        }

        session()->flash("message", $message);
        return redirect()->back();
    }
}
