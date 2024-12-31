<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CartModel;
use App\Models\ProductModel;
use App\Models\CartItemsModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class CartController extends ResourceController
{

    protected $cartModel;
    protected $cartItemModel;
    protected $productModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
        $this->cartItemModel = new CartItemsModel();
        $this->productModel = new ProductModel();
    }
    public function getCart()
    {   
        //get user id for authenticated user 
         $userId = auth()->id();
         
        ob_start(); // Start output buffering
        var_dump($userId);
        $userDebug = ob_get_clean(); // Get the output and clear the buffer
        log_message('error', 'User entity before save (detailed): ' . $userDebug);
         // Get or create cart
         $cart = $this->cartModel->where('user_id', $userId)->first();
        
         if (!$cart) {
             $cartId = $this->cartModel->insert(['user_id' => $userId]);
             $cart = $this->cartModel->find($cartId);
         }

         $builder = $this->cartItemModel->builder();
         $builder->select('cart_items.*, products.*')
                 ->join('products', 'products.product_id = cart_items.product_id')
                 ->where('cart_items.cart_id', $cart['cart_id']);

         $cartItems = $builder->get()->getResultArray();

             // Format response
        $response = [
            'user_id' => $userId,
            'cart_id' => $cart['cart_id'],
            'items' => array_map(function($item) {
                return [
                    'cart_item_id' => $item['cart_item_id'],
                    'product' => [
                        'product_id' => $item['product_id'], // Changed to product_id
                        'name' => $item['name'],
                        'price' => $item['price'],
                        // Add other product fields you need
                    ],
                    'quantity' => $item['quantity'],
                    'created_at' => $item['created_at']
                ];
            }, $cartItems)
        ];
        return $this->respond($response);
    }

    
}
