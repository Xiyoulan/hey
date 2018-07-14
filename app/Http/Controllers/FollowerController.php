<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Models\User;
use Illuminate\Http\Request;

class FollowerController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function store(User $user) {
        if (Auth::user()->id === $user->id) {
            return redirect('/');
        }
        if (!Auth::user()->isFollowing($user->id)) {
            Auth::user()->follow($user->id);
        }
        return redirect()->route('users.show', $user->id);
    }

    public function destroy(User $user) {
        if (Auth::user()->id === $user->id) {
            return redirect('/');
        }
        if (Auth::user()->isFollowing($user->id)) {
            Auth::user()->unFollow($user->id);
        }
        return redirect()->route('users.show', $user->id);
    }

}
