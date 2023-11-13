<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }



    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login'); // Redirection vers '/home'
    }


    private function validator(Request $request)
    {

        return request()->validate([
            'email' => [function ($attribute, $value, $fail) {
                if (empty($value)) {
                    $fail('Veuillez remplir ce champ');
                }
            },'string', 'email', 'max:255'],
            'password' => [ function ($attribute, $value, $fail) {
                if (empty($value)) {
                    $fail('Veuillez remplir ce champ');
                }
            }],
        ]);
    }


    public function login(Request $request)
    {
        $this->validator($request);

        $email = $request["email"];
        $password = $request["password"];
        $user = User::where('email', '=', $email)->first();
        if (!$user) {
            $message = "Vous n'avez pas encore de compte ! Veuillez contacter un administrateur.";
            $request->session()->flash('error_message', $message);
            return redirect()->back();
        } else {
            if (Hash::check($password, $user->password)) {
                if ($user->state ==  0) {
                    $message = "Ce compte a été bloqué. Veuillez contacter un administrateur.";
                    $request->session()->flash('error_message', $message);
                    return redirect()->back();
                } else {
                    Auth::attempt(['email' => $email,'password' => $password]);
                    $user = User::where('email', $email)->first();
                    return redirect()->route('home');
                }
            } else {
                $message = "Vos informations de connexion sont incorrectes.";
                $request->session()->flash('error_message', $message);
                return redirect()->back();
            }
        }


    }

}
