<?php

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 10)->create()->each(function ($user) {
            factory(Wishlist::class, random_int(1, 5))->make()->each(function ($wishlist) use(&$user) {
                $user->wishlists()->save($wishlist);

                factory(Product::class, random_int(1, 5))->make()->each(function ($product) use(&$wishlist) {
                    $wishlist->products()->save($product);
                });
            });
        });
    }
}
