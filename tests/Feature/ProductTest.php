<?php

namespace Tests\Feature;

use App\Cts;
use App\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase; //migrate new database ( in memory sqlite in phpunit conf file )

    public function setUp(): void
    {
        parent::setUp();

        //Lets do this right
        $this->withHeader('HTTP_CONTENT_TYPE','application/json');
        $this->withHeader('HTTP_ACCEPT','application/json');
    }

    /**
     * Test viewing product
     *
     * @return void
     */
    public function test_can_create_and_view_product()
    {
        //create dummy product
        $product = factory(Product::class)->create();

        $this->json('GET', route('product.show', $product->id))->assertJson([
            'id' => $product->id,
            'title' => $product->title,
            'price' => $product->price,
            'discount' =>$product->discount,
            'price_without_discount' => $product->price_without_discount,
            'price_with_symbol' => $product->price_with_symbol,
            'bundle' => [],
        ]);
    }

    public function test_can_create_and_view_product_with_bundle()
    {
        //create dummy products
        $product_1 = factory(Product::class)->create();
        $product_2 = factory(Product::class)->create();
        $product_3 = factory(Product::class)->create();




        //product 2 and 3 are bundles with 1
        $product_1->bundle()->sync([$product_2->id,$product_3->id]);

        $this->json('GET', route('product.show', $product_1->id))->assertJson([
            'id' => $product_1->id,
            'title' => $product_1->title,
            'price' => $product_1->price,
            'discount' =>$product_1->discount,
            'price_without_discount' => $product_1->price_without_discount,
            'price_with_symbol' => $product_1->price_with_symbol,
            'bundle' =>
            [
                [
                'id'=>$product_2->id,
                'title'=>$product_2->title,
                'price'=>$product_2->price,
                'discount'=>$product_2->discount,
                'price_without_discount'=>$product_2->price_without_discount,
                'price_with_symbol'=>$product_2->price_with_symbol,
                ],
                [
                    'id'=>$product_3->id,
                    'title'=>$product_3->title,
                    'price'=>$product_3->price,
                    'discount'=>$product_3->discount,
                    'price_without_discount'=>$product_3->price_without_discount,
                    'price_with_symbol'=>$product_3->price_with_symbol,
                ],
            ],
        ]);
    }



    /**
     * Test product deletion
     *
     * @return void
     */
    public function test_can_delete_post()
    {
        $product = factory(Product::class)->create();
        $this->delete(route('product.destroy', $product->id))->assertStatus(Cts::HTTP_STATUS_UNAUTHORIZED);
    }
}
