<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pointing;
use Illuminate\Http\Request;
use App\Models\CourseDeposit;
use App\Http\Controllers\Controller;

class CourseDepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $course_deposits = CourseDeposit::all();
        $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
        return view('Admin.CourseDeposit.index', compact('course_deposits', 'courseDeposit'));
    }



    public function download($id)
    {
        $courseDeposit = CourseDeposit::findOrFail($id);
        $filePath = storage_path('app/' . $courseDeposit->support_file);

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            // Gérez le cas où le fichier n'existe pas
            return redirect()->back()->with('error', 'Fichier introuvable.');
        }
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

        $courseDeposit = CourseDeposit::find($id);
        if (!$courseDeposit) {
            $message = "La suppression a échouée";
            session()->flash('success_message', $message);
        } else {
            // Supprimer le fichier du stockage public
            $filePath = storage_path('app/' . $courseDeposit->support_file);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            // Supprimer l'enregistrement de CourseDeposit
            $courseDeposit->delete();

            $message = "Le cours déposé a été supprimé avec succès !";
            session()->flash('success_message', $message);
        }
        return redirect()->back();
    }


    public function deleteAll()
    {
        CourseDeposit::truncate();
        $message = 'Tous les cours déposés supprimés avec succès';
        session()->flash('success_message', $message);
        return redirect()->route('admin.course-deposit.index');
    }


    public function valide($id)
    {
        $courseDeposit = CourseDeposit::find($id);

        if (!$courseDeposit) {
            $message = 'Cours déposé introuvable';
            session()->flash('error_message', $message);
        }else{
            $courseDeposit->state = 'validé';
            $courseDeposit->save();
            $message = 'Cours déposé validé avec succès!';
            session()->flash('success_message', $message);

        }
        return redirect()->back();
    }


    public function refuse($id)
    {
        $courseDeposit = courseDeposit::find($id);

        if (!$courseDeposit) {
            $message = 'Cours déposé introuvable';
            session()->flash('error_message', $message);
        }else{
            $courseDeposit->state = 'rejete';
            $courseDeposit->save();
            $message = 'Cours déposé rejeté avec succès!';
            session()->flash('success_message', $message);

        }
        return redirect()->back();
    }
}
