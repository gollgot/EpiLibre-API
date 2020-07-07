<?php

namespace App\Http\Controllers;

use App\CustomHelpers\JSONResponseHelper;
use App\Role;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Return all users that are not validate (pending validation)
     * @param Request $request The request
     * @return \Illuminate\Http\JsonResponse Json response
     */
    public function pending(Request $request){
        $JSONResponseHelper = new JSONResponseHelper();

        $users = User::select("firstname", "lastname", "email", "created_at")
                        ->where("confirmed", false)
                        ->orderBy('created_at', 'asc')
                        ->get();

        return $JSONResponseHelper->successJSONResponse($users);
    }

}
