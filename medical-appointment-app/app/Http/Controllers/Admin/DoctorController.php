<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = \App\Models\User::role('doctor')->with('doctor')->paginate(10);
        return view('admin.doctors.index', compact('doctors'));
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
    public function edit(\App\Models\User $doctor)
    {
        $profile = \App\Models\Doctor::firstOrCreate(['user_id' => $doctor->id]);
        return view('admin.doctors.edit', compact('doctor', 'profile'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\User $doctor)
    {
        $request->validate([
            'specialty' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
        ]);

        $profile = \App\Models\Doctor::updateOrCreate(
            ['user_id' => $doctor->id],
            [
                'specialty' => $request->specialty,
                'license_number' => $request->license_number,
            ]
        );

        return redirect()->route('admin.doctors.index')->with('message', 'Perfil actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
