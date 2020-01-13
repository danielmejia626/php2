<?php

namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Validator as v;

class UsersController extends BaseController {
    public function getAddUser() {
        return $this->renderHTML('email.twig');
    }

    public function postSaveUser($request) {
        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();
            $userValidator = v::key('email', v::email()->notEmpty())
                  ->key('password', v::stringType()->notEmpty());

            try {
                $userValidator->assert($postData); 
                $postData = $request->getParsedBody();

                if (User::where('user', '=', $postData['email'])->exists()) {
                    $responseMessage = 'The user already exists!';
                }else {

                    $user = new User();
                    $user->user = $postData['email'];
                    $user->password = password_hash($postData['password'], PASSWORD_DEFAULT);
                    $user->save();

                    $responseMessage = 'Sucessfully saved';
                }
            } catch (\Exception $e) {
                $responseMessage = $e->getMessage();
            }

        }

        return $this->renderHTML('email.twig', [
            'responseMessage' => $responseMessage
        ]);
    }
}
