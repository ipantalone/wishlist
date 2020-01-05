<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\RESTOperation;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function create($id_wishlist, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255'
        ]);

        if ($validator->fails()) 
        {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        else
        {
            $wishlist = DB::table('wishlists')->where('id_user', Auth::user()->id_user)->where('id_wishlist', $id_wishlist)->exists();
            if($wishlist)
            {
                $product = new Product(['title' => $request->title]);
                $product->id_wishlist = $id_wishlist;
                $product->save();

                return response(null, Response::HTTP_CREATED);
            }
            else
            {
                return response(null, Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function list($id_wishlist)
    {
        $wishlist = DB::table('wishlists')->where('id_user', Auth::user()->id_user)->where('id_wishlist', $id_wishlist)->first();

        if(!is_null($wishlist))
        {
            $products = DB::table('products')->select('id_product', 'title')
                ->where('id_wishlist', $wishlist->id_wishlist)
                ->get();

            return response()->json([
                'id_wishlist' => $wishlist->id_wishlist,
                'wishlist' => $wishlist->title,
                'total' => $products->count(),
                'data' => $products
            ], Response::HTTP_OK);
        }
        else
        {
            return response(null, Response::HTTP_NOT_FOUND);
        }
    }

    public function show($id_wishlist, $id_product)
    {
        $product = DB::table('products as p')
            ->select('p.*')
            ->join('wishlists as w', 'p.id_wishlist', 'w.id_wishlist')
            ->where('w.id_user', Auth::user()->id_user)
            ->where('p.id_wishlist', $id_wishlist)
            ->where('p.id_product', $id_product)
            ->first();

        if(!is_null($product))
        {
            return response()->json($product, Response::HTTP_OK);
        }
        else
        {
            return response(null, Response::HTTP_NOT_FOUND);
        }
    }
    
    public function edit($id_wishlist, $id_product, Request $request)
    {
        
    }

    public function delete($id_wishlist, $id_product)
    {
        
    }
}