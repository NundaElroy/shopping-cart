<?php
namespace App\Services;

use App\Models\UserModel;
use App\Exceptions\ValidationException;
use Config\Services;

class UserService{

   protected $usermodel;
   protected static array $validationRules = [
    'email' => 'required|valid_email|is_unique[users.email]',
    'password' => 'required|min_length[8]',
   ]; 
   protected $validation ;

   public function __construct() {
         $this->usermodel = new UserModel();
         $this->validation = Services::validation();

   }
   
   //create user
   public function createUser(array $data): array {
        $this->validation->setRules(self::$validationRules);
        if (!$this->validation->run($data)) {
            throw new ValidationException($this->validation->getErrors(),"could not register user");
        }
        //save details
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->usermodel->insert($data);
        
        return $this->usermodel->find($this->usermodel->insertID());
   }

   //validate user details
   public function validateUser(array $credentials): bool
   {
       $email = $credentials['email'];
       $password = $credentials['password'];
   
       // Retrieve the user by email
       $user = $this->usermodel->where('email', $email)->first();
   
       // Check if user exists
       if (! $user) {
           return false; // User not found
       }
   
       // Verify password
       if (! password_verify($password, $user['password'])) {
           return false; // Password mismatch
       }
   
       // If validation passes
       return true;
   }
   
}