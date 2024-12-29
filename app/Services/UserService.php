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
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->usermodel->insert($data);
        
        return $this->usermodel->find($this->usermodel->insertID());
   }
}