<?php

namespace App\Http\Controllers;

use App\CurrentAccount;
use App\SavingsAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $userId)
    {
        if($userInfo = User::where('id', $userId)->with(['savingsAccount', 'currentAccount'])->first()) {
            return response()->json(['success' => true, 'message' => 'Summary of your bank accounts', 'data' => $userInfo]);
        } else
            return response()->json(['success' => false, 'message' => 'Please login to access this page.', 'data' => null]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validation = Validator::make($request->json()->all(), [
            'first_name' => 'required', 'last_name' => 'required', 'email_address' => 'email|unique:users|max:140', 'password' => 'required|min:6'
        ]);
        if($validation->fails())
            return response()->json(['success' => false, 'message' => 'Failed to create account. Please check your input & try again', 'errors' => $validation->errors()->getMessages(),]);

        $password = Hash::make($request->json('password'));
        $apiToken = Hash::make(uniqid());

        $data = $request->json()->all();
        $data['password'] = $password;
        $data['api_token'] = $apiToken;

        if($user = User::create($data)){

            SavingsAccount::create(['user_id' => $user->id, 'balance' => 0]);
            CurrentAccount::create(['user_id' => $user->id, 'balance' => 0]);

            return response()->json(['success' => true, 'message' => 'Your account has been created successfully!', 'data' => $user]);
        } else
            return response()->json(['success' => false, 'message' => 'We encountered an error while creating your account.', 'data' => null]);

    }

    public function login(Request $request)
    {
        $validation = Validator::make($request->json()->all(), [
            'email_address' => 'required', 'password' => 'required'
        ]);
        if($validation->fails())
            return response()->json(['success' => false, 'message' => 'There was a problem logging you in. Please check your input & try again', 'errors' => $validation->errors()->getMessages(),]);

        $email = $request->json('email_address');
        if ($user = User::where('email_address', $email)->first()) {

            $apiToken = Hash::make(uniqid() . $email);
            $user->update(['api_token' => $apiToken]);

            if(Hash::check($request->json('password'), $user->password)) {


                return response()->json(['success' => true, 'message' => 'You have successfully logged in', 'data' => $user]);
            } else
                return response()->json(['success' => false, 'message' => 'Login failed. Your details are incorrect', 'data' => null]);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
