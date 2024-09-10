<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware("guest")->except("dashboard");
    }

    public function dashboard()
    {
        return view("dashboard");
    }

    /**
     * Display a listing of the resource.
     */
    public function register()
    {
        return view("auth.register");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function login()
    {
        return view("auth.login");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "username" => "required",
            "password" => "required",
        ]);

        $user = new User();
        $user->username = $request->username;
        $user->password = $request->password;
        $user->save();

        return redirect("login")->with("success", "");
    }

    /**
     * Display the specified resource.
     */
    public function credentials(Request $request)
    {
        try {
            $credentials = $request->only('username', 'password');

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->route('dashboard');
            }

            return redirect()->back()->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect('login')->with('success', '');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, auth $auth)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(auth $auth)
    {
        //
    }
}
