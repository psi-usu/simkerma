<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use parinpan\fanjwt\libs\JWTAuth;

class IsAuth {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (env('APP_ENV') == 'LOCAL')
        {
            $user = new User();
            $user->username = env('LOGIN_USERNAME');
            Auth::login($user);

            return $next($request);
        }
        $login = JWTAuth::communicate('https://akun.usu.ac.id/auth/listen', @$_COOKIE['ssotok'], function ($credential)
        {
            $loggedIn = $credential->logged_in;
            if ($loggedIn)
            {
                //kalau udah login
            } else
            {
                setcookie('ssotok', null, -1, '/');

                return false;
            }
        }
        );
        if (! $login->logged_in && ! is_null($login->payload->identity))
        {
            $url = JWTAuth::makeLink([
                'baseUrl'  => 'https://akun.usu.ac.id/auth/login',
                'callback' => url('/') . '/callback.php',
                'redir'    => url('/'),
            ]);

            return redirect()->away($url);
        } else
        {
            $user = new User();
            $user->username = $login->payload->identity;
            Auth::login($user);

            return $next($request);
        }
    }
}
