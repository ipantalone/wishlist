<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductTest extends TestCase
{
    private $user;

    public function generateUser()
    {
        $this->user = User::find(1);
    }

    public function testList_Success()
    {
        $this->generateUser();
        $wishlist = $this->user->wishlists()->first();
        $response = $this->actingAs($this->user)->call('GET', '/wishlist/' . $wishlist->id_wishlist . '/product');
        $this->assertEquals(200, $response->status());
        $this->seeJsonStructure([
            'id_wishlist',
            'wishlist',
            'total',
            'data' => [
                [
                    'id_product', 'title'
                ]
            ]
        ]);
    }

    public function testList_NotFound()
    {
        $this->generateUser();
        $response = $this->actingAs($this->user)->call('GET', '/wishlist/9999/product');
        $this->assertEquals(404, $response->status());
    }

    public function testShowProduct_Success()
    {
        $this->generateUser();
        $wishlist = $this->user->wishlists()->first();
        $response = $this->actingAs($this->user)->call('GET', '/wishlist/' . $wishlist->id_wishlist . '/product/' . $wishlist->products()->first()->id_product);
        $this->assertEquals(200, $response->status());
        $this->seeJsonStructure([
            'id_product',
            'title',
            'id_wishlist',
            'created_at',
            'updated_at'
        ]);
    }

    public function testShowProduct_NotFound()
    {
        $this->generateUser();
        $response = $this->actingAs($this->user)->call('GET', '/wishlist/9999/product/9999');
        $this->assertEquals(404, $response->status());
    }

    public function testCreate_MissingData()
    {
        $this->generateUser();
        $wishlist = $this->user->wishlists()->first();
        $response = $this->actingAs($this->user)->call('POST', '/wishlist/' . $wishlist->id_wishlist . '/product');
        $this->assertEquals(422, $response->status());
    }

    public function testCreate_Success()
    {
        $this->generateUser();
        $text_unittest = Str::random(32);
        $wishlist = $this->user->wishlists()->first();
        $response = $this->actingAs($this->user)->call('POST', '/wishlist/' . $wishlist->id_wishlist . '/product', [
            'title' => $text_unittest
        ]);
        $this->assertEquals(201, $response->status());
        $this->seeInDatabase('products', ['id_wishlist' => $wishlist->id_wishlist, 'title' => $text_unittest]);
    }

    public function testCreate_WrongOwner()
    {
        $this->generateUser();
        $text_unittest = Str::random(32);
        $response = $this->actingAs($this->user)->call('POST', '/wishlist/0/product', [
            'title' => $text_unittest
        ]);
        $this->assertEquals(404, $response->status());
    }
}