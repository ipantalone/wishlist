<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class WishlistTest extends TestCase
{
    private $user;

    public function generateUser()
    {
        $this->user = User::find(1);
    }

    public function testCreate_MissingData()
    {
        $this->generateUser();
        $response = $this->actingAs($this->user)->call('POST', '/wishlist');
        $this->assertEquals(422, $response->status());
    }

    public function testCreate_Success()
    {
        $this->generateUser();
        $text_unittest = Str::random(32);
        $response = $this->actingAs($this->user)->call('POST', '/wishlist', [
            'title' => $text_unittest
        ]);
        $this->assertEquals(201, $response->status());
        $this->seeInDatabase('wishlists', ['title' => $text_unittest]);
    }

    public function testList()
    {
        $this->generateUser();
        $response = $this->actingAs($this->user)->call('GET', '/wishlist');
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
        $this->generateUser();
        $data = DB::table('wishlists')->first(); // Prendo la prima wishlist disponibile
        $response = $this->actingAs($this->user)->call('GET', '/wishlist/' . $data->id_wishlist);
        $this->assertEquals(200, $response->status());

        $this->seeJsonStructure([
            'id_wishlist',
            'title',
            'products' => []
        ]);
    }

    public function testShow_NotExists()
    {
        $this->generateUser();
        $response = $this->actingAs($this->user)->call('GET', '/wishlist/0');
        $this->assertEquals(404, $response->status());
    }

    public function testEdit_NotFound()
    {
        $this->generateUser();
        $response = $this->actingAs($this->user)->call('PUT', '/wishlist/0');
        $this->assertEquals(404, $response->status());
    }

    public function testEdit_WrongOwner()
    {
        $this->generateUser();
        $wishlist = DB::table('wishlists')->where('id_user', '!=', 1)->first();
        $response = $this->actingAs($this->user)->call('PUT', '/wishlist/' . $wishlist->id_wishlist);
        $this->assertEquals(404, $response->status());
    }

    public function testEdit_MissingData()
    {
        $this->generateUser();
        $wishlist = DB::table('wishlists')->where('id_user', 1)->first();
        $response = $this->actingAs($this->user)->call('PUT', '/wishlist/' . $wishlist->id_wishlist);
        $this->assertEquals(422, $response->status());
    }

    public function testEdit_Success()
    {
        $this->generateUser();
        $text_unittest = Str::random(32);
        $wishlist = DB::table('wishlists')->where('id_user', 1)->first();
        $response = $this->actingAs($this->user)->call('PUT', '/wishlist/' . $wishlist->id_wishlist, [
            'title' => $text_unittest
        ]);
        $this->assertEquals(204, $response->status());
        $this->seeInDatabase('wishlists', ['title' => $text_unittest]);
    }

    public function testDelete_Success()
    {
        $this->generateUser();
        $wishlist = DB::table('wishlists')->where('id_user', 1)->latest()->first();
        $response = $this->actingAs($this->user)->call('DELETE', '/wishlist/' . $wishlist->id_wishlist);
        $this->assertEquals(204, $response->status());
    }

    public function testDelete_NotFound()
    {
        $this->generateUser();
        $response = $this->actingAs($this->user)->call('DELETE', '/wishlist/0');
        $this->assertEquals(404, $response->status());
    }
}