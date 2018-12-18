<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\User;
use Auth;

class GoogleAuthController extends Controller
{
  /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('google')->user();

        if (!User::where('provider_id', $user->id)->first()) { // jika tidak ada user dengan id tersebut maka buat user nya
          $log = User::create([
            'name' => $user->name,
            'email' => $user->email,
            'provider_id' => $user->id,
            'password' => bcrypt(str_random()),
          ]);
        } else { // jika ada hanya login
          $log = User::where('provider_id', $user->id)->first();
        }

        Auth::login($log);

        return redirect()->route('home');
    }
}
