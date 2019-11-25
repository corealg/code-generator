<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $users = $this->userService->lists($data);

        $search = $request->search;

        return view("users.index", compact(["users", "search"]));
    }

    public function create(Request $request)
    {
        return view("users.create");
    }

    public function edit(Request $request, $id)
    {
        $user = $this->userService->getById($id);
        return view("users.edit", compact(["user"]));
    }

    public function save(Request $request)
    {
        $validatedData = $request->all();

        $user = $this->userService->createOrUpdate($validatedData);

        if(is_null($user) === false){
            $message = message("Operation succeed.");
        }else{
            $message = message("Operation failed.", "error");
        }

        session()->flash("message", $message);
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $validatedData = $request->all();

        $user = $this->userService->createOrUpdate($validatedData);

        if(is_null($user) === false){
            $message = message("Operation succeed.");
        }else{
            $message = message("Operation failed.", "error");
        }

        session()->flash("message", $message);
        return redirect()->back();
    }
    
    public function view(Request $request, $id)
    {
        $user = $this->userService->getById($id);
        return view("users.view", compact(["user"]));
    }

    public function delete(Request $request, $id)
    {
        $user = $this->userService->getById($id);

        $response = $this->userService->delete($user);
        
        if($response === true){
            $message = message("Operation succeed.");
        }else{
            $message = message("Operation failed.", "error");
        }

        session()->flash("message", $message);
        return redirect()->back();
    }
}
