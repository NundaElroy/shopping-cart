<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
    protected function initialize(): void
    {
        parent::initialize();

        $this->allowedFields = [
               ...$this->allowedFields,
                    'email', // Added
            
        ]; 

        // $this->allowedFields = array_merge($this->allowedFields, ['email']);
    }
}
