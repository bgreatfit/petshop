<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //
    public function store(CreateUserRequest $request):JsonResponse{
        $user = User::create([
            'uuid' => (string) Str::uuid(),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'address' => $request->input('address'),
            'phone_number' => $request->input('phone_number'),
            'avatar' => $request->input('avatar'),
            'is_marketing' => $request->has('is_marketing')? 1: 0
        ]);
        return response()->json(
            [ 'status' => 1,
                'data' => $user],
            201);
    }
}
