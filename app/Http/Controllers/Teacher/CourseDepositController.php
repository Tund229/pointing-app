<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Course;
use App\Models\Promotion;
use Illuminate\Http\Request;
use App\Models\CourseDeposit;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CourseDepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $course_deposits = CourseDeposit::where('user_id', $user_id)->get();
        $courseDeposit = CourseDeposit::where('user_id',$user_id)->where('state', 'rejeté')->count();

        return view('Teacher.CourseDeposit.index', compact('course_deposits', 'courseDeposit'));
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
        $courses = Course::all();
        $promotions = Promotion::all();
        $user_id = Auth::user()->id;
        $courseDeposit = CourseDeposit::where('user_id',$user_id)->where('state', 'rejeté')->count();
        return view('Teacher.CourseDeposit.create', compact('courses', 'promotions', 'courseDeposit'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $customMessages = [
            'required' => 'Veuillez remplir ce champ.',
            'support_file.required' => 'Veuillez sélectionner un fichier à téléverser.',
            'support_file.file' => 'Le fichier doit être un fichier valide.',
            'support_file.mimes' => 'Le fichier doit être au format PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPEG, PNG, GIF ou JPG.',
        ];
        $data = $request->validate([
            'course_id' => 'required',
            'promotion_id' => 'required',
            'support_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpeg,png,gif,jpg',
            'comment' => 'required'
        ], $customMessages);
        $course = Course::findOrFail($data['course_id']); // Récupérer la matière
        $promotion = Promotion::findOrFail($data['promotion_id']); // Récupérer la promotion
        $user = Auth::user();
        $fileName = $user->name . '_' . $course->name . '_' . $promotion->name . '.' . $request->file('support_file')->getClientOriginalExtension();
        $filePath = $request->file('support_file')->storeAs('public/Fichiers', $fileName);
        $teacher = CourseDeposit::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'promotion_id' => $promotion->id,
            'comment' => $request['comment'],
            'support_file' => $filePath, // Stocker le chemin du fichier dans la base de données
        ]);
        $message = 'Nouveau cours déposé avec succès.';
        $request->session()->flash('success_message', $message);
        return redirect()->route('teacher.course-deposit.index');

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
}
