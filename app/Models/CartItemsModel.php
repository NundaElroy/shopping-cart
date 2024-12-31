<?php

namespace App\Models;

use CodeIgniter\Model;

class CartItemsModel extends Model
{
    protected $table = 'cart_items';
    protected $primaryKey = 'cart_item_id';
    protected $allowedFields = ['cart_id', 'product_id', 'quantity'];
    protected $returnType      = 'array';
    
  


    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
   

    

}
