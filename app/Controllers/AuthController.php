<?php

namespace App\Controllers;

use App\Helpers\View;
use App\Helpers\Redirect;
use App\Helpers\Session;
use App\Services\AuthService;

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function showLogin(): void
    {
        if (Session::has('user_id')) Redirect::to('/');
        View::render('auth.login', ['title' => 'Login - CRM']);
    }

    public function login(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        if ($this->authService->attemptLogin($email, $password)) Redirect::to('/');
        else Redirect::back();
    }

    public function logout(): void
    {
        $this->authService->logout();
        Redirect::to('/login');
    }

    public function showForgotPassword(): void
    {
        View::render('auth.forgot-password', ['title' => 'Forgot Password']);
    }

    public function sendResetLink(): void
    {
        $email = $_POST['email'] ?? '';
        $token = $this->authService->generatePasswordResetToken($email);

        if ($token) {
            // In a real app, send an email here. For now we just flash the reset link.
            // (As per Phase 1 limitations, SMTP comes later)
            $resetLink = "/reset-password?email=" . urlencode($email) . "&token=" . $token;
            Session::flash('success', "Simulated Email: Click <a href='$resetLink' class='underline'>here</a> to reset your password.");
        } else {
            // Always show success to prevent email enumeration
            Session::flash('success', "If your email is registered, you will receive a password reset link.");
        }

        Redirect::back();
    }

    public function showResetPassword(): void
    {
        $email = $_GET['email'] ?? '';
        $token = $_GET['token'] ?? '';

        if (!$this->authService->validateResetToken($email, $token)) {
            Session::flash('error', 'Invalid or expired password reset link.');
            Redirect::to('/forgot-password');
        }

        View::render('auth.reset-password', [
            'title' => 'Reset Password',
            'email' => $email,
            'token' => $token
        ]);
    }

    public function resetPassword(): void
    {
        $email = $_POST['email'] ?? '';
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';

        if ($password !== $passwordConfirmation) {
            Session::flash('error', 'Passwords do not match.');
            Redirect::back();
        }

        if ($this->authService->resetPassword($email, $token, $password)) {
            Session::flash('success', 'Password reset successfully. Please login.');
            Redirect::to('/login');
        } else {
            Session::flash('error', 'Failed to reset password. Link may be expired.');
            Redirect::back();
        }
    }
}
