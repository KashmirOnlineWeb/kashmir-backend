<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Classes\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{   
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
                'provider_id' => 'sometimes|integer'
            ],[
                'username' => 'The username field must be a valid email address or mobile number'
            ]);

            if($loginWith == 'email'){
                if(isset($request->provider) AND ($request->provider == 'google') AND isset($request->provider_id) AND !empty($request->provider_id) ){
                    $user = User::where('email', $request->username)
                                ->where('provider_id', $request->provider_id)
                                ->first();
                    if(!$user){
                        $user = User::where('email', $request->username)->first();
                        if(!$user){
                            $isRegister = true;
                        }
                        
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
                ]);
            } else if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
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
    * Register user
    **/
    public function register(Request $request)
    {
        try {
            $loginWith = $this->validateUsername(request()->input('username'));

            $request->validate([
                'username' => 'required|' . ($loginWith == 'email' ? 'string|lowercase|email|max:255|unique:users' : 'digits:10|unique:users,mobile'),
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ],[
                'username' => 'The username field must be a valid email address or mobile number'
            ]);

            $user = User::create([
                'email'    => ($loginWith == 'email') ? $request->username : NULL,
                'mobile'   => ($loginWith == 'mobile') ? $request->username : NULL,
                'password' => Hash::make($request->password),
            ]);

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

            $OTP = $this->generateOTP();

            $user->otp = $OTP;
            $user->save();
//dd($OTP);
            /* Send otp to mobile number */

            // Replace with your username
            $user = "your_user_name";
            // Replace with your API KEY (We have sent API KEY on activation email, also available on panel)
            $password = "your_password";
            // Replace with the destination mobile Number to which you want to send sms
            $msisdn = $request->mobile;
            // Replace if you have your own Six character Sender ID, or check with our support team.
            $sid = "AREPLY";
            // Replace with client name
            //$name = "Anurag Sharrma";
            // Replace if you have OTP in your template.
            //$OTP = "6765R";
            // Replace with your Message content
            $msg = "Your One Time Password is ".$OTP.". Thanks SMSINDIAHUB";
            $msg = urlencode($msg);

            $fl = "0";
            // if you are using transaction sms api then keep gwid = 2 or if promotional then remove this parameter
            $gwid = "2";
            // For Plain Text, use "txt" ; for Unicode symbols or regional Languages like hindi/tamil/kannada use "uni"
            $type = "txt";

            //--------------------------------------
            //http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?APIKey=7OaWXe6a9E6VYi9HWQ66KA&msisdn=9878512185&sid=AREPLY&msg=Your One Time Password is 12121. Thanks SMSINDIAHUB&fl=0&gwid=2
            //step1
            $cSession = curl_init(); 
            //step2
            $url = "http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?APIKey=7OaWXe6a9E6VYi9HWQ66KA&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=0&gwid=2";
            //$response = Http::get('http://test.com');
            //dd($url);
            curl_setopt($cSession,CURLOPT_URL,$url);
            curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($cSession,CURLOPT_HEADER, false); 
            //step3
            $result=curl_exec($cSession);
            //step4
            curl_close($cSession);
            //step5
            //echo $result;
            $result = json_decode($result);

            $response = false;
            if(isset($result->JobId) AND !empty($result->JobId)){
                $response = true;
            }

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
