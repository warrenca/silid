<?php

namespace App\Http\Controllers;

class AuthController extends Controller
{
    public function googleLogin()
    {
      return \Socialite::driver('google')->stateless(false)->redirect();
    }

    public function googleCallback()
    {
      try {
        $user = \Socialite::driver('google')->stateless(false)->user();

        $regex = '/@((([^.]+)\.)+)([a-zA-Z]{3,}|[a-zA-Z.]{5,})/';
        preg_match($regex, $user->email, $matches);
        $hostname = substr($matches[0], 1);

        if (! in_array($hostname, explode(",",env('SILID_ALLOWED_DOMAINS')))) {
          $_SESSION['errors'] = ['Your email is not part of the allowed domains. Please sign-in with an email from the allowed domains.'];
          return redirect('login');
        }

        // OAuth Two Providers
        $token = $user->token;
        $expiresIn = $user->expiresIn;

        $_SESSION['token'] = $token;
        $_SESSION['expiresIn'] = $expiresIn;
        return redirect('booking');
      } catch (\Exception $e) {
        return redirect('login');
      }
    }

    public function logout() {
      unset($_SESSION['token']);
      unset($_SESSION['expiresIn']);
      return redirect('login');
    }

    public function login() {
      $errors = [];
      if (isset($_SESSION['errors'])) {
        $errors = $_SESSION['errors'];
        unset($_SESSION['errors']);
      }

      return app()->make('view')->make('login', ['allowed_domains'=>env('SILID_ALLOWED_DOMAINS'), 'errors' => $errors]);
    }
}
