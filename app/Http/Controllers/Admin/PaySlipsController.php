<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Models\User;
use App\Models\PaySlips;
use App\Models\Pointing;
use Illuminate\Http\Request;
use App\Models\CourseDeposit;
use App\Http\Controllers\Controller;
use App\Models\FicheAdminTuteurFixe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaySlipsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paySlips = PaySlips::all();
        $ficheAdminTuteurFixes = FicheAdminTuteurFixe::all();
        $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
        return view('Admin.PaySlips.index', compact('paySlips', 'courseDeposit', 'ficheAdminTuteurFixes'));
    }

    public function downloadPaySlips($id)
    {
        $paySlip = PaySlips::findOrFail($id);
        $filePath = storage_path('app/' . $paySlip->file_path);

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = User::where(function ($query) {
            $query->where('name', '!=', 'Recuperation')
                ->orWhere('email', '!=', 'recuperation@gmail.com');
        })->get();
        $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
        return view('Admin.PaySlips.create', compact('teachers', 'courseDeposit'));
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
            'user_id' => 'required',
            'month' => 'required',
        ], $customMessages);

        $user = User::find($data['user_id']);

        if ($user) {
            // Générez un code de 6 chiffres
            $code = sprintf('%06d', mt_rand(1, 999999));

            // Créez l'enregistrement PaySlips
            $paySlip = PaySlips::create([
                'user_id' => $user->id,
                'admin_id' => Auth::user()->id,
                'total_hours' => $user->total_hours,
                'amount' => $user->amount,
                'month' => $data['month'],
                'code' => $code, // Stockez le code généré
                'state' => false,
            ]);

            // Récupérez les pointages pour l'utilisateur
            $pointings = Pointing::where('state', 'validé')->where('user_id', $user->id)->get();
            $pdfData = [
                'pointings' => $pointings,
                'user' => $user,
                'paySlip' => $paySlip,
            ];

            // Ajoutez le modèle PaySlips au tableau de données
            $pdfData['paySlip'] = $paySlip;

            // Générez le PDF
            $pdf = PDF::loadView('pdf.PaySlips', $pdfData);
            $filename = 'fiche_paie_' . $user->name . '_' . $data['month'] . '.pdf';

            // Enregistrez le PDF dans le dossier "Fiche" du stockage
            Storage::disk('local')->put('Fiche/' . $filename, $pdf->output());

            // Stockez le chemin d'accès dans le modèle PaySlips
            $paySlip->file_path = 'Fiche/' . $filename;
            $paySlip->save();

            $message = 'Fiche de paie ajoutée avec succès';
            $request->session()->flash('success_message', $message);
        } else {
            $message = "Une erreur s'est produite !";
            $request->session()->flash('error_message', $message);
        }

        return redirect()->route('admin.pay-slips.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $paySlip = PaySlips::findOrFail($id);
        $paiment = $paySlip->paiement;
        dd($paiment);
        return view('admin.pay_slips.show', compact('paySlip'));

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
        $paySlip = PaySlips::find($id);
        if (!$paySlip) {
            $message = "La suppression a échouée";
            session()->flash('success_message', $message);
        } else {
            $filePath = storage_path('app/' . $paySlip->file_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $paySlip->delete();
            $message = "La fiche de paie a été supprimée avec success !";
            session()->flash('success_message', $message);
        }
        return redirect()->back();
    }
}
