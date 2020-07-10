<?php

namespace App\Http\Controllers;

use App\CustomHelpers\JSONResponseHelper;
use App\Role;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Basic Auth and JWT token generation if auth granted
     * @param Request $request The request
     * @return \Illuminate\Http\JsonResponse Json response
     */
    public function login(Request $request){
        $JSONResponseHelper = new JSONResponseHelper();

        // Extract the Basic auth credentials
        $credentials = explode(":", base64_decode(substr($request->header('Authorization'), 6)));

        // Credentials not found
        if(empty($credentials)){
            return $JSONResponseHelper->badRequestJSONResponse("Invalid login request");
        }
        // Credentials founds
        else{
            $user = User::where([
                "email" => $credentials[0],
                "password" => $credentials[1],
                "confirmed" => true,
                "deleted" => false]
            )->with("role")->first();

            // Wrong credentials
            if(empty($user)){
                return $JSONResponseHelper->badRequestJSONResponse("Invalid login request");
            }
            // Auth OK -> Generate JWT
            else{
                $token = [
                    "id" => $user->id,
                    "firstname" => $user->firstname,
                    "lastname" => $user->lastname,
                    "email" => $user->email,
                    "role" => $user->role->shortName
                ];
                $token = JWT::encode($token, env("JWT_SECRET", null));
                return $JSONResponseHelper->successJSONResponse(['token' => $token]);
            }
        }

    }

    /**
     * Register function (need confirmation by SUPER_ADMIN to be activated)
     * @param Request $request The request
     * @return \Illuminate\Http\JsonResponse Json response
     */
    public function register(Request $request){
        $firstname = $request->input("firstname");
        $lastname = $request->input("lastname");
        $email = $request->input("email");
        $password = $request->input("password");
        $passwordRepeated = $request->input("passwordRepeated");

        $JSONResponseHelper = new JSONResponseHelper();

        // Fields missing or incorrect
        if(empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($passwordRepeated)
            || !filter_var($email, FILTER_VALIDATE_EMAIL)
            || ($password != $passwordRepeated)){
            return $JSONResponseHelper->badRequestJSONResponse("Some fields are missing or incorrect");
        }
        // Fields correct
        else {
            // Check email uniqueness
            $user = User::where("email", $email)->first();
            if(!empty($user)){
                return $JSONResponseHelper->badRequestJSONResponse("Email already used");
            }
            // All correct
            else{
                try {
                    $sellerRole = Role::where("shortName", "SELLER")->first();
                    $user = new User();
                    $user->firstname = $firstname;
                    $user->lastname = $lastname;
                    $user->email = $email;
                    $user->password = $password;
                    $user->deleted = false;
                    $user->confirmed = false;
                    $user->role()->associate($sellerRole);

                    $user->save();
                    return $JSONResponseHelper->createdJSONResponse($user);
                }catch(\Exception $e){
                    // Error
                    return $JSONResponseHelper->badRequestJSONResponse();
                }
            }
        }
    }

}
