<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SellerController extends Controller
{
    public function create(Request $request)
    {
        return response()->noContent(Response::HTTP_OK);
    }

    public function index()
    {
        return response()->noContent(Response::HTTP_OK);
    }

    public function show(Seller $seller)
    {
        return response()->noContent(Response::HTTP_OK);
    }

    public function update(Request $request, Seller $seller)
    {
        return response()->noContent(Response::HTTP_OK);
    }

    public function destroy(Seller $seller)
    {
        return response()->noContent(Response::HTTP_OK);
    }
}
