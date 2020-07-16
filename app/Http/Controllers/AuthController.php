<?php

namespace App\Http\Controllers;

use App\CustomHelpers\JSONResponseHelper;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        // Credentials founds and user is confirmed and not soft deleted
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
                // Update tokenAPI for more security and return the user as resource
                $user->tokenAPI = Str::random(64);
                $user->save();
                return $JSONResponseHelper->successJSONResponse([
                    'id' => $user->id,
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'email' => $user->email,
                    'role' => $user->role['shortName'],
                    'tokenAPI' => $user->tokenAPI
                ]);
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
                    $user->firstname = Str::ucfirst(strtolower($firstname)); // Store first name with the first character capitalized
                    $user->lastname = Str::ucfirst(strtolower($lastname)); // Store last name with the first character capitalized
                    $user->email = $email;
                    $user->password = $password;
                    $user->deleted = false;
                    $user->confirmed = false;
                    $user->tokenAPI = Str::random(64);
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
