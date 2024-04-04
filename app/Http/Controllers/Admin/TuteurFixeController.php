<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseDeposit;
use App\Models\TuteurFix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TuteurFixeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tuteurFixes = TuteurFix::where('state', true)->orderBy('created_at', 'desc')->get();
        $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
        return view('Admin.TuteursFixe.index', compact('tuteurFixes', 'courseDeposit'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
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
        return view('Admin.TuteursFixe.create', compact('courseDeposit', 'cashInServices'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customMessages = [
            'required' => 'Veuillez remplir ce champ.',
            'regex' => 'Format de numéro invalide.(ex:77 XX XX XX)',
        ];
        $data = $request->validate([
            'name' => 'required',
            'amount' => 'required',
            'phone' => ['required', 'string', 'max:255', 'regex:/^[0-9]{9}$/'],
            'poste' => 'required',
            'reseau' => 'required',
        ], $customMessages);
        $existingTeacher = TuteurFix::where('name', $data['name'])->first();
        if ($existingTeacher) {
            $message = 'Un utilisateur avec ce nom existe déjà.';
            $request->session()->flash('error_message', $message);
            return redirect()->route('admin.tuteurs-fixe.index');
        }
        $teacher = TuteurFix::create([
            'name' => $data['name'],
            'amount' => $data['amount'],
            'phone' => $data['phone'],
            'poste' => $data['poste'],
            'reseau' => $data['reseau'],
        ]);
        $message = 'Nouveau tuteur fixe enregistré avec succès. ';
        $request->session()->flash('success_message', $message);
        return redirect()->route('admin.tuteurs-fixe.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        $teacher = TuteurFix::where('id', $id)->first();
        $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
        if (!$teacher) {
            $message = 'Tuteur non trouvé.';
            $request->session()->flash('error_message', $message);
            return redirect()->route('admin.TuteursFixe.index');
        }
        return view('Admin.TuteursFixe.show', compact('teacher', 'cashInServices', 'courseDeposit'));
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
        $customMessages = [
            'required' => 'Veuillez remplir ce champ.',
            'regex' => 'Format de numéro invalide.(ex:77XXXXXX)',
        ];
        $data = $request->validate([
            'name' => 'required',
            'amount' => 'required',
            'phone' => ['required', 'string', 'max:255', 'regex:/^[0-9]{9}$/'],
            'poste' => 'required',
            'reseau' => 'required',
        ], $customMessages);
        $teacher = TuteurFix::find($id);
        if (!$teacher) {
            $message = 'Tuteur fixe non trouvé.';
            $request->session()->flash('error_message', $message);

        } else {
            $teacher->update([
                'name' => $data['name'],
                'amount' => $data['amount'],
                'phone' => $data['phone'],
                'poste' => $data['poste'],
                'reseau' => $data["reseau"],
            ]);
            $message = 'Tuteur Fixe modifié avec succès';
            $request->session()->flash('success_message', $message);
        }
        return redirect()->route('admin.tuteurs-fixe.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $teacher = TuteurFix::find($id);

        if (!$teacher) {
            $message = 'Tuteur fixe non trouvé.';
            session()->flash('error_message', $message);
        } else {
            $teacher->delete();
            $message = 'Tuteur fixe supprimé avec succès.';
            session()->flash('success_message', $message);
        }

        return redirect()->route('admin.tuteurs-fixe.index');
    }

}
