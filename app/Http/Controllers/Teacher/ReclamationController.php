<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Reclamation;
use Illuminate\Http\Request;
use App\Models\CourseDeposit;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReclamationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $reclamations = Reclamation::where('user_id', $user_id)->get();
        $courseDeposit = CourseDeposit::where('user_id',$user_id)->where('state', 'rejeté')->count();
        return view("Teacher.Reclamtions.index", compact('reclamations', 'courseDeposit'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user_id = Auth::user()->id;
        $courseDeposit = CourseDeposit::where('user_id',$user_id)->where('state', 'rejeté')->count();
        return view('Teacher.Reclamtions.create', compact('courseDeposit'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $customMessages = [
            'required' => 'Veuillez remplir ce champ.',
        ];
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
        ], $customMessages);

        $reclamations = Reclamation::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'user_id' => Auth::user()->id
        ]);
        $message = 'Nouvelle réclamtion enregistrée avec succès.';
        $request->session()->flash('success_message', $message);
        return redirect()->route('teacher.reclamations.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reclamation = Reclamation::where('id', $id)->first();
        return view('Teacher.Reclamtions.show', compact('reclamation'));
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
        $customMessages = [
            'required' => 'Veuillez remplir ce champ.',
        ];
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
        ], $customMessages);
        $reclamation = Reclamation::find($id);
        if (!$reclamation) {
            $message = 'Reclamation non trouvée.';
            $request->session()->flash('error_message', $message);

        } else {
            $reclamation->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'state' => 'en attente'
            ]);
            $message = 'Reclamation modifiée avec succès';
            $request->session()->flash('success_message', $message);
        }

        return redirect()->route('teacher.reclamations.index');

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
}
