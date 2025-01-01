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
         
       
         // Get or create cart
         $cart = $this->cartModel->where('user_id', $userId)->first();
        
         if (!$cart) {
             $cartId = $this->cartModel->insert(['user_id' => $userId]);
             $cart = $this->cartModel->find($cartId);
         }

        $response = $this->createCartResponse($cart,$userId);
        return $this->respond($response);
    }

    //add item to cart 
    public function addItemToCart(){


        //validate input from user 
        $rules = [
            'id' => 'required|integer',
            'quantity'=> 'required|integer|greater_than[0]'

        ];

        $productDetails = $this->request->getJSON(true);
        
        //validating user input 
        if (! $this->validateData($productDetails, $rules, [])) {
            return $this->fail(
                ['errors' => $this->validator->getErrors()]
                 
            );
        }

        $product  = $this->productModel->where('product_id',$productDetails['id'])->first();

        //check if exists
        if(!$product){
            return $this->fail(null,404,null,"product does not exist");
        }
        
        //check if stock is sufficient
        if($productDetails['quantity'] > $product['stock']){
            return $this->fail(null,404,null,"product is than requires");
        }


        
        //cart attached to user
        //get user id for authenticated user 
        $userId = auth()->id();
        $cart = $this->cartModel->where('user_id', $userId)->first();

        //insert into cart items
        if(

        !$this->cartItemModel->save([
            'cart_id' => $cart['cart_id'], 
            'product_id'=>$product['product_id'], 
            'quantity' => $productDetails['quantity'] 
        ])

        ){
            return $this->fail(null,400,null,"product not added to cart ");

        }

        $response = $this->createCartResponse( $cart, $userId);




        return $this->respond($response);



    }

    /**
     * @param array $cart  arrary of cart   
     * @param $userId id for the user
     * @return custom response array 
     * 
     */

    private function createCartResponse(array $cart,int $userId):mixed{

        $builder = $this->cartItemModel->builder();
        $builder->select('cart_items.*, products.*')
                ->join('products', 'products.product_id = cart_items.product_id')
                ->where('cart_items.cart_id', $cart['cart_id']);

        $cartItems = $builder->get()->getResultArray();

            // Formatted response

      return  [
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
        
    }



    /**
     * Edit cart item quantity
     * 
     * @param int $cartItemId The ID of the cart item to edit
     * @return ResponseInterface
     */
    public function editItem($cartItemId)
    {
        // Validate input
        $rules = [
            'quantity' => 'required|integer|greater_than[0]'
        ];

        $input = $this->request->getJSON(true);
        
        if (!$this->validateData($input, $rules)) {
            return $this->fail(
                ['errors' => $this->validator->getErrors()]
            );
        }

        // Get user's cart
        $userId = auth()->id();
        $cart = $this->cartModel->where('user_id', $userId)->first();
        
        if (!$cart) {
            return $this->fail(null, 404, null, "Cart not found");
        }

        // Get cart item and verify ownership
        $cartItem = $this->cartItemModel->find($cartItemId);
        
        if (!$cartItem) {
            return $this->fail(null, 404, null, "Cart item not found");
        }
        
        if ($cartItem['cart_id'] !== $cart['cart_id']) {
            return $this->fail(null, 403, null, "Unauthorized access to cart item");
        }

        // Check product stock
        $product = $this->productModel->where('product_id', $cartItem['product_id'])->first();
        
        if (!$product) {
            return $this->fail(null, 404, null, "Product no longer exists");
        }

        if ($input['quantity'] > $product['stock']) {
            return $this->fail(null, 400, null, "Requested quantity exceeds available stock");
        }

        // Update cart item
        if (!$this->cartItemModel->update($cartItemId, [
            'quantity' => $input['quantity']
        ])) {
            return $this->fail(null, 500, null, "Failed to update cart item");
        }

        // Return updated cart
        $response = $this->createCartResponse($cart, $userId);
        return $this->respond($response);
    }

    /**
     * Remove item from cart
     * 
     * @param int $cartItemId The ID of the cart item to remove
     * @return ResponseInterface
     */
    public function removeItem($cartItemId)
    {
        // Get user's cart
        $userId = auth()->id();
        
        $cart = $this->cartModel->where('user_id', $userId)->first();
        
        if (!$cart) {
            return $this->fail(null, 404, null, "Cart not found");
        }

        // Get cart item and verify ownership
        $cartItem = $this->cartItemModel->find($cartItemId);
        
        if (!$cartItem) {
            return $this->fail(null, 404, null, "Cart item not found");
        }
        
        if ($cartItem['cart_id'] !== $cart['cart_id']) {
            return $this->fail(null, 403, null, "Unauthorized access to cart item");
        }

        // Remove cart item
        if (!$this->cartItemModel->delete($cartItemId)) {
            return $this->fail(null, 500, null, "Failed to remove cart item");
        }

        // Return updated cart
        $response = $this->createCartResponse($cart, $userId);
        return $this->respond($response);
    }
}

    

