<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     * A route for logging the user in and providing an auth token
     * for further requests in case of success.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::whereEmail($request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->unauthorized(['message' => 'Invalid Credentials.']);
        }
        Auth::attempt($request->all());
        $token = $request->user()->createToken(config('app.name'));
        return $this->success(['token' => $token->plainTextToken]);
    }


    /**
     * @return JsonResponse
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     * A route for sign-out and auth token deletion.
     */
    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return $this->noContent();
    }
}
