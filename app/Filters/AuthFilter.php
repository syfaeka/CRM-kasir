<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Check if user is logged in before accessing protected routes
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            // Store intended URL for redirect after login
            session()->set('redirect_url', current_url());

            return redirect()->to('/login')
                ->with('error', 'Please login to access this page.');
        }

        // Optional: Check for specific roles
        if ($arguments !== null) {
            $userRole = session()->get('user_role');

            if (!in_array($userRole, $arguments)) {
                return redirect()->to('/dashboard')
                    ->with('error', 'You do not have permission to access this page.');
            }
        }

        return null;
    }

    /**
     * After filter (not used)
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
