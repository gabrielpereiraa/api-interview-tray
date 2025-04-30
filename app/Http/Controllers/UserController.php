<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function create(Request $request)
    {
        return response()->noContent(Response::HTTP_OK);
    }

    public function index()
    {
        return response()->noContent(Response::HTTP_OK);
    }

    public function show(User $user)
    {
        return response()->noContent(Response::HTTP_OK);
    }

    public function update(Request $request, User $user)
    {
        return response()->noContent(Response::HTTP_OK);
    }

    public function destroy(User $user)
    {
        return response()->noContent(Response::HTTP_OK);
    }
}
