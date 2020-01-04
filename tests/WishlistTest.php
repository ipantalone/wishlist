<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class WishlistTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreate_MissingData()
    {
        $response = $this->call('POST', '/wishlist');
        $this->assertEquals(422, $response->status());
    }

    public function testCreate_Success()
    {
        $text_unittest = Str::random(32);
        $response = $this->call('POST', '/wishlist', [
            'title' => $text_unittest
        ]);
        $this->assertEquals(201, $response->status());
        $this->seeInDatabase('wishlists', ['title' => $text_unittest]);
    }

    public function testList()
    {
        $response = $this->call('GET', '/wishlist');
        $this->assertEquals(200, $response->status());

        $this->seeJsonStructure([
            'total',
            'data' => [
                [
                    'id_wishlist', 'title', 'products'
                ]
            ]
        ]);
    }

    public function testShow_Exists()
    {
        $data = DB::table('wishlists')->first(); // Prendo la prima wishlist disponibile
        $response = $this->call('GET', '/wishlist/' . $data->id_wishlist);
        $this->assertEquals(200, $response->status());

        $this->seeJsonStructure([
            'id_wishlist',
            'title',
            'products' => []
        ]);
    }

    public function testShow_NotExists()
    {
        $response = $this->call('GET', '/wishlist/0');
        $this->assertEquals(404, $response->status());
    }
}