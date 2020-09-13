<?php

namespace App\Http\Controllers;

use App\CustomHelpers\JSONResponseHelper;
use App\Role;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;

class UserController extends Controller
{

    /** Return all users that is valid and not deleted
     * @return \Illuminate\Http\JsonResponse Json response
     */
    public function index(){
        $JSONResponseHelper = new JSONResponseHelper();
        $users = User::select("id", "firstname", "lastname", "email", "role_id")
                        ->with("role")
                        ->where("confirmed", true)
                        ->where("deleted", false)
                        ->orderBy("firstname", "ASC")
                        ->orderBy("lastname", "ASC")
                        ->get();

        return $JSONResponseHelper->successJSONResponse($users);
    }

    /**
     * Return all users that are not validate (pending validation)
     * @param Request $request The request
     * @return \Illuminate\Http\JsonResponse Json response
     */
    public function pending(Request $request){
        $JSONResponseHelper = new JSONResponseHelper();

        $users = User::select("id", "firstname", "lastname", "email")
                        ->where("confirmed", false)
                        ->orderBy('created_at', 'asc')
                        ->get();

        return $JSONResponseHelper->successJSONResponse($users);
    }

    /**
     * Confirm a specific User to be able to use the app
     * @param Integer $user_id The user ID we want to confirm
     * @param Request $request The Request
     * @return \Illuminate\Http\JsonResponse Json response
     */
    public function confirm($user_id, Request $request){
        $JSONResponseHelper = new JSONResponseHelper();
        $user = User::find($user_id);

        // Wrong $user_id -> throw 400 bad request
        if(empty($user)){
            return $JSONResponseHelper->badRequestJSONResponse();
        }

        // Confirm the user and save
        $user->confirmed = true;
        $user->save();

        return $JSONResponseHelper->successJSONResponse($user);
    }

    /**
     * Unconfirm (delete) a specific User that we don't want he uses the app
     * @param Integer $user_id The user ID we want to unconfirm
     * @param Request $request The Request
     * @return \Illuminate\Http\JsonResponse Json response
     */
    public function unconfirm($user_id, Request $request){
        $JSONResponseHelper = new JSONResponseHelper();
        $user = User::find($user_id);

        // Wrong $user_id or try to unconfirm a user which is already confirmed -> throw 400 bad request
        if(empty($user) || $user->confirmed == true){
            return $JSONResponseHelper->badRequestJSONResponse();
        }

        // Deleted resource
        $resource = [
            "firstname" => $user->firstname,
            "lastname" => $user->lastname
        ];

        // Delete the pending user
        $user->delete();

        return $JSONResponseHelper->successJSONResponse($resource);
    }

}
