<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'product_id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['name','price','stock'];
    protected $returnType      = 'array';
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name'  => 'required|min_length[3]|max_length[255]',
        'price' => 'required|decimal|greater_than[0]',
        'stock' => 'required|integer|greater_than_equal_to[0]',
    ];
    
    protected $validationMessages = [
        'name' => [
            'required'    => 'The product name is required.',
            'min_length'  => 'The product name must be at least 3 characters long.',
            'max_length'  => 'The product name cannot exceed 255 characters.',
        ],
        'price' => [
            'required'    => 'The price is required.',
            'decimal'     => 'The price must be a valid decimal number.',
            'greater_than' => 'The price must be greater than 0.',
        ],
        'stock' => [
            'required'    => 'The stock is required.',
            'integer'     => 'The stock must be an integer.',
            'greater_than_equal_to' => 'The stock must be 0 or more.',
        ],
    ];
    
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

  
}
