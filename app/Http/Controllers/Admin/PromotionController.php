<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseDeposit;
use App\Models\Pointing;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $courses = Promotion::all();
        $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
        return view('Admin.Promotions.index', compact('courses', 'courseDeposit'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courseDeposit = CourseDeposit::where('state', 'en attente')->count();
        return view('Admin.Promotions.create', compact('courseDeposit'));
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
        ], $customMessages);
        $existingPromotion = Promotion::where('name', $data['name'])->first();
        if ($existingPromotion) {
            $message = 'Cette Promotion existe déjà';
            $request->session()->flash('error_message', $message);
            return redirect()->route('admin.promotion.index');
        }
        $teacher = Promotion::create([
            'name' => $data['name'],
        ]);
        $message = 'Nouvelle promotion enregistrée avec succès.';
        $request->session()->flash('success_message', $message);
        return redirect()->route('admin.promotion.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $promotion = Promotion::find($id);
        if (!$promotion) {
            $message = 'Promotion non trouvée.';
            $request->session()->flash('error_message', $message);
            return redirect()->route('admin.promotion.index');
        }
        return view('Admin.Promotions.show', compact('promotion'));
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
            'name' => 'required',
        ], $customMessages);
        $promotion = Promotion::find($id);
        if (!$promotion) {
            $message = 'Promotion non trouvée.';
            $request->session()->flash('error_message', $message);

        } else {
            $promotion->update([
                'name' => $data['name'],
            ]);
            $message = 'Promotion modifiée avec succès';
            $request->session()->flash('success_message', $message);
        }
        return redirect()->route('admin.promotion.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $promotion = Promotion::find($id);
        if (!$promotion) {
            $message = "La suppression a échoué";
            session()->flash('success_message', $message);
        } else {
            $pointings = Pointing::where('promotion_id', $promotion->id)->get();
            foreach ($pointings as $pointing) {
                $pointing->delete();
            }

            $promotion->courseDeposits()->delete();

            $promotion->delete();

            $message = "La promotion a été supprimée avec succès !";
            session()->flash('success_message', $message);
        }
        return redirect()->back();
    }

}
