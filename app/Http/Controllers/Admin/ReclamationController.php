<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pointing;
use App\Models\Reclamation;
use Illuminate\Http\Request;
use App\Models\CourseDeposit;
use App\Http\Controllers\Controller;

class ReclamationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reclamations = Reclamation::all();
        $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
        return view('Admin.Reclamations.index', compact('reclamations', 'courseDeposit'));
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
        $reclamation = Reclamation::find($id);
        if (!$reclamation) {
            $message = "La suppression a échouée";
            session()->flash('success_message', $message);
        } else {
            $reclamation->delete();
            $message = "La reclamation a été supprimé avec success !";
            session()->flash('success_message', $message);
        }
        return redirect()->back();
    }


    public function valide($id)
    {
        $reclamation = Reclamation::find($id);

        if (!$reclamation) {
            $message = 'Reclamation introuvable';
            session()->flash('errot_message', $message);
        }else{
            $reclamation->state = 'validé';
            $reclamation->save();
            $message = 'Reclamation validée avec succès!';
            session()->flash('success_message', $message);

        }
        return redirect()->back();
    }


    public function refuse($id)
    {
        $reclamation = Reclamation::find($id);

        if (!$reclamation) {
            $message = 'Reclamation introuvable';
            session()->flash('errot_message', $message);
        }else{
            $reclamation->state = 'rejete';
            $reclamation->save();
            $message = 'Reclamation rejetée avec succès!';
            session()->flash('success_message', $message);

        }
        return redirect()->back();
    }
}
