<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function register(Request $request): array {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'repeat_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return ['message' => 'Registration failed.', 'error' => $validator->errors()];
        }

        $validated = $validator->validated();

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        return [
            'message' => 'Registration successful.',
            'token' => $user->createToken(config('app.name'))->plainTextToken
        ];
    }
}
