<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Pointing;
use App\Models\Promotion;
use Illuminate\Http\Request;
use App\Models\CourseDeposit;
use App\Models\RestoreAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['restore_account_view', 'restore_account']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id = null)
    {
        $user = Auth::user();
        $courses = Course::all();
        $promotions = Promotion::all();
        $pointing = null;
        $tuteurs = null;
        $admin = null;
        if ($user->role == "admin") {
            $tuteurs = User::where('role', 'teacher')->get();
            $courseDeposit = CourseDeposit::where('state', 'en attente')->count();

            $admin = User::where('role', 'admin')
            ->where(function ($query) {
                $query->where('name', '!=', 'Recuperation')
                    ->orWhere('email', '!=', 'recuperation@gmail.com');
            })
            ->get();
            $pointing = Pointing::all();
        } elseif ($user->role == "teacher" && $id) {
            $courseDeposit = CourseDeposit::where('user_id',$user->id)->where('state', 'rejeté')->count();
            $pointing = Pointing::find($id);
            
        }elseif($user->role == "teacher"){
            $courseDeposit = CourseDeposit::where('user_id',$user->id)->where('state', 'rejeté')->count();
        }

        return view('home', compact('courses', 'promotions', 'pointing', 'tuteurs', 'admin', 'courseDeposit'));
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
        //
    }


    public function restore_account_view()
    {
        return view('auth.passwords.restore-account');
    }


    private function validator(Request $request)
    {
        return request()->validate([
            'email' => [function ($attribute, $value, $fail) {
                if (empty($value)) {
                    $fail('Veuillez remplir ce champ');
                }
            },'string', 'email', 'max:255'],

        ]);
    }



    public function profile()
    {
        $id = Auth::user()->id;
        $user = User::where('id', $id)->first();
        $courseDeposit = 0;
        $pointing = Pointing::all();
        if($user->role == "teacher"){
            $courseDeposit = CourseDeposit::where('user_id',$id)->where('state', 'rejeté')->count();
        }else{
            $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
        }
        return view('profile', compact('user', 'pointing', 'courseDeposit'));
    }

    public function profile_update(Request $request, $id)
    {
        $customMessages = [
            'required' => 'Veuillez remplir ce champ.',
            'confirmed' => 'Les mots de passe ne correspondent pas',
            'min' => 'Le mot de passe doit avoir au moins 8 caractères'

        ];
        $data = $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed|min:8'
        ], $customMessages);
        $user = User::find($id);
        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->back()->withErrors(['old_password' => 'L\'ancien mot de passe est incorrect']);
        }
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $message = "Votre mot de passe a été modifié avec success!";
        $request->session()->flash('success_message', $message);
        return redirect()->back();

    }


    public function restore_account(Request $request)
    {
        $this->validator($request);
        $email = $request["email"];
        $user = User::where('email', '=', $email)->first();
        if (!$user) {
            $message = "Vous n'avez pas encore de compte !Veuillez contacter un administrateur.";
            $request->session()->flash('error_message', $message);
            return redirect()->back();
        } else {
            RestoreAccount::updateOrCreate(
                [
                    'email' => $email,
                ],
                [
                    'name' => $user->name,
                    'phone' => $user->phone,
                ]
            );
            $message = "Votre demande de restauration de compte sera traitée. Veuillez verifier vos mails!";
            $request->session()->flash('success_message', $message);
            return redirect()->back();
        }
    }


    public function handleCallback(Request $request)
    {
        // Récupérer les données envoyées via la route /callback
        $data = $request->all();

        // Afficher les données dans les logs
        Log::info('Callback data: ' . json_encode($data));

        // Vous pouvez effectuer d'autres opérations avec les données ici

        // Retourner une réponse si nécessaire
        // return response()->json(['message' => 'Callback handled successfully']);
    }

    public function handleSuccess(Request $request)
    {
        // Récupérer les données envoyées via la route /success
        $data = $request->all();

        // Afficher les données dans les logs
        Log::info('Success data: ' . json_encode($data));

        // Vous pouvez effectuer d'autres opérations avec les données ici

        // Retourner une réponse si nécessaire
        // return response()->json(['message' => 'Success handled successfully']);
    }

    public function handleFailure(Request $request)
    {
        // Récupérer les données envoyées via la route /failure
        $data = $request->all();

        // Afficher les données dans les logs
        Log::info('Failure data: ' . json_encode($data));

        // Vous pouvez effectuer d'autres opérations avec les données ici

        // Retourner une réponse si nécessaire
        // return response()->json(['message' => 'Failure handled successfully']);
    }
}