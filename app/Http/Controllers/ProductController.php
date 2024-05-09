<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shopify = app('shopify');

        return new ProductResource($shopify->getProducts());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $shopify = app('shopify');

        $product = $shopify->createProduct($request->all());
        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $shopify = app('shopify');
        
        try {
            $product = $shopify->getProduct($id);
            return new ProductResource($product);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 404,
                'message' => "Can not found product has id $id",
            ]);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $shopify = app('shopify');
        try {
            $product = $shopify->updateProduct($id, $request->all());
            return new ProductResource($product);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 404,
                'message' => "Something went wrong when updating product has id $id",
            ]);
        }
        
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $shopify = app('shopify');
        try {
            $shopify->deleteProduct($id);
            return response()->json([
                'status'=> 200,
                'message' => "Product deleted successfully!"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 404,
                'message' => "Something went wrong when deleting product has id $id",
            ]);
        }
        
    }
}