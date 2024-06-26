<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseDeposit;
use App\Models\PaySlips;
use App\Models\Pointing;
use App\Models\User;
use App\Notifications\NewTeacherNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = User::where('role', 'teacher')->orderBy('created_at', 'desc')->get();
        $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
        return view('Admin.Teachers.index', compact('teachers', 'courseDeposit'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $apiToken = env('DEXCHANGE_API_TOKEN');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiToken,
        ])->get('https://api-m.dexchange.sn/api/v1/api-services/services');
        if ($response->status() === 200) {
            $services = $response->json()['services'];
            $cashInServices = array_filter($services, function ($service) {
                return Str::endsWith($service['serviceCode'], 'CASHIN');
            });
        }

        $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
        return view('Admin.Teachers.create', compact('courseDeposit', 'cashInServices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $customMessages = [
            'required' => 'Veuillez remplir ce champ.',
            'regex' => 'Format de numéro invalide.(ex:77XXXXXX)',

        ];
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => ['required', 'string', 'max:255', 'regex:/^[0-9]{9}$/'],
            'poste' => 'required',
            'reseau' => 'required',
        ], $customMessages);
        $existingTeacher = User::where('name', $data['name'])
            ->orWhere('email', $data['email'])
            ->first();
        if ($existingTeacher) {
            $message = 'Un utilisateur avec ce nom ou cette adresse e-mail existe déjà.';
            $request->session()->flash('error_message', $message);
            return redirect()->route('admin.teacher.index');
        }
        $password = Hash::make('LesTutorielsTuteurs');
        $teacher = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $password,
            'phone' => $data['phone'],
            'role' => 'teacher',
            'poste' => $data['poste'],
            'reseau' => $data['reseau'],
        ]);
        $teacher->notify(new NewTeacherNotification($teacher));
        $message = 'Nouveau tuteur enregistré avec succès. Il peut consulter ses emails. ';
        $request->session()->flash('success_message', $message);
        return redirect()->route('admin.teacher.index');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $apiToken = env('DEXCHANGE_API_TOKEN');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiToken,
        ])->get('https://api-m.dexchange.sn/api/v1/api-services/services');
        if ($response->status() === 200) {
            $services = $response->json()['services'];
            $cashInServices = array_filter($services, function ($service) {
                return Str::endsWith($service['serviceCode'], 'CASHIN');
            });
        }
        $teacher = User::where('role', 'teacher')->where('id', $id)->first();
        $pointing = Pointing::all();
        $courseDeposit = CourseDeposit::where('state', 'en attente')->count();

        if (!$teacher) {
            $message = 'Enseignant non trouvé.';
            $request->session()->flash('error_message', $message);
            return redirect()->route('admin.teacher.index');
        }
        return view('Admin.Teachers.show', compact('teacher', 'pointing', 'cashInServices', 'courseDeposit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customMessages = [
            'required' => 'Veuillez remplir ce champ.',
            'regex' => 'Format de numéro invalide.(ex:77XXXXXX)',
        ];
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => ['required', 'string', 'max:255', 'regex:/^[0-9]{9}$/'],
            'poste' => 'required',
            'reseau' => 'required',
        ], $customMessages);
        $teacher = User::where('role', 'teacher')->find($id);
        if (!$teacher) {
            $message = 'Enseignant non trouvé.';
            $request->session()->flash('error_message', $message);

        } else {
            $teacher->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'poste' => $data['poste'],
                'reseau' => $data["reseau"],
            ]);
            $message = 'Tuteur modifié avec succès';
            $request->session()->flash('success_message', $message);
        }
        return redirect()->route('admin.teacher.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $teacher = User::where('role', 'teacher')->find($id);
        if (!$teacher) {
            $message = "La suppression a échouée";
            session()->flash('success_message', $message);
        } else {
            $pointings = Pointing::where('user_id', $teacher->id)->get();
            $courseDeposits = CourseDeposit::where('user_id', $teacher->id)->get();
            $paySlips = PaySlips::where('user_id', $teacher->id)->get();
            foreach ($pointings as $pointing) {
                $pointing->delete();
            }

            foreach ($courseDeposits as $courseDeposit) {
                $filePath = storage_path('app/' . $courseDeposit->support_file);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $courseDeposit->delete();
            }

            foreach ($paySlips as $paySlip) {
                $filePath = storage_path('app/' . $paySlip->file_path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $paySlip->delete();
            }

            $teacher->delete();
            $message = "Le tuteur a été supprimé avec success !";
            session()->flash('success_message', $message);
        }
        return redirect()->back();
    }
}
