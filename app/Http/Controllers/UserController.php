<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;

class UserController extends Controller
{
    public function me(): ?Authenticatable {
        return auth()->user();
    }
}
