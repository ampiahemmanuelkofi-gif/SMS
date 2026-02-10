<?php
/**
 * Authentication Controller
 */

class AuthController extends Controller {
    
    /**
     * Show login page
     */
    public function login() {
        // If already logged in, redirect to dashboard
        if (Auth::isLoggedIn()) {
            $this->redirect('dashboard');
        }
        
        // Handle login form submission
        if ($this->isPost()) {
            $username = $this->post('username');
            $password = $this->post('password');
            $csrfToken = $this->post('csrf_token');
            
            // Validate CSRF token
            if (!Auth::validateCsrfToken($csrfToken)) {
                $this->setFlash('error', 'Invalid request. Please try again.');
                $this->redirect('auth/login');
            }
            
            // Validate input
            $validator = new Validator();
            $validator->required('username', $username, 'Username')
                     ->required('password', $password, 'Password');
            
            if (!$validator->isValid()) {
                $error = $validator->getFirstError();
            } else {
                // Attempt login
                if (Auth::login($username, $password)) {
                    $this->redirect('dashboard');
                } else {
                    $error = 'Invalid username or password';
                }
            }
            
            $this->view('auth/login', ['error' => $error], false);
        } else {
            // Show login form
            $this->view('auth/login', [], false);
        }
    }
    
    /**
     * Logout
     */
    public function logout() {
        Auth::logout();
        $this->setFlash('success', 'You have been logged out successfully.');
        $this->redirect('auth/login');
    }
    
    /**
     * Show forgot password page
     */
    public function forgotPassword() {
        if ($this->isPost()) {
            $email = $this->post('email');
            
            // Validate email
            $validator = new Validator();
            $validator->required('email', $email, 'Email')
                     ->email('email', $email);
            
            if (!$validator->isValid()) {
                $error = $validator->getFirstError();
            } else {
                // TODO: Implement password reset email
                $this->setFlash('success', 'Password reset instructions have been sent to your email.');
                $this->redirect('auth/login');
            }
            
            $this->view('auth/forgot-password', ['error' => $error], false);
        } else {
            $this->view('auth/forgot-password', [], false);
        }
    }
}
