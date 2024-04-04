<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseDeposit;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::all();
        $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
        return view('Admin.Courses.index', compact('courses', 'courseDeposit'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
        return view('Admin.Courses.create', compact('courseDeposit'));
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
            'name' => 'required',
            'price_per_hour' => 'required',
        ], $customMessages);
        $existingCourse = Course::where('name', $data['name'])->first();
        if ($existingCourse) {
            $message = 'Cette matière existe déjà';
            $request->session()->flash('error_message', $message);
            return redirect()->route('admin.courses.index');
        }
        $teacher = Course::create([
            'name' => $data['name'],
            'price_per_hour' => $data['price_per_hour'],
        ]);
        $message = 'Nouvelle matière enregistrée avec succès.';
        $request->session()->flash('success_message', $message);
        return redirect()->route('admin.courses.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $course = Course::find($id);
        $courseDeposit = CourseDeposit::where('state', 'en attente')->count();

        if (!$course) {
            $message = 'Matière non trouvée.';
            $request->session()->flash('error_message', $message);
            return redirect()->route('admin.courses.index');
        }
        return view('Admin.Courses.show', compact('course', 'courseDeposit'));
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
            'name' => 'required',
        ];
        $data = $request->validate([
            'name' => 'required',
            'price_per_hour' => 'required',

        ], $customMessages);
        $course = Course::find($id);
        if (!$course) {
            $message = 'Matière non trouvée.';
            $request->session()->flash('error_message', $message);

        } else {
            $course->update([
                'name' => $data['name'],
                'price_per_hour' => $data['price_per_hour'],

            ]);
            $message = 'Matière modifiée avec succès';
            $request->session()->flash('success_message', $message);
        }
        return redirect()->route('admin.courses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = Course::find($id);
        if (!$course) {
            $message = "La suppression a échoué";
            session()->flash('error_message', $message);
            return redirect()->back();
        }

        $course->courseDeposits()->delete();

        $course->pointings()->delete();

        $course->delete();

        $message = "La matière a été supprimée avec succès !";
        session()->flash('success_message', $message);

        return redirect()->back();
    }

}
