<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/user/create",
     *     tags={"User"},
     *     summary="Create a User account",
     *     description="User API endpoint",
     *     operationId="user-create",
     *     @OA\RequestBody(
     *         required=true,
     *         description="User data",
     *        @OA\JsonContent(),
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                type="object",
     *                required={"first_name", "last_name", "email", "password", "password_confirmation", "address", "phone_number"},
     *                 @OA\Property(property="first_name", type="string", description="User firstname"),
     *                 @OA\Property(property="last_name", type="string", description="User lastname"),
     *                 @OA\Property(property="email", type="string", description="User email"),
     *                 @OA\Property(property="password", type="string", description="User password"),
     *                 @OA\Property(property="password_confirmation", type="string", description="User password"),
     *                 @OA\Property(property="avatar", type="string", description="Avatar image UUID"),
     *                 @OA\Property(property="address", type="string", description="User main address"),
     *                 @OA\Property(property="phone_number", type="string", description="User main phone number"),
     *                 @OA\Property(property="is_marketing", type="string", description="User marketing preferences")
     *             )
     *         )
     *     ),
     *
     *    @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Page not found"),
     *     @OA\Response(response=422, description="Unprocessable Entity"),
     *     @OA\Response(response=500, description="Internal server error"),
     *     security={{"bearerAuth": {}}}
     * )
     * @param \App\Http\Requests\RegisterUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */


    public function register(RegisterUserRequest $request): JsonResponse
    {
        $user = User::create([
            'uuid' => (string) Str::uuid(),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'address' => $request->input('address'),
            'phone_number' => $request->input('phone_number'),
            'avatar' => $request->input('avatar'),
            'is_marketing' => $request->has('is_marketing') ? 1: 0,
        ]);

        return response()->created($user);
    }
}
