<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SaleController extends Controller
{
    public function create(Request $request)
    {
        return response()->noContent(Response::HTTP_OK);
    }

    public function index()
    {
        return response()->noContent(Response::HTTP_OK);
    }

    public function show(Sale $sale)
    {
        return response()->noContent(Response::HTTP_OK);
    }

    public function update(Request $request, Sale $sale)
    {
        return response()->noContent(Response::HTTP_OK);
    }

    public function destroy(Sale $sale)
    {
        return response()->noContent(Response::HTTP_OK);
    }
}