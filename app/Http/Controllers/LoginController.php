<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Redirect;

class LoginController extends Controller
{
    public function loginUser(Request $request)
    {
        $validateUser = $request->validate(
        [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $get_http_host = request()->getHost();
        $tenant = strstr($get_http_host, '.', true);

        $user = User::on('tenant')->where('email', $validateUser['email'])->first();

        if(!is_null($user) && Hash::check($validateUser['password'], $user->password)){
            // if($tenant == 'admin' && !($user->email == 'admin@mail.com' || $user->email == 'Amit.Seth@buroserv.com.au'  || $user->current_team_id == 1)){
            if($tenant == 'admin' && !($user->current_team_id == 1)){
                return Redirect::back()->with('error-domain','Invalid');
            }
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->to('http://'.$tenant.'.localhost:8000/dashboard');
        } else {
            return Redirect::back()->with('error','Invalid');
        }
    }
}
