<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Login with email and password to get the authentication token",
 *     name="Token based Based",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 * )
 */

class UserController extends Controller
{

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/v1/user",
     *     summary="View a User account",
     *     tags={"User"},
     *     operationId="user-read",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function show(Request $request) {
        //
        $user = $request->user();

        return response()->success($user, 200);


    }


}
