<?php

namespace App\Controllers;


use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Exceptions\ValidationException;
use App\Services\UserService;
use CodeIgniter\Shield\Authentication\JWTManager;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Authentication\Passwords;

class AuthController extends ResourceController
{   
    protected $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }
   // register user
    // public function register()
    // {  
    //     try{
    //         $data = $this->request->getJSON(true);
    //         $user = $this->userService->createUser($data);

    //         // Remove sensitive data from response
    //         unset($user['password']);

            
    //         return $this->respondCreated($user,"user created successfuly");
            

    //     }catch(ValidationException $e){
            
        

    //         return $this->fail($e, 400, null ,"user not created");

    //     }

    // }


    public function register() {
        // Debug incoming request - prints raw JSON string to error log
        log_message('error', 'Raw request body: ' . file_get_contents('php://input'));
        
        // Get and validate JSON data
        $data = $this->request->getJSON(true);
        
        // print_r creates a readable string representation of array/object
        // Here we're logging the parsed JSON data for debugging
        log_message('error', 'Parsed request data: ' . print_r($data, true));
        
        if (empty($data)) {
            return $this->fail(['message' => 'No data received. Please check request body.']);
        }
    
        // Updated validation rules to include username
        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[auth_identities.secret]',
            'password' => 'required|min_length[8]'
        ];
        
        if (!$this->validateData($data, $rules, [])) {
            return $this->fail([
                'errors' => $this->validator->getErrors()
            ]);
        }
    
        // Create user
        $user = new \CodeIgniter\Shield\Entities\User();
        $user->fill($data);
        
        // var_dump and print_r are both debug functions, but var_dump provides more detail
        // Here we log the complete user entity state before saving
        ob_start(); // Start output buffering
        var_dump($user);
        $userDebug = ob_get_clean(); // Get the output and clear the buffer
        log_message('error', 'User entity before save (detailed): ' . $userDebug);
        
        // Save user (only once)
        $users = auth()->getProvider();
        if (!$users->save($user)) {
            return $this->failServerError('Failed to save the user.');
        }

        // Get complete user record 
        //user variable reassigned
        $user_id = $users->getInsertID(); 
        $user = $users->findById($user_id);
        
      
        try {
            $users->addToDefaultGroup($user);
        } catch (\Exception $e) {
            log_message('error', 'Failed to add user to default group: ' . $e->getMessage());
            return $this->failServerError('Failed to add user to default group.');
        }
        
        // Remove sensitive data before sending response
        $responseData = [
            'id'    => $user_id,
            'email' => $user->getEmail(),
        
        ];

        return $this->respondCreated($responseData, "User created successfully.");
    }


    /**
     * Authenticate Existing User and Issue JWT.
     */
    public function login(){

        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]',
        ];
        
        

        

        //validating user input 
        if (! $this->validateData($this->request->getJSON(true), $rules, [])) {
            return $this->fail(
                ['errors' => $this->validator->getErrors()],
                $this->codes['unauthorized']
            );
        }
        

        // Get the credentials for login
        $credentials             = $this->request->getJsonVar(setting('Auth.validFields'));
        $credentials             = array_filter($credentials);
        $credentials['password'] = $this->request->getJsonVar('password');

        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        // Check the credentials
        $result = $authenticator->check($credentials);

        // ob_start();
        // var_dump($result);
        // $result1 = ob_get_contents(); //or ob_get_clean()
        // log_message('error', $result1);

        // Credentials mismatch.
        if (! $result->isOK()) {
            // @TODO Record a failed login attempt

            return $this->failUnauthorized($result->reason());
        }

        // Credentials match.
        // @TODO Record a successful login attempt

        $user = $result->extraInfo();

        /** @var JWTManager $manager */
        $manager = service('jwtmanager');

        // Generate JWT and return to client
        $jwt = $manager->generateToken($user);

        return $this->respond([
            'access_token' => $jwt,
        ]);
}
}
