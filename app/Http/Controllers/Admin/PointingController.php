<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Course;
use App\Models\Pointing;
use App\Models\Promotion;
use Illuminate\Http\Request;
use App\Models\CourseDeposit;
use App\Http\Controllers\Controller;

class PointingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pointings = Pointing::orderBy('state', 'desc') // Tri par état "en attente" en premier
        ->orderBy('created_at', 'desc') // Ensuite, triez par date de création (ou un autre critère)
        ->get();
        $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
        return view('Admin.Pointing.index', compact('pointings', 'courseDeposit'));
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
        $pointing = Pointing::find($id);
        $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
        return view('Admin.Pointing.show', compact('pointing', 'courseDeposit'));
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
        $pointing = Pointing::find($id);
        if (!$pointing) {
            $message = "La suppression a échouée";
            session()->flash('success_message', $message);
        } else {
            if($pointing->state == 'validé') {
                $user = $pointing->user;
                $startHours = Carbon::parse($pointing->start_time);
                $endHours = Carbon::parse($pointing->end_time);
                $difference = $endHours->diff($startHours);
                $diffHours = $difference->h;
                $diffMinutes = $difference->i;
                if($diffMinutes >= 40) {
                    $amount = ($diffHours * $pointing->course->price_per_hour) + ($pointing->course->price_per_hour / 2);
                    $diffHours = $diffHours + 1;
                } else {
                    $amount = $diffHours * $pointing->course->price_per_hour;
                }
                $user->update([
                    'amount' => $user->amount - $amount,
                    'total_hours' => $user->total_hours - $diffHours
                ]);
                $pointing->delete();
            } else {
                $pointing->delete();
            }
            $message = "Le pointage a été supprimé avec success !";
            session()->flash('success_message', $message);
        }
        return redirect()->back();
    }


    public function valider(Request $request, $id)
    {
        $pointing = Pointing::find($id);
        if ($pointing) {
            if($pointing->state != "validé" ){

                $motif = $request->input('reason');
                $startHours = Carbon::parse($pointing->start_time);
                $endHours = Carbon::parse($pointing->end_time);
                $difference = $endHours->diff($startHours);
                $diffHours = $difference->h;
                $diffMinutes = $difference->i;
                if($diffMinutes >= 30 && $diffMinutes < 39) {   
                    $amount = ($diffHours * $pointing->course->price_per_hour) + ($pointing->course->price_per_hour / 2);
                    $diffHours = $diffHours;
                }elseif($diffMinutes >= 39){
                    $diffHours = $diffHours +1;
                    $amount = $diffHours * $pointing->course->price_per_hour;
                }else{
                    $amount = $diffHours * $pointing->course->price_per_hour;
                }           
                // Enregistrer le montant chez le user_id
                $user = $pointing->user;
                $user->amount += $amount;
                $user->total_hours += $diffHours;
                $user->save();
    
                $pointing->update([
                    'state' => 'validé',
                ]);
                $message = 'Pointage validé avec succès!';
                $request->session()->flash('success_message', $message);
            }
        } else {
            $message = 'Erreur de validation';
            $request->session()->flash('error_message', $message);
        }

        return redirect()->route('admin.pointing.index');

    }


    public function refuser(Request $request, $id)
    {
        $pointing = Pointing::find($id);

        if ($pointing) {
            $motif = $request->input('reason');

            // Si l'état est "validé", annuler la validation en soustrayant le montant et les heures
            if($pointing->state === 'validé') {
                $user = $pointing->user;
                $startHours = Carbon::parse($pointing->start_time);
                $endHours = Carbon::parse($pointing->end_time);
                $difference = $endHours->diff($startHours);
                $diffHours = $difference->h;
                $diffMinutes = $difference->i;
                if($diffMinutes >= 40) {
                    $amount = ($diffHours * $pointing->course->price_per_hour) + ($pointing->course->price_per_hour / 2);
                    $diffHours = $diffHours + 1;
                } else {
                    $amount = $diffHours * $pointing->course->price_per_hour;
                }
                $user->update([
                    'amount' => $user->amount - $amount,
                    'total_hours' => $user->total_hours - $diffHours
                ]);
                $pointing->update([
                    'state' => 'rejeté',
                    'reason' => $motif
                ]);
            } else {
                $pointing->update([
                    'state' => 'rejeté',
                    'reason' => $motif
                ]);
            }
            $message = 'Pointage rejeté avec succès!';
            $request->session()->flash('success_message', $message);
        } else {
            $message = 'Erreur de refus';
            $request->session()->flash('error_message', $message);
        }
        return redirect()->route('admin.pointing.index');
    }



    public function deleteAll()
    {
        Pointing::truncate();
        $users = User::all();
        foreach ($users as $user) {
            $user->update(['amount' => 0, 'total_hours' => 0]);
        }
        $message = 'Tous les pointages supprimés avec succès';
        session()->flash('success_message', $message);
        return redirect()->route('admin.pointing.index');
    }
}
