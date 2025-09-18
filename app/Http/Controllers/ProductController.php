<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected Product $product)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if($request->has('max_price')) {
                $products = $this->product->where('price', '<=', $request->max_price)->get();
                return $this->jsonResponse("Products fetched successfully", $products);
            }else if ($request->has('min_price')) {
                $products = $this->product->where('price', '>=', $request->min_price)->get();
                return $this->jsonResponse("Products fetched successfully", $products);
            }else{
                $products = $this->product->get();
                return $this->jsonResponse("Products fetched successfully", $products);
            }
        } catch (\Exception $e) {
            return $this->jsonResponse("Error fetching products", [], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try {
            $data = $request->validated();
            $product = $this->product->create($data);
            return $this->jsonResponse("Product created successfully", $product, 201);
        } catch (\Exception $e) {
            return $this->jsonResponse("Error creating product", [], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = $this->product->find($id);
            if (!$product) {
                return $this->jsonResponse("Product not found", [], 404);
            }
            return $this->jsonResponse("Product fetched successfully", $product);
        } catch (\Exception $e) {
            return $this->jsonResponse("Error fetching product", [], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $product = $this->product->find($id);
            if (!$product) {
                return $this->jsonResponse("Product not found", [], 404);
            }
            $data = $request->all();
            $product->update($data);
            return $this->jsonResponse("Product updated successfully", $product);
        } catch (\Exception $e) {
            return $this->jsonResponse("Error updating product", [], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = $this->product->find($id);
            if (!$product) {
                return $this->jsonResponse("Product not found", [], 404);
            }
            $product->delete();
            return $this->jsonResponse("Product deleted successfully");
        } catch (\Exception $e) {
            return $this->jsonResponse("Error deleting product", [], 500);
        }
    }

    private function jsonResponse($msg, $data = [], $status = 200)
    {
        return response()->json([
            "data" => ProductResource::collection(
                is_iterable($data) ? $data : [$data]
            ),
            "status" => $status,
            "message" => $msg
        ]);
    }
}
