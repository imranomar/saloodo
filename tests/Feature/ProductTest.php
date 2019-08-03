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
    public function test_can_show_post()
    {
        $product = factory(Product::class)->create();
        $this->get(route('product.index', $product->id))->assertStatus(Cts::HTTP_STATUS_OK);
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
