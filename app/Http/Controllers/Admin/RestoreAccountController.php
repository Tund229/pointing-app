<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Pointing;
use Illuminate\Http\Request;
use App\Models\CourseDeposit;
use App\Models\RestoreAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Notifications\RestoreAccountNotification;

class RestoreAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $restoreAccounts = RestoreAccount::all();
        $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
        return view('Admin.RestoreAccount.index', compact('restoreAccounts', 'courseDeposit'));
    }


    public function restore_account_valide($id){
        $restoreAccount = RestoreAccount::where('id', $id)->first();
        if($restoreAccount){
            $user = User::where('email', $restoreAccount->email)->first();
            $user->update([
                'password'=>Hash::make('LesTutorielsPointage')
            ]);
            $restoreAccount->delete();
            $user->notify(new RestoreAccountNotification($user));
            $message = 'Le mot de passe de'. $user->name .'a été restauré avec succès';
            session()->flash('success_message', $message);
        }
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $restoreAccount = RestoreAccount::find($id);
        if (!$restoreAccount) {
            $message = "La suppression a échouée";
            session()->flash('success_message', $message);
        } else {
            $restoreAccount->delete();
            $message = "Le demande a été supprimée avec success !";
            session()->flash('success_message', $message);
        }
        return redirect()->back();
    }
}
