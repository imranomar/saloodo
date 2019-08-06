<?php

namespace Tests\Feature;

use App\Cts;
use App\User;
use App\Product;
use function MongoDB\BSON\toJSON;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class ProductTest extends TestCase
{
    use RefreshDatabase; //migrate new database ( in memory sqlite in phpunit conf file )

    public function setUp(): void
    {
        parent::setUp();

        //Lets do this right
        //$this->withHeader('HTTP_CONTENT_TYPE','application/json');
        $this->withHeader('HTTP_ACCEPT','application/json');
    }

    /**
     * Test can view product
     *
     */
    public function test_can_view_product()
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

    /**
     * Test can view bundled product
     *
     */
    public function test_can_view_bundled_product()
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
     * Test admin can create a product
     *
     */
    public function test_admin_can_create_product()
    {

        //enable passport
        \Artisan::call('passport:install');

        //create admin user
        $user = factory(User::class)->create();
        $user->role_id = Cts::ROLE_ADMIN;
        $user->save();
        Passport::actingAs($user);

        $token = $user->generateToken();

        $headers = [ 'Authorization' => 'Bearer $token'];

        $data_sent = [
            'title' => "testing title 1",
            'price' => 25,
            'discount' => 5,
            'discount_type' => 0,
        ];

        $data_received = [
            'title' => "testing title 1",
            'price' => 20.00,
            'discount' => 5,
            'discount_type' => 0,
            'price_with_symbol' => Cts::CURRENCY_SYMBOL . "20.00"
        ];

        $this->actingAs($user)->post(route('product.store'), $data_sent, $headers)
            ->assertStatus(Cts::HTTP_STATUS_CREATED)
            ->assertJson($data_received);
    }


    /**
     * Test non-admin cannot create a product
     *
     */
    public function test_non_admin_cannot_create_product()
    {

        //enable passport
        \Artisan::call('passport:install');

        //create user with non admin role
        $user = factory(User::class)->create();
        $user->role_id = Cts::ROLE_GUEST;
        $user->save();
        Passport::actingAs($user);

        $token = $user->generateToken();

        $headers = [ 'Authorization' => 'Bearer $token'];

        $data_sent = [];

        $this->actingAs($user)->post(route('product.store'), $data_sent, $headers)
            ->assertStatus(Cts::HTTP_STATUS_UNAUTHORIZED);

    }

    /**
     * Test product deletion
     *
     */
    public function test_can_delete_product()
    {
        $product = factory(Product::class)->create();
        $this->delete(route('product.destroy', $product->id))->assertStatus(Cts::HTTP_STATUS_UNAUTHORIZED);
    }

    //Test non admin cannot user should not be able to create a user

}
