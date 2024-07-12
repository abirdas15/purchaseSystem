<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Displays the registration form for new users.
     *
     * @param Request $request
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function registerForm(Request $request): Factory|View|\Illuminate\Foundation\Application|Application
    {
        // Return the view 'auth.register' to display the registration form
        return view('auth.register');
    }


    /**
     * Registers a new user based on the provided registration data.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function register(Request $request): RedirectResponse
    {
        // Validate the incoming request data
        $request->validate([
            'username' => 'required|string|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|unique:users',
        ]);

        // Create a new User instance and populate its fields
        $user = new User();
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->password = bcrypt($request->input('password'));

        // Save the user to the database
        if (!$user->save()) {
            // If user saving fails, redirect back with an error message
            return redirect()->back()->with('error', 'User cannot be saved.');
        }

        // Send verification email to the user
        Mail::to($user->email)->send(new VerifyEmail($user));

        // Redirect to the email verification page with user ID
        return redirect()->route('auth.email.verify', ['id' => $user->id])
            ->with('message', 'Sent mail on your email. Please check inbox.');
    }


    /**
     * Displays the email verification form for verifying user's email.
     *
     * @param Request $request
     * @param string $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application|RedirectResponse
     */
    public function emailVerifyForm(Request $request, string $id): Factory|View|\Illuminate\Foundation\Application|Application|RedirectResponse
    {
        // Find the user based on the provided $id
        $user = User::find($id);

        // If user is not found, redirect back
        if (!$user instanceof User) {
            return redirect()->back();
        }

        // If user's email is already verified, redirect to login page
        if ($user->email_verified_at !== null) {
            return redirect()->route('auth.login');
        }

        // Return the view 'auth.email-verify', passing the $id data to it
        return view('auth.email-verify', compact('id'));
    }

    /**
     * Resends the email verification link to the user.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function emailResendVerification(Request $request): RedirectResponse
    {
        // Validate the incoming request data
        $request->validate([
            'id' => 'required|string|exists:users',
        ]);

        // Find the user based on the provided 'id'
        $user = User::find($request->input('id'));

        // If user is not found, redirect back
        if (!$user instanceof User) {
            return redirect()->back();
        }

        // Send verification email to the user
        Mail::to($user->email)->send(new VerifyEmail($user));

        // Redirect to the email verification page with user ID
        return redirect()->route('auth.email.verify', ['id' => $user->id])
            ->with('message', 'Successfully resent verification email.');
    }

    /**
     * Handles successful email verification for a user.
     *
     * @param Request $request
     * @param int $id
     * @return Factory|View|Application|RedirectResponse
     */
    public function emailVerifySuccessForm(Request $request, int $id): Factory|View|Application|RedirectResponse
    {
        // Find the user based on the provided $id
        $user = User::find($id);

        // If user is not found, redirect back
        if (!$user instanceof User) {
            return redirect()->back();
        }

        // If user's email is already verified, redirect to login page
        if ($user->email_verified_at !== null) {
            return redirect()->route('auth.login');
        }

        // Set the email_verified_at timestamp to current time
        $user->email_verified_at = now();

        // Save the user to mark email as verified
        if (!$user->save()) {
            // If user saving fails, redirect back with an error message
            return redirect()->back()->with('error', 'User cannot be verified.');
        }

        // Return the view 'auth.email-verify-success' to display success message
        return view('auth.email-verify-success');
    }

    /**
     * Displays the login form for users to authenticate.
     *
     * @param Request $request
     * @return Factory|View|Application|RedirectResponse
     */
    public function loginForm(Request $request): Factory|View|Application|RedirectResponse
    {
        // Return the view 'auth.login' to display the login form
        return view('auth.login');
    }


    /**
     * Handles user login authentication.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        // Validate the incoming request data
        $request->validate([
            'email-username' => 'required|string',
            'password' => 'required|string',
        ], [
            'email-username.required' => 'The email or username field is required.',
        ]);

        // Attempt to find the user by email or username
        $user = User::where('email', $request->input('email-username'))
            ->orWhere('username', $request->input('email-username'))
            ->first();

        // If user is not found, redirect back with error message
        if (!$user instanceof User) {
            return redirect()->back()->withErrors(['email-username' => 'Invalid email or username.']);
        }

        if ($user->email_verified_at == null) {
            return redirect()->back()->withErrors(['email-username' => 'Email is not verified.']);
        }

        // Verify the password using Hash::check
        if (Hash::check($request->input('password'), $user->password)) {
            // Generate and save OTP (one-time password) for user
            $user->otp = rand(1000, 9999);
            $user->save();

            // Redirect to OTP verification page with user ID
            return redirect()->route('auth.verify.otp', ['id' => $user->id]);
        }

        // If password is invalid, redirect back with error message
        return back()->withErrors(['password' => 'Invalid password.'])->onlyInput('password');
    }

    /**
     * Displays the OTP verification form for a user.
     *
     * @param Request $request
     * @param int $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function verifyOtpForm(Request $request, int $id): Factory|View|\Illuminate\Foundation\Application|Application
    {
        // Return the view 'auth.verify-otp', passing the $id data to it
        return view('auth.verify-otp', compact('id'));
    }

    /**
     * Handles OTP verification and logs in the user upon successful verification.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function verifyOtp(Request $request, $id): RedirectResponse
    {
        // Validate the incoming request data
        $request->validate([
            'otp' => 'required|string|exists:users',
        ]);

        // Find the user by ID and verify OTP
        $user = User::where('id', $id)
            ->where('otp', $request->input('otp'))
            ->first();

        // If user is not found or OTP is invalid, redirect back with error message
        if (!$user instanceof User) {
            return redirect()->back()->withErrors(['otp' => 'The selected OTP is invalid.']);
        }

        // Clear OTP field (set to null) and save the user
        $user->otp = null;
        $user->save();

        // Log in the user
        Auth::login($user);

        // Redirect to the home page after successful login
        return redirect()->route('home');
    }

    /**
     * Logs out the authenticated user.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        // Logout the currently authenticated user
        Auth::logout();

        // Redirect to the login page after logout
        return redirect()->route('auth.login');
    }

}
