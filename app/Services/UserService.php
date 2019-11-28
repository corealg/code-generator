<?php

namespace App\Services;

use App\User;
use App\Services\ErrorNotifierService;
use App\Services\SettingService;

class UserService
{
    protected $errorNotifier;
    protected $settingService;

    public $paginatedList = true;

    public function __construct()
    {
        $this->errorNotifier = new ErrorNotifierService();
        $this->settingService = new SettingService();
    }

    public function lists($data = null)
    {
        $search_query = [];

        $order = "desc";

        $query = User::query();

        // if(isset($data["search"])){

        //     $search_query = [
        //         "search" => $data["search"]
        //     ];

        //     $query->where(function($q) use($data){
        //         $q->orWhere("name", "LIKE", "%".$data["search"]."%");
        //     });
        // }

        $query->orderBy('id', $order);

        if ($this->paginatedList === true) {

            $item_per_page = $this->settingService->get("item_per_page", 25) ?? 25;

            $users = $query->paginate($item_per_page)->appends($search_query);
            $users->pagination_summary = get_pagination_summary($users);
        } else {
            $users = $query->get();
        }

        return $users;
    }

    public function updateOrCreate($data)
    {
        $user_id = auth()->user()->id;

        if(!empty($data["id"])){
            // update

            $user = User::whereId($data["id"])->first();
            $user->updated_by = $user_id;

        }else{
            //create

            $user = new User();
            $user->created_by = $user_id;
        }

        
        $user->type = $data['type'];
                

        $user->name = $data['name'];
                

        $user->email = $data['email'];
                

        $user->password = $data['password'];
                

        return $user->save() ? $user : null;
    }

    public function getById($id)
    {
        return User::find($id);
    }

    public function delete($user)
    {
        $user = $user->delete();
        return $user;
    }
}
