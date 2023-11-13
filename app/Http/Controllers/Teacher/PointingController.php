<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Pointing;
use Illuminate\Http\Request;
use App\Models\CourseDeposit;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PointingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $user_id = Auth::user()->id;
        $pointings = Pointing::where('user_id',$user_id )->get();
        $courseDeposit = CourseDeposit::where('user_id',$user_id)->where('state', 'rejeté')->count();
        return view('Teacher.Pointing.index', compact('pointings', 'courseDeposit'));
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
    
        $customMessages = [
            'required' => 'Veuillez remplir ce champ.',
        ];
        $data = $request->validate([
            'course_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'course_id' => 'required',
            'promotion_id' => 'required',
            'comment' => 'required'
        ], $customMessages);

        $teacher = Pointing::create([
            'user_id'=>Auth::user()->id,
            'course_date' => $data['course_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'course_id' => $data['course_id'],
            'promotion_id' => $data['promotion_id'],
            'comment' => $request['comment'],
        ]);
        $message = 'Nouveau pointage enregistré avec succès.';
        $request->session()->flash('success_message', $message);
        return redirect()->route('teacher.pointing.index');
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
        $customMessages = [
            'required' => 'Veuillez remplir ce champ.',
        ];
        $data = $request->validate([
            'course_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'course_id' => 'required',
            'promotion_id' => 'required',
            'comment' => 'required'
        ], $customMessages);
        $pointing = Pointing::find($id);
        if (!$pointing) {
            $message = 'Pointage non trouvé.';
            $request->session()->flash('error_message', $message);

        }else{
            $pointing->update([
                'course_date' => $data['course_date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'course_id' => $data['course_id'],
                'promotion_id' => $data['promotion_id'],
                'comment' => $request['comment']
            ]);
            $message = 'Pointage modifié avec succès';
            $request->session()->flash('success_message', $message);
        }

        return redirect()->route('teacher.pointing.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pointing = Pointing::find($id);
        if (!$pointing) {
            $message = "La suppression a échouée";
            session()->flash('success_message', $message);
        } else {
            $pointing->delete();
            $message = "Le pointage a été supprimé avec success !";
            session()->flash('success_message', $message);
        }
        return redirect()->back();
    }
}
