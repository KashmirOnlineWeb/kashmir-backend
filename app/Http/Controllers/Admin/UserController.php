<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /*
     * Display list of users. 
     * */
    public function index(Request $request)
    {
        try {
            /* Send users with role filter. */
            if(isset($request->role)){
                $users = User::orderBy('id','desc')->role(trim($request->role))->paginate(12);
            } else {
                $users = User::orderBy('id','desc')->paginate(12);
            }
            
            return view('User.index')->with(['users' => $users]);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in user index.');
        }
    }

    /**
     * Create user form.
     */
    public function create(Request $request): View
    {
        $roles = Role::select('id','name')->get();
        return view('User.edit',compact('roles'));
    }

    /*
    * Show user.
    **/
    public function show(Request $request, $id): View
    {
        try {
            $user = User::findOrFail($id);
            return view('User.show',compact('user'));

        } catch (Exception $e) {
            Log::error('Somethinng went wrong in user show.');   
        }
    }

    /**
     * Store user form.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->all();

            $request->validate([
                'email'         => 'required|string|lowercase|email|max:255|unique:users,email',
                'mobile'        => 'sometimes|nullable|digits:10|unique:users,mobile',
                'password'      => ['required', 'confirmed', Rules\Password::defaults()],
                'first_name'    => 'required|string',
                'last_name'     => 'required|string',
                'dob'           => 'sometimes|nullable|date_format:Y-m-d',
                'profile_image' => 'sometimes|nullable|string',
                'role'          => 'required|exists:roles,name',
            ]);

            $name = trim(($request->input('first_name') ?? '') . ' ' . ($request->input('last_name') ?? ''));

            $user = User::create([
                        'first_name'    => (isset($data['first_name']) ? $data['first_name'] : NULL), 
                        'last_name'     => (isset($data['last_name']) ? $data['last_name'] : NULL),
                        'email'         => (isset($data['email']) ? $data['email'] : NULL),
                        'mobile'        => (isset($data['mobile']) ? $data['mobile'] : NULL),
                        'dob'           => (isset($data['dob']) ? $data['dob'] : NULL),
                        'profile_image' => (isset($data['profile_image']) ? $data['profile_image'] : NULL),
                        'password'      => Hash::make($request->password),
                        'name'          => $name,
                    ]);

            $user->assignRole($data['role']);

            return Redirect::route('user.index',$user->id);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in user store.');
        }
    }

    /**
     * Edit the user form.
     */
    public function edit(Request $request, $id): View
    {
        $roles = Role::select('id','name')->get();
        $user  = User::findOrFail($id);
        
        return view('User.edit',compact('user', 'roles'));
    }

    /**
     * Update the user information.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $data = $request->all();
            $request->merge(['user_id' => $id]);
            
            $request->validate([
                            'user_id'       => 'required|integer|exists:users,id',
                            //'email'         => 'required|string|lowercase|email|max:255|unique:users,email',
                            //'mobile'        => 'sometimes|nullable|digits:10|unique:users,mobile',
                            //'password'      => ['required', 'confirmed', Rules\Password::defaults()],
                            'first_name'    => 'required|string',
                            'last_name'     => 'required|string',
                            'dob'           => 'sometimes|nullable|date_format:Y-m-d',
                            'profile_image' => 'sometimes|nullable|string',
                            'role'          => 'required|exists:roles,name',
                        ]);

            $name = trim(($request->input('first_name') ?? '') . ' ' . ($request->input('last_name') ?? ''));
            
            $response = User::where('id', $request->user_id)
                                ->update([
                                        'first_name'    => (isset($data['first_name']) ? $data['first_name'] : NULL), 
                                        'last_name'     => (isset($data['last_name']) ? $data['last_name'] : NULL),
                                        'dob'           => (isset($data['dob']) ? $data['dob'] : NULL),
                                        'profile_image' => (isset($data['profile_image']) ? $data['profile_image'] : NULL),
                                        //'password'      => Hash::make($request->password),
                                        'name'          => $name,
                                    ]);
            $user = User::find($request->user_id);
            $user->syncRoles([$data['role']]);

            return Redirect::route('user.index',$response);
        } catch (Exception $e) {
            Log::error('Somethinng went wrong in user update.');
        }
    }
}
