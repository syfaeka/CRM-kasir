<?php

namespace App\Controllers;

class Home extends BaseController
{
    /**
     * Landing page / homepage
     */
    public function landing()
    {
        // If logged in, redirect to appropriate page
        if (session()->get('isLoggedIn')) {
            $role = session()->get('user_role');

            if ($role === 'admin') {
                return redirect()->to('/dashboard');
            }

            return redirect()->to('/pos');
        }

        return view('landing');
    }

    public function index(): string
    {
        return view('welcome_message');
    }
}
