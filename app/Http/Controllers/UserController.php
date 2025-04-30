<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    protected array $rules = [
        'name' => 'required|string',
        'email' => 'required|string|email|unique:users',
        'password' => 'required|string|min:6',
    ];

    public function create(Request $request)
    {
        try {
            $validatedData = $request->validate($this->rules);
            $createdUser = User::create($validatedData);

            return response(['user' => $createdUser], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->noContent(Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return response()->noContent(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function index(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'nullable|email',
            ]);
    
            if (isset($validatedData['email'])) {
                $user = User::where('email', $validatedData['email'])->firstOrFail();
                return response(['user' => $user], Response::HTTP_OK);
            }
    
            $allUsers = User::all();
            return response(['users' => $allUsers], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->noContent(Response::HTTP_NOT_FOUND);
        } catch (ValidationException $e) {
            return response()->noContent(Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return response()->noContent(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(User $user)
    {
        return response(['user' => $user], Response::HTTP_OK);
    }

    public function update(Request $request, User $user)
    {
        try {
            $this->rules['password'] = 'nullable|string|min:6';

            $validatedData = $request->validate($this->rules);
            $user->update($validatedData);
            return response(['user' => $user], Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return response()->noContent(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(User $user)
    {
        $adm = User::where('role', 1)->first(); //temp

        $user->deleted_by = $adm->id;
        $user->save();
        $user->delete();

        return response()->noContent(Response::HTTP_OK);
    }
}
