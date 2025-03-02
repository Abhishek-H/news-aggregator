<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //Get  registration form
    public function getRegister()
    {
        // Check if the user is logged in by verifying the session token
        if (Session::has('user') && !empty(Session::get('user.token'))) {
            return redirect('/dashboard');
        }
        return view('register');
    }

    //Get login form
    public function getLogin()
    {
        // Check if the user is logged in by verifying the session token
        if (Session::has('user') && !empty(Session::get('user.token'))) {
            return redirect('/dashboard');
        }
        return view('login');
    }

    // User Registration
    public function register(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // API URL to register user
            $url = url('/api/register');
            // Send request to API
            $response = Http::post($url, [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ]);

            // Decode response
            $responseData = json_decode($response->getBody(), true);

            // Check for successful response
            if ($response->successful()) {
                return redirect('/login')->with('success', 'User registered successfully. Please login.');
            }

            // Handle API errors
            return redirect()->back()->withErrors(['error' => $responseData['message'] ?? 'Failed to register.'])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()])->withInput();
        }
    }

    // User Login
    public function login(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // API URL for login
            $url = url('/api/login');

            // Send request to API
            $response = Http::post($url, [
                'email' => $request->email,
                'password' => $request->password
            ]);

            // Decode response
            $responseData = json_decode($response->getBody(), true);

            // Check if response is successful
            if ($response->successful() && isset($responseData['token'])) {
                // Store user details in session (Modify based on your needs)
                Session::put('user', [
                    'email' => $request->email,
                    'token' => $responseData['token']
                ]);

                return redirect('/dashboard')->with('success', 'User login successful');
            }

            // Handle API errors
            return redirect()->back()->withErrors([
                'error' => $responseData['message'] ?? 'Invalid login credentials.'
            ])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Something went wrong: ' . $e->getMessage()
            ])->withInput();
        }
    }


    public function logout()
    {
        // Check if the user is logged in by verifying the session token
        if (!Session::has('user') || empty(Session::get('user.token'))) {
            return redirect('/login');
        }

        // Optionally, invalidate the token via the API logout endpoint
        $url = url('/api/logout');
        $token = Session::get('user.token');

        try {
            Http::withToken($token)->post($url);
        } catch (\Exception $e) {
            // Log error if needed but continue logout process
        }

        // Clear session data
        Session::forget('user');
        Session::flush();

        return redirect('/login')->with('message', 'Logged out successfully.');
    }


    public function getDashboard()
    {
        // Check if the user is logged in by verifying the session token
        if (!Session::has('user') || empty(Session::get('user.token'))) {
            return redirect('/login')->with('message', 'You must be logged in.');
        }

        return view('dashboard');
    }


    public function registerUser(Request $request)
    {
        try {
            // Validate request data
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'message' => 'User created successfully.',
                'user' => $user
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function loginUser(Request $request)
    {
        try {
            // Validate request data
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Attempt to login user
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'message' => 'Invalid credentials'
                ], 401);
            }

            // Get the authenticated user
            $user = Auth::user();

            // Create token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'User logged in successfully.',
                'user' => $user,
                'token' => $token
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logoutUser(Request $request)
    {
        try {
            // Revoke the token
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'User logged out successfully.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
