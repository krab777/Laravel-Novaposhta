<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\DetailedProductResource;
use App\Http\Resources\ListProductResource;
use App\Http\Resources\ProductResource;
use App\Models\ProductUser;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('users')->get();
        return ListProductResource::collection($products);
    }

    public function store(CreateProductRequest $request)
    {
        $productData = $request->validated();
        $product = Product::create($productData);

        if(isset($productData['users_id'])) {
            foreach ($productData['users_id'] as $user_id) {
                $userProductData[] = [
                    'user_id'    => $user_id,
                    'product_id' => $product['id'],
                ];
            }

            ProductUser::insert($userProductData);
        }


        return new DetailedProductResource($product);
    }

    public function show($id)
    {
        $product = Product::with('users')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return new DetailedProductResource($product);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $productData = $request->validated();
        $product->update($productData);

        if(isset($productData['users_id'])) {
            $product->users()->detach();

            foreach ($productData['users_id'] as $user_id) {
                $userProductData[] = [
                    'user_id'    => $user_id,
                    'product_id' => $product['id'],
                ];
            }

            ProductUser::insert($userProductData);
        } else {
            $product->users()->detach();
        }


        return new DetailedProductResource($product);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->users()->detach();
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}

