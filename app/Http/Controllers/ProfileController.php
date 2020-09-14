<?php


namespace App\Http\Controllers;


use App\CustomHelpers\JSONResponseHelper;
use App\Unit;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProfileController extends Controller
{

    /**
     * Update the first name, last name, email of the user
     * @param Request $request The Request
     */
    public function update(Request $request){
        $JSONResponseHelper = new JSONResponseHelper();

        $firstname = $request->get("firstname");
        $lastname = $request->get("lastname");
        $email = $request->get("email");

        // Empty fields
        if(empty($firstname) || empty($lastname) || empty($email)){
            return $JSONResponseHelper->badRequestJSONResponse();
        }
        // Correct
        else{
            // Fetch the user that will update his profile
            $user = User::where("tokenAPI", $request->bearerToken())->first();
            $oldEmail = $user->email;

            $user->firstname = Str::ucfirst(strtolower($firstname));
            $user->lastname = Str::ucfirst(strtolower($lastname));

            // Want to change the email
            if($oldEmail != $email){
                // New email is already used
                if(User::where("email", $email)->exists()){
                    return $JSONResponseHelper->badRequestJSONResponse("email already used");
                }
                // New email is free to use
                else{
                    $user->email = $email;
                }
            }

            // Update the user
            try {
                $user->save();
            }catch(\Exception $e){
                // Probably too long string for the field in DB
                return $JSONResponseHelper->badRequestJSONResponse();
            }

            // Success
            return $JSONResponseHelper->successJSONResponse($user);
        }
    }


    /**
     * Change the password of the current user 
     * @param Request $request The request
     * @return \Illuminate\Http\JsonResponse Json response
     */
    public function changePassword(Request $request){
        $hashAlgo = "sha256";

        $JSONResponseHelper = new JSONResponseHelper();

        $oldPass = $request->get("oldPassword");
        $newPass = $request->get("newPassword");
        $newPassRepeated = $request->get("newPasswordRepeated");

        // Check not empty
        if(empty($oldPass) || empty($newPass) || empty($newPassRepeated)){
            return $JSONResponseHelper->badRequestJSONResponse();
        }

        // check
        if($newPass != $newPassRepeated){
            return $JSONResponseHelper->badRequestJSONResponse("New password repeated must correspond to the new password");
        }

        // Fetch the user that will update his profile
        $user = User::where("tokenAPI", $request->bearerToken())->first();
        // Check old password from request is same as the user's password
        if($user->password != hash($hashAlgo,$oldPass)){
            return $JSONResponseHelper->badRequestJSONResponse("Incorrect old password");
        }

        // All correct
        $user->password = hash($hashAlgo, $oldPass);
        $user->save();

        return $JSONResponseHelper->successJSONResponse([
            "Password successfully updated"
        ]);
    }

}
