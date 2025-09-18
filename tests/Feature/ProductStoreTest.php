<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductStoreTest extends TestCase
{
    use RefreshDatabase;

    public function TestProductStoring()
    {
        $data = [
            'name' => 'TestStoring',
            'price' => 10,
            'stock' => 1,
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'status' => 201,
                     'message' => 'Product created successfully',
                     'data' => [
                         ['name' => 'TestStoring']
                     ]
                 ]);

        $this->assertDatabaseHas('products', $data);
    }
    
}
