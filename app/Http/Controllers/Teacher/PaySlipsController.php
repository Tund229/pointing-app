<?php

namespace App\Http\Controllers\Teacher;

use App\Models\PaySlips;
use Illuminate\Http\Request;
use App\Models\CourseDeposit;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PaySlipsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $paySlips = PaySlips::where('user_id', $user_id)->get();
        $courseDeposit = CourseDeposit::where('user_id',$user_id)->where('state', 'rejeté')->count();
        return view('Teacher.PaysSlips.index', compact('paySlips', 'courseDeposit'));
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
}
