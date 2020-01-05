<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Interfaces\RESTOperation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class WishlistController extends Controller implements RESTOperation
{
    /**
     * Crea la wishlist nel database con il titolo ricevuto dalla request
     *
     * @param Request $request
     */
    public function create(Request $request)
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
            Auth::user()->wishlists()->create(['title' => $request->title]);
            return response(null, Response::HTTP_CREATED);
        }
    }

    /**
     * Visualizza l'elenco di wishlist dell'utente loggato con il conteggio dei prodotti inseriti
     */
    public function list()
    {
        $wishlists = DB::table('wishlists as w')->select('w.id_wishlist', 'w.title', DB::raw('count(p.id_product) as products'))
            ->leftJoin('products as p', 'p.id_wishlist', '=', 'w.id_wishlist')
            ->groupBy('w.id_wishlist', 'w.title')
            ->where('id_user', Auth::user()->id_user)
            ->get();

        return response()->json([
            'total' => $wishlists->count(),
            'data' => $wishlists
        ], Response::HTTP_OK);
    }


    public function show($id_wishlist)
    {
        $wishlist = DB::table('wishlists')->select('id_wishlist', 'title')
            ->where('id_user', Auth::user()->id_user)
            ->where('id_wishlist', $id_wishlist)
            ->first();

        if(!is_null($wishlist)) // La wishlist esiste ed è dell'utente loggato
        {
            // Estraggo i prodotti per inserirli nella response così da dare una risposta più completa sulla wishlist richiesta
            $wishlist->products = DB::table('products')->select('id_product', 'title')->where('id_wishlist', $wishlist->id_wishlist)->get();

            return response()->json($wishlist, Response::HTTP_OK);
        }
        else // Non mi interessa se la wishlist esiste ma non è dell'utente corretto, quindi un problema di autorizzazioni, per l'utente è 404
        {
            return response(null, Response::HTTP_NOT_FOUND);
        }
    }
    
    public function edit($id_wishlist, Request $request)
    {

    }

    public function delete($id_wishlist)
    {

    }
}