<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class ProductController extends ResourceController
{   
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function create()
    {
       
        //do some input validation 
        if(!$this->validate($this->productModel->getValidationRules(),$this->productModel->getValidationMessages())){
            return $this->fail($this->validator->getErrors());
        }

        $productDetails = $this->request->getJSON(true);

        //check if product exists 
        //model obj is returned is returned 
        $check = $this->productModel->where('name',$productDetails['name'])->first();
        if($check){
            $updateData = [
                'product_id' => $check->getInsertID(), // Ensure correct product is updated
                'price'      => $productDetails['price'],
                'stock'      => $productDetails['stock'],
            ];

             //save the product
            if($this->productModel->save($updateData)){
                return $this->respond($updateData,201,"product updated");
            } 

            return $this->failServerError('Failed to update the product.');
        }

        //incase the product is new 

         // Create a new product
         if ($this->productModel->save($productDetails)) {
            return $this->respondCreated($productDetails,"Product created successfully");
        }

        return $this->failServerError('Failed to create product.');

    }

    public function getAllProducts(){
        $products = $this->productModel->findAll();
        if($products){
            return $this->respond($products,200,"successful request");
        }
           return $this->fail(null,400,null,"oops no products");
    }

    public function getProductById($id)
    {
        // Fetch the product by ID
        $product = $this->productModel->find($id);

        if ($product) {
            // Return product as JSON
            return $this->respond($product);
        } else {
            // Product not found, return a 404 response
            return $this->fail(null,400,null,"oops product not found");
                                  
        }
    }
}
