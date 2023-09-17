<?php

namespace App\Http\Controllers;

use App\Contracts\TokenServiceInterface;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AuthController extends Controller
{

    private $tokenService;

    public function __construct(TokenServiceInterface $tokenService)
    {
        $this->tokenService = $tokenService;
    }
    /**
     * @OA\Post(
     *     path="/api/v1/user/create",
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
     */


    public function register(RegisterUserRequest $request): JsonResponse
    {
        $validate = $request->validated();
        $user = User::create([
            'uuid' =>  Str::uuid(),
            'first_name' => $validate['first_name'],
            'last_name' => $validate['last_name'],
            'email' => $validate['email'],
            'password' => Hash::make($validate['password']),
            'address' => $validate['address'],
            'phone_number' =>  $validate['phone_number'],
            'avatar' => $request->get('firstname'),
            'is_marketing' => $request->has('is_marketing') ? 1 : 0,
        ]);
        $user['uuid'] = $user->uuid;

        return response()->success($user, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/login",
     *     tags={"User"},
     *     summary="Login a User account",
     *     description="Login API endpoint",
     *     operationId="user-login",
     *     @OA\RequestBody(
     *         required=true,
     *         description="User data",
     *        @OA\JsonContent(),
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                type="object",
     *                required={"email", "password"},
     *                 @OA\Property(property="email", type="string", description="User email"),
     *                 @OA\Property(property="password", type="string", description="User password")
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
     */
    public function login(LoginRequest $request): JsonResponse {

        $data = $request->validated();
        if (!auth()->attempt($data)) {
            return response()->badRequest('Failed to authenticate user');
        }
        $user = auth()->user();
        $token = $this->tokenService->issueToken($user, 'Access Token');

        return response()->success(['token'=>$token], 200);
    }
}
