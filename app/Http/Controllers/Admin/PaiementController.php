<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Code;
use App\Models\CourseDeposit;
use App\Models\FicheAdminTuteurFixe;
use App\Models\Paiement;
use App\Models\PaySlips;
use App\Models\TuteurFix;
use App\Models\User;
use App\Notifications\PaiementNotification;
use auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;

class PaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $code = $user->code;

        if ($code) {
            if (!$code->expired()) {
                if (!$code->verified) {
                    return $this->showVerificationPage();
                } else {
                    $paySlips = PaySlips::where('state', false)->get();
                    $paiements = Paiement::all();
                    $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
                    return view('Admin.Paiement.index', compact('paySlips', 'courseDeposit', 'paiements'));
                }
            } else {
                $this->generate();
                return redirect()->route('admin.verification.page')->with('info_message', 'Un nouveau code a été généré. Veuillez vérifier votre code pour accéder à la page de paiement.');
            }
        } else {
            $this->generate();
            return redirect()->route('admin.verification.page')->with('info_message', 'Un code a été généré pour vous. Veuillez vérifier votre code pour accéder à la page de paiement.');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'pay_slip_id' => 'required|exists:pay_slips,id',
        ]);
        $apiToken = env('DEXCHANGE_API_TOKEN');
        $paySlipId = $request->pay_slip_id;
        $paySlip = PaySlips::findOrFail($paySlipId);
        $user = $paySlip->user;
        $externalTransactionId = Str::uuid();
        $payload = [
            'amount' => abs($paySlip->amount),
            'serviceCode' => $user->reseau,
            'callBackURL' => 'https://academy-tutoriels.com/callback',
            'externalTransactionId' => $externalTransactionId,
            'failureUrl' => 'https://academy-tutoriels.com/',
            'successUrl' => 'https://academy-tutoriels.com/',
            'number' => $user->phone,
        ];
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiToken,
        ])->post('https://api-m.dexchange.sn/api/v1/transaction/init', $payload);
        $responseData = $response->json();
        if (isset($responseData['transaction']) && $responseData['transaction']['success']) {
            $transactionId = $responseData['transaction']['transactionId'];
            $response = Http::get("https://api-m.dexchange.sn/api/v1/transaction/{$transactionId}");
            $paiementData = [
                'phone' => $user->phone,
                'amount' => abs($user->amount),
                'externalTransactionId' => $externalTransactionId,
                'status' => $responseData['transaction']['Status'],
                'success' => $responseData['transaction']['success'],
                'transactionFee' => $responseData['transaction']['transactionFee'],
                'transactionCommission' => $responseData['transaction']['transactionCommission'],
                'transactionId' => $responseData['transaction']['transactionId'],
                'transactionType' => $responseData['transaction']['transactionType'],
                'previousBalance' => $responseData['transaction']['previousBalance'],
                'currentBalance' => $responseData['transaction']['currentBalance'],
                'user_id' => $user->id,
                'paye_by' => auth()->id(),
                'pay_slip_id' => $paySlip->id,
            ];
            Paiement::create($paiementData);
            $paySlip->update(['state' => true]);
            $successMessage = "La fiche de paie a été payée avec succès.";
            session()->flash('success_message', $successMessage);
            return redirect()->route('admin.paiements.index');
        } else {
            $errorMessage = $responseData['transaction']['message'];
            if ($errorMessage === "Request invalid") {
                $errorMessage = "Le paiement de la fiche a échoué";
            } else {
                $errorMessage = "Une erreur est survenue lors du paiement de la fiche de paie";
            }
            session()->flash('error_message', $errorMessage);
            return redirect()->route('admin.paiements.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function generate()
    {

        $user = auth()->user();
        $code = Code::where('user_id', $user->id)->first();
        if ($code) {
            $code->code = Str::random(6);
            $code->expire_at = Carbon::now()->addMinutes(15);
            $code->save();
        } else {
            $code = new Code([
                'user_id' => $user->id,
                'code' => Str::random(6),
                'expire_at' => Carbon::now()->addMinutes(5),
            ]);
            $code->save();
        }
        $admin = User::where('email', 'deniscoly19@gmail.com')->first();
        if ($admin) {
            $admin->notify(new PaiementNotification($code));
            return redirect()->route('admin.verification.page')->with('success_message', 'Le code a été généré et envoyé à l\'admin.');
        } else {
            return back()->with('error_message', 'L\'admin spécifié n\'a pas été trouvé.');
        }
    }

    public function showVerificationPage()
    {
        $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
        return view('Admin.Paiement.verification-page', compact('courseDeposit'));
    }

    public function check(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $code = Code::where('code', $request->code)
            ->where('user_id', $user->id)
            ->where('expire_at', '>', Carbon::now())
            ->first();

        if ($code) {
            $code->update(['verified' => true]);
            return redirect()->route('admin.paiements.index')->with('success_message', 'Code valide. Accès autorisé.');
        } else {
            return redirect()->back()->with('error_message', 'Code invalide ou expiré. Veuillez réessayer.');
        }
    }

    public function paiement_admin()
    {

        $user = auth()->user();
        $code = $user->code;
        if ($code) {
            if (!$code->expired()) {
                if (!$code->verified) {
                    return $this->showVerificationPage();
                } else {
                    $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
                    $admins = User::where('role', 'admin')
                        ->where(function ($query) {
                            $query->where('name', '!=', 'Recuperation')
                                ->orWhere('email', '!=', 'recuperation@gmail.com');
                        })->orderBy('created_at', 'desc')
                        ->get();
                    return view('Admin.Paiement.paiement-admin', compact('courseDeposit', 'admins'));
                }
            } else {
                $this->generate();
                return redirect()->route('admin.verification.page')->with('info_message', 'Un nouveau code a été généré. Veuillez vérifier votre code pour accéder à la page de paiement.');
            }
        } else {
            $this->generate();
            return redirect()->route('admin.verification.page')->with('info_message', 'Un code a été généré pour vous. Veuillez vérifier votre code pour accéder à la page de paiement.');
        }

    }
    public function paiement_employes()
    {
        $user = auth()->user();
        $code = $user->code;

        if ($code) {
            if (!$code->expired()) {
                if (!$code->verified) {
                    return $this->showVerificationPage();
                } else {
                    $employes = TuteurFix::where('state', true)
                        ->orderBy('created_at', 'desc')
                        ->get();
                    $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
                    return view('Admin.Paiement.paiement-employes', compact('courseDeposit', 'employes'));
                }
            } else {
                $this->generate();
                return redirect()->route('admin.verification.page')->with('info_message', 'Un nouveau code a été généré. Veuillez vérifier votre code pour accéder à la page de paiement.');
            }
        } else {
            $this->generate();
            return redirect()->route('admin.verification.page')->with('info_message', 'Un code a été généré pour vous. Veuillez vérifier votre code pour accéder à la page de paiement.');
        }
    }

    // public function paiement_admin_store(Request $request)
    // {

    //     $customMessages = [
    //         'required' => 'Veuillez remplir ce champ.',
    //     ];

    //     $data = $request->validate([
    //         'user_id' => 'required',
    //         'month' => 'required',
    //     ], $customMessages);

    //     $user = User::find($data['user_id']);
    //     if ($user) {
    //         $code = sprintf('%06d', mt_rand(1, 999999));
    //         $ficheAdminTuteurFixe = FicheAdminTuteurFixe::create([
    //             'admin_id' => $user->id,
    //             'amount' => $user->amount,
    //             'month' => $data['month'],
    //             'code' => $code,
    //         ]);
    //         $ficheAdminTuteurFixe->update(['state' => true]);
    //         $pdfData = [
    //             'user' => $user,
    //             'paySlip' => $ficheAdminTuteurFixe,
    //         ];
    //         $pdfData['paySlip'] = $ficheAdminTuteurFixe;
    //         $pdf = PDF::loadView('pdf.NewPaySlips', $pdfData);
    //         $filename = 'fiche_paie_' . $user->name . '_' . $data['month'].'_'.$code.'.pdf';
    //         Storage::disk('local')->put('Fiche/' . $filename, $pdf->output());
    //         $ficheAdminTuteurFixe->file_path = 'Fiche/' . $filename;
    //         $ficheAdminTuteurFixe->save();
    //         $successMessage = "La fiche de paie a été payée avec succès.";
    //         session()->flash('success_message', $successMessage);
    //         return redirect()->route('admin.paiements.index');
    //     } else {
    //         $message = "Une erreur s'est produite !";
    //         $request->session()->flash('error_message', $message);
    //     }

    //     return redirect()->route('admin.paiements.index');

    // }
    public function paiement_admin_store(Request $request)
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
            $code = sprintf('%06d', mt_rand(1, 999999));
            $ficheAdminTuteurFixe = FicheAdminTuteurFixe::create([
                'admin_id' => Auth::user()->id,
                'amount' => $user->amount,
                'month' => $data['month'],
                'code' => $code,
            ]);
            $apiToken = env('DEXCHANGE_API_TOKEN');
            $externalTransactionId = Str::uuid();
            $payload = [
                'amount' => abs($user->amount),
                'serviceCode' => $user->reseau,
                'callBackURL' => 'https://academy-tutoriels.com/callback',
                'externalTransactionId' => $externalTransactionId,
                'failureUrl' => 'https://academy-tutoriels.com/',
                'successUrl' => 'https://academy-tutoriels.com/',
                'number' => $user->phone,
            ];
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiToken,
            ])->post('https://api-m.dexchange.sn/api/v1/transaction/init', $payload);
            $responseData = $response->json();
            if (isset($responseData['transaction']) && $responseData['transaction']['success']) {
                $transactionId = $responseData['transaction']['transactionId'];
                $response = Http::get("https://api-m.dexchange.sn/api/v1/transaction/{$transactionId}");
                $paiementData = [
                    'phone' => $user->phone,
                    'amount' => abs($user->amount),
                    'externalTransactionId' => $externalTransactionId,
                    'status' => $responseData['transaction']['Status'],
                    'success' => $responseData['transaction']['success'],
                    'transactionFee' => $responseData['transaction']['transactionFee'],
                    'transactionCommission' => $responseData['transaction']['transactionCommission'],
                    'transactionId' => $responseData['transaction']['transactionId'],
                    'transactionType' => $responseData['transaction']['transactionType'],
                    'previousBalance' => $responseData['transaction']['previousBalance'],
                    'currentBalance' => $responseData['transaction']['currentBalance'],
                    'user_id' => $user->id,
                    'paye_by' => auth()->id(),
                    'pay_slip_admin_id' => $ficheAdminTuteurFixe->id,
                ];
                Paiement::create($paiementData);
                $ficheAdminTuteurFixe->update(['state' => true]);
                $pdfData = [
                    'user' => $user,
                    'paySlip' => $ficheAdminTuteurFixe,
                ];
                $pdfData['paySlip'] = $ficheAdminTuteurFixe;
                $pdf = PDF::loadView('pdf.NewPaySlips', $pdfData);
                $filename = 'fiche_paie_' . $user->name . '_' . $data['month'] . '_' . $code . '.pdf';
                Storage::disk('local')->put('Fiche/' . $filename, $pdf->output());
                $ficheAdminTuteurFixe->file_path = 'Fiche/' . $filename;
                $ficheAdminTuteurFixe->save();
                $successMessage = "La fiche de paie a été payée avec succès.";
                session()->flash('success_message', $successMessage);
                return redirect()->route('admin.paiements.index');
            } else {
                $errorMessage = $responseData['transaction']['message'];
                if ($errorMessage === "Request invalid") {
                    $errorMessage = "Le paiement de la fiche a échoué";
                } else {
                    $errorMessage = "Une erreur est survenue lors du paiement de la fiche de paie";
                }
                session()->flash('error_message', $errorMessage);
                return redirect()->route('admin.paiements.index');
            }
        } else {
            $message = "Une erreur s'est produite !";
            $request->session()->flash('error_message', $message);
        }

        return redirect()->route('admin.paiements.index');

    }

    public function paiement_employes_store(Request $request)
    {

        $customMessages = [
            'required' => 'Veuillez remplir ce champ.',
        ];

        $data = $request->validate([
            'user_id' => 'required',
            'month' => 'required',
        ], $customMessages);

        $user = TuteurFix::find($data['user_id']);
        if ($user) {
            $code = sprintf('%06d', mt_rand(1, 999999));
            $ficheAdminTuteurFixe = FicheAdminTuteurFixe::create([
                'tuteur_fixe_id' => Auth::user()->id,
                'amount' => $user->amount,
                'month' => $data['month'],
                'code' => $code,
            ]);
            $apiToken = env('DEXCHANGE_API_TOKEN');
            $externalTransactionId = Str::uuid();
            $payload = [
                'amount' => abs($user->amount),
                'serviceCode' => $user->reseau,
                'callBackURL' => 'https://academy-tutoriels.com/callback',
                'externalTransactionId' => $externalTransactionId,
                'failureUrl' => 'https://academy-tutoriels.com/',
                'successUrl' => 'https://academy-tutoriels.com/',
                'number' => $user->phone,
            ];
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiToken,
            ])->post('https://api-m.dexchange.sn/api/v1/transaction/init', $payload);
            $responseData = $response->json();
            if (isset($responseData['transaction']) && $responseData['transaction']['success']) {
                $transactionId = $responseData['transaction']['transactionId'];
                $response = Http::get("https://api-m.dexchange.sn/api/v1/transaction/{$transactionId}");
                $paiementData = [
                    'phone' => $user->phone,
                    'amount' => abs($user->amount),
                    'externalTransactionId' => $externalTransactionId,
                    'status' => $responseData['transaction']['Status'],
                    'success' => $responseData['transaction']['success'],
                    'transactionFee' => $responseData['transaction']['transactionFee'],
                    'transactionCommission' => $responseData['transaction']['transactionCommission'],
                    'transactionId' => $responseData['transaction']['transactionId'],
                    'transactionType' => $responseData['transaction']['transactionType'],
                    'previousBalance' => $responseData['transaction']['previousBalance'],
                    'currentBalance' => $responseData['transaction']['currentBalance'],
                    'tuteur_fixe_id' => $user->id,
                    'paye_by' => auth()->id(),
                    'pay_slip_admin_id' => $ficheAdminTuteurFixe->id,
                ];
                Paiement::create($paiementData);
                $ficheAdminTuteurFixe->update(['state' => true]);
                $pdfData = [
                    'user' => $user,
                    'paySlip' => $ficheAdminTuteurFixe,
                ];
                $pdfData['paySlip'] = $ficheAdminTuteurFixe;
                $pdf = PDF::loadView('pdf.NewPaySlips', $pdfData);
                $filename = 'fiche_paie_' . $user->name . '_' . $data['month'] . '_' . $code . '.pdf';
                Storage::disk('local')->put('Fiche/' . $filename, $pdf->output());
                $ficheAdminTuteurFixe->file_path = 'Fiche/' . $filename;
                $ficheAdminTuteurFixe->save();
                $successMessage = "La fiche de paie a été payée avec succès.";
                session()->flash('success_message', $successMessage);
                return redirect()->route('admin.paiements.index');
            } else {
                $errorMessage = $responseData['transaction']['message'];
                if ($errorMessage === "Request invalid") {
                    $errorMessage = "Le paiement de la fiche a échoué";
                } else {
                    $errorMessage = "Une erreur est survenue lors du paiement de la fiche de paie";
                }
                session()->flash('error_message', $errorMessage);
                return redirect()->route('admin.paiements.index');
            }
        } else {
            $message = "Une erreur s'est produite !";
            $request->session()->flash('error_message', $message);
        }

        return redirect()->route('admin.paiements.index');

    }

    public function downloadPaySlips(Paiement $paiement)
    {
        if ($paiement->pay_slip_id !== null) {
            $filePath = storage_path('app/' . $paiement->paySlip->file_path);
        } elseif ($paiement->pay_slip_admin_id !== null) {
            $filePath = storage_path('app/' . $paiement->paySlipAdmin->file_path);
        }

        if (isset($filePath) && file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return redirect()->back();
        }
    }

    public function destroyPaiement(Paiement $paiement)
    {
        if ($paiement->pay_slip_id !== null) {
            $filePath = storage_path('app/' . $paiement->paySlip->file_path);
        } elseif ($paiement->pay_slip_admin_id !== null) {
            $filePath = storage_path('app/' . $paiement->paySlipAdmin->file_path);
        }

        if (isset($filePath) && file_exists($filePath)) {
            unlink($filePath);
        }
        if ($paiement->pay_slip_id !== null) {
            $paiement->paySlip->delete();
        } elseif ($paiement->pay_slip_admin_id !== null) {
            $paiement->paySlipAdmin->delete();
        }
        $paiement->delete();

        return redirect()->back();
    }

}
