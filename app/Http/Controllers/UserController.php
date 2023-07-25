<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function UserRegistration(Request $request)
    {

        try {

            User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'user_name' => $request->input('user_name'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => Hash::make($request->input('password')),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User Registration Successfully',
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => 'User Registration Failed ! From Back-End',
            ], 400);
        }
    }

    public function UserLogin(Request $request)
    {

        if (User::where('email', '=', $request->email)->exists()) {

            $user = User::where('email', '=', $request->email)->first();

            if (Hash::check($request->password, $user->password)) {

                $token = JWTToken::CreateToken($request->input('email'));

                return response()->json([
                    'status' => 'success',
                    'message' => 'User Login Successful',
                    'token' => $token,
                ], 200)->cookie('token', $token, 60 * 60 * 24);
            }

            return response()->json([
                'success' => true,
                'message' => 'Wrong User Credential!',
                'data' => null,
            ], 400);
        }

        return response()->json([
            'success' => false,
            'message' => 'No User With That Email Address!',
            'data' => null,
        ], 404);

    }

    public function SendOTPCode(Request $request)
    {

        $email = $request->input('email');
        $otp = rand(1000, 9999);
        $count = User::where('email', '=', $email)->count();

        if ($count == 1) {

            User::where('email', '=', $email)->update(['otp' => $otp]);
            Mail::to($email)->send(new OTPMail($otp));

            return response()->json([
                'status' => 'success',
                'message' => '4 Digit OTP Code has been send to your email !',
            ], 200);

        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized',
            ], 401);
        }
    }

    public function VerifyOTP(Request $request)
    {
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email', '=', $email)
            ->where('otp', '=', $otp)->count();

        if ($count == 1) {

            User::where('email', '=', $email)->update(['otp' => '0']);

            $token = JWTToken::CreateTokenForSetPassword($request->input('email'));

            return response()->json([
                'status' => 'success',
                'message' => 'OTP Verification Successful',
            ], 200)->cookie('token', $token, 60 * 60 * 24);

        } else {

            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized',
            ], 401);
        }
    }

    public function ResetPassword(Request $request)
    {
        try {

            $email = $request->header('email');
            $password = $request->input('password');
            User::where('email', '=', $email)->update(['password' => $password]);
           
            return response()->json([
                'status' => 'success',
                'message' => 'Request for Reseting Password Successful',
            ], 200);

        } catch (Exception $exception) {

            return response()->json([
                'status' => 'fail',
                'message' => 'Something Went Wrong',
            ], 500);
        }
    }
}
