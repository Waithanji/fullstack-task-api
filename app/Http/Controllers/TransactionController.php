<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Utilities\config\Config;
use App\SavingsAccount;
use App\CurrentAccount;
use App\User;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validation = Validator::make($request->json()->all(), [
            'user_id' => 'required', 'account_type' => 'required', 'transaction_type' => 'required', 'access' => 'required', 'amount' => 'required'
        ]);
        if($validation->fails())
            return response()->json(['success' => false, 'message' => 'Failed to process transaction. Please check your input & try again', 'errors' => $validation->errors()->getMessages(),]);

        $data = $request->json()->all();
        if($transaction = Transaction::create($data)){

            if($transaction->account_type == Config::ACCOUNT_TYPE_SAVINGS) {
                $affectedAccount = SavingsAccount::where('user_id', $transaction->user_id)->first();
            } else {
                $affectedAccount = CurrentAccount::where('user_id', $transaction->user_id)->first();
            }

            if($transaction->transaction_type == Config::TRANSACTION_TYPE_CREDIT) {
                $newBalance = $affectedAccount->balance + $transaction->amount;
            } else {
                $newBalance = $affectedAccount->balance - $transaction->amount;
            }

            $affectedAccount->update(['balance' => $newBalance]);

            $user  = User::where('id', $transaction->user_id)->with(['savingsAccount', 'currentAccount'])->first();

            return response()->json(['success' => true, 'message' => 'The transaction has been processed successfully!', 'data' => $user]);
        } else
            return response()->json(['success' => false, 'message' => 'We encountered an error while processing the transaction.', 'data' => null]);

    }

    public function history(Request $request, $userId)
    {
        if($transactions = Transaction::where('user_id', $userId)->orderBy('created_at', 'DESC')->get()){
            return response()->json(['success' => true, 'message' => 'Transactions were found!', 'data' => $transactions]);
        } else
            return response()->json(['success' => false, 'message' => 'There are no transactions for your account. Please add a new transaction.', 'data' => null]);

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
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
