<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Classes\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Services\SMSService;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

class AuthController extends Controller
{   
    protected $SMSService;

    public function __construct(SMSService $SMSService)
    {
        $this->SMSService = $SMSService;
    }

    /*
    * Create login
    **/
    public function login(Request $request)
    {
        try {
            $isRegister = false;
            $isLogin    = false;
            $loginWith  = $this->validateUsername(request()->input('username'));

            $request->validate([
                'username' => 'required|' . ($loginWith == 'email' ? 'email' : 'digits:10'),
                'password' => 'required',
                'name'     => 'sometimes|string',
                'provider' => 'sometimes|string|in:google',
                'provider_id' => 'sometimes|string'
            ],[
                'username' => 'The username field must be a valid email address or mobile number'
            ]);

            if($loginWith == 'email'){
                if(isset($request->provider) AND ($request->provider == 'google') AND isset($request->provider_id) AND !empty($request->provider_id) ){
                    $user = User::where('email', $request->username)
                                ->where('provider_id', $request->provider_id)
                                ->first();
                    if(!$user){
                        $existingUser = User::where('email', $request->username)->first();
                        if($existingUser){
                            throw ValidationException::withMessages([
                                'email' => ['This email is already registered. Please login with email and password instead of Google.'],
                            ]);
                        }
                        $isRegister = true;
                    } else {
                        $isLogin = true;
                    }                                
                } else {
                    $user = User::where('email', $request->username)->first();    
                }
                
            } else {
                $user = User::where('mobile', $request->username)->first();    
            }

            if($isLogin){

            } else if ($isRegister){
                $user = User::create([
                    'email'       => $request->username,
                    'name'        => (isset($request->name) ? $request->name : NULL),
                    'provider'    => (isset($request->provider) ? $request->provider : NULL),
                    'provider_id' => (isset($request->provider_id) ? $request->provider_id : NULL),
                    'profile_image' => (isset($request->profile_image) ? $request->profile_image : NULL),
                ]);
                $user->assignRole('customer');
                
            } else if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $data = [
                'token' => $user->createToken('web')->plainTextToken,
                'user'  => $user->only('id','first_name', 'last_name', 'name', 'email', 'profile_image', 'mobile', 'dob', 'created_at')
            ];

            return ApiResponse::send(200, null, $data);

        } catch (Exception $e) {
            return ApiResponse::send(400, 'Something went wrong.');
        }
    }

    /*
    * OTP verify login
    **/
    public function OTPVerify(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|digits:10|exists:users,mobile',
                'otp'      => 'required|digits:6'
            ]);
            
            $user = User::where('mobile', $request->username)->where('otp',$request->otp)->first();    
            

            if (!$user) {
                throw ValidationException::withMessages([
                    'username' => ['The provided credentials are incorrect.'],
                ]);
            }

            $data = [
                'token' => $user->createToken('web')->plainTextToken,
                'user'  => $user->only('id','first_name', 'last_name', 'name', 'email', 'profile_image', 'mobile', 'created_at')
            ];

            return ApiResponse::send(200, null, $data);

        } catch (Exception $e) {
            return ApiResponse->send(400, 'Something went wrong.');
        }
    }

    /*
    * Verify user in database.
    **/
    public function verifyUser(Request $request)
    {
        try {
            $loginWith = $this->validateUsername(request()->input('username'));

            $request->validate([
                'username' => 'required|' .($loginWith == 'email' ? 'string|lowercase|email|max:255' : 'digits:10'),
            ],[
                'username' => 'The username field must be a valid email address or mobile number'
            ]);

            $user = User::where(($loginWith == 'email' ? 'email':'mobile'), request()->input('username'))->first();
            /* True = 'Registration required' Falase = 'Can login.'*/
            
            $data = (is_null($user) ? true: false);
            return ApiResponse::send(200, null, $data);
        } catch (Exception $e) {
            return ApiResponse->send(400, 'Something went wrong.');
        }
    }

    /*
    * Register user
    **/
    public function register(Request $request)
    {
        try {
            $loginWith = $this->validateUsername(request()->input('username'));

            $request->validate([
                'username'   => 'required|' . ($loginWith == 'email' ? 'string|lowercase|email|max:255|unique:users,email' : 'digits:10|unique:users,mobile'),
                'password'   => ['required', 'confirmed', Rules\Password::defaults()],
                'first_name' => 'sometimes|string',
                'last_name'  => 'sometimes|string',
            ],[
                'username' => 'The username field must be a valid email address or mobile number'
            ]);

            $name = trim(($request->input('first_name') ?? '') . ' ' . ($request->input('last_name') ?? ''));

            $user = User::create([
                'email'    => ($loginWith == 'email') ? $request->username : NULL,
                'mobile'   => ($loginWith == 'mobile') ? $request->username : NULL,
                'password' => Hash::make($request->password),
                'name'     => $name,
            ]);

            $user->assignRole('customer');

            /* Send welcome email to user. */
            $mailData = ['name' => (!empty($user->name))? $user->name : strtok($user->email, '@'),
                         'message' => 'Welcome to Kashmir Online.',
                        ];
            if(!empty($user->email)){
                Mail::to($user->email)->send(new WelcomeEmail($mailData));    
            }

            $data = [
                'token' => $user->createToken('web')->plainTextToken,
                'user'  => $user->only('id','first_name', 'last_name', 'name', 'email', 'profile_image', 'mobile', 'created_at')
            ];

            return ApiResponse::send(200, null, $data);
        } catch (Exception $e) {
            return ApiResponse::send(400, 'Something went wrong.');
        }
    }

    /*
    * Send otp to valid mobile number.
    **/
    public function sendOTP(Request $request)
    {
        try {
            $request->validate(['mobile' => 'required|digits:10']);
            $user = User::where('mobile', $request->mobile)->first();    

            if (!$user) {
                throw ValidationException::withMessages([
                    'mobile' => ['The provided credentials are incorrect.'],
                ]);
            }

            $OTP       = $this->generateOTP();
            $user->otp = $OTP;
            $user->save();

            /* Let setup text. */
            $msg = "Your One Time Password is ".$OTP.". Thanks SMSINDIAHUB";
            $msg = urlencode($msg);

            /* Send otp to client. */
            $response = $this->SMSService->sendText($request->mobile, $msg);

            return ApiResponse::send(200, null, $response);

        } catch (Exception $e) {
            return ApiResponse->send(400, 'Something went wrong.');
        }
    }

    /*
    * Get login user info
    **/
    public function getAuthUser(Request $request)
    {
        try {
            $user = $request->user()->only('id', 'first_name', 'last_name', 'name', 'email', 'profile_image', 'mobile', 'created_at');

            return ApiResponse::send(200, null, $user);
        } catch (Exception $e) {
            return ApiResponse->send(400, 'Something went wrong.');
        }
    }

    /*
    * Log out auth user
    **/
    public function logout(Request $request)
    {
        try {
            $response = $request->user()->currentAccessToken()->delete();

            if ($response){
                return ApiResponse::send(200, 'You have been successfully logout.', $response);
            } else {
                return ApiResponse::send(400, 'Something went wrong.', $response);
            }

        } catch (Exception $e) {
            return ApiResponse::send(400, 'Something went wrong.');
        }
    }

    /*
    * Log out auth user everywhere
    **/
    public function logoutEveryWhere(Request $request)
    {
        try {
            $response = $request->user()->tokens()->delete();

            if ($response){
                return ApiResponse::send(200, 'User have been successfully logout.', $response);
            } else {
                return ApiResponse::send(400, 'Something went wrong.', $response);
            }

        } catch (Exception $e) {
            return ApiResponse::send(400, 'Something went wrong.');
        }
    }

    /*
    * Update user profile data.
    **/
    public function updateProfile(Request $request)
    {
        try {
            $data = $request->validate([
                'id'            => 'required|integer|exists:users',
                'first_name'    => 'required|string',
                'last_name'     => 'required|string',
                'dob'           => 'sometimes|date_format:Y-m-d',
                'profile_image' => 'sometimes|nullable|string',
                'mobile'        => 'sometimes|nullable|digits:10',
                'email'         => 'sometimes|nullable|email'
            ]);

            $user = User::find($data['id']);
            $updateData = [
                'first_name'    => $data['first_name'] ?? null,
                'last_name'     => $data['last_name'] ?? null,
                'dob'           => $data['dob'] ?? null,
                'profile_image' => $data['profile_image'] ?? null,
                'mobile'        => $data['mobile'] ?? null,
            ];

            if (empty($user->email) && !empty($data['email'])) {
                $updateData['email'] = $data['email'];
            }

            $user->update($updateData);
            
            $userData = $user->only('id', 'first_name', 'last_name', 'name', 'email', 'profile_image', 'mobile', 'dob', 'created_at');
            
            return ApiResponse::send(200, 'User has been updated successfully.', $userData);
        } catch (Exception $e) {
            return ApiResponse::send(400, 'Something went wrong.');
        }
    }

    /*
    * Validate username field 
    */
    public function validateUsername($username = null)
    {
        if(!is_null($username)){
            if(is_numeric($username)){
                $loginWith = 'mobile';
            } elseif (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                $loginWith = 'email';
            } else {
                $loginWith = 'email';
            }
        }
        return $loginWith;
    }

    /*
    * Generate otp
    **/
    private function generateOTP()
    {
        $pin = mt_rand(111111, 999999);

        if ($this->pinCodeExists($pin) || strlen((string) $pin) !== 6) {
            return $this->generateOTP();
        }

        return $pin;
    }

    private function pinCodeExists($pin)
    {
        return User::where('otp', $pin)->exists();
    }
}
