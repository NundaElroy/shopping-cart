<?php

namespace App\Controllers;


use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Exceptions\ValidationException;
use App\Services\UserService;

class AuthController extends ResourceController
{   
    protected $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }
    //register user
    public function register()
    {  
        try{
            $data = $this->request->getJSON(true);
            $user = $this->userService->createUser($data);

            // Remove sensitive data from response
            unset($user['password']);

            
            return $this->respondCreated($user,"user created successfuly");
            

        }catch(ValidationException $e){
            
        

            return $this->fail($e, 400, null ,"user not created");

        }

    }
    
}
