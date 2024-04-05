<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\PaySlips;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CourseDeposit;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Notifications\NewAdminNotification;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->where(function ($query) {
                $query->where('name', '!=', 'Recuperation')
                    ->orWhere('email', '!=', 'recuperation@gmail.com');
            })->orderBy('created_at', 'desc')->get();
        $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
        return view('Admin.Admin.index', compact('admins', 'courseDeposit'));
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
        return view('Admin.Admin.create', compact('courseDeposit', 'cashInServices'));

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
            'amount' => 'required',

        ], $customMessages);
        $existingAdmin = User::where('name', $data['name'])
            ->orWhere('email', $data['email'])
            ->first();
        if ($existingAdmin) {
            $message = 'Un administrateur avec ce nom ou cette adresse e-mail existe déjà.';
            $request->session()->flash('error_message', $message);
            return redirect()->route('admin.admin.index');
        }
        $password = Hash::make('LesTutorielsAdmin');
        $admin = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $password,
            'phone' => $data['phone'],
            'role' => 'admin',
            'reseau'=> $data['reseau'],
            'poste' => $data['poste'],
            'amount' => $data['amount'],
        ]);
        $admin->notify(new NewAdminNotification($admin));
        $message = 'Nouvel Administrateur enregistré avec succès. Il peut consulter ses emails. ';
        $request->session()->flash('success_message', $message);
        return redirect()->route('admin.admin.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $admin = User::where('role', 'admin')->where('id', $id)->first();
        $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
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

        if (!$admin) {
            $message = 'Administrateur non trouvé.';
            $request->session()->flash('error_message', $message);
            return redirect()->route('admin.admin.index');
        }
        return view('Admin.Admin.show', compact('admin', 'courseDeposit', 'cashInServices'));
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
            'regex' => 'Format de numéro invalide.(ex:77XXXXXX)',

        ];
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => ['required', 'string', 'max:255', 'regex:/^[0-9]{9}$/'],
            'poste' => 'required',
            'reseau' => 'required',
            'amount' => 'required',
        ], $customMessages);
        $admin = User::where('role', 'admin')->find($id);
        if (!$admin) {
            $message = 'Administrateur non trouvé.';
            $request->session()->flash('error_message', $message);

        } else {
            $admin->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'poste' => $data['poste'],
                'reseau' => $data['reseau'],
                'amount' => $data['amount'],

            ]);
            $message = 'Administrateur modifié avec succès';
            $request->session()->flash('success_message', $message);
        }
        return redirect()->route('admin.admin.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = User::where('role', 'admin')->find($id);
        if (!$admin) {
            $message = "La suppression a échouée";
            session()->flash('success_message', $message);
        } else {
            $adminPaySlips = PaySlips::where('admin_id', $admin->id)->get();
            foreach ($adminPaySlips as $adminPaySlip) {
                $filePath = storage_path('app/' . $adminPaySlip->file_path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $adminPaySlip->delete();
            }
            $admin->delete();
            $message = "L'administrateur a été supprimé avec success !";
            session()->flash('success_message', $message);
        }
        return redirect()->back();
    }

}
