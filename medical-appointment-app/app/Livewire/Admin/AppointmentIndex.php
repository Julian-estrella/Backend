<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use Livewire\WithPagination;
use App\Models\Appointment;

class AppointmentIndex extends Component
{
    use WithPagination;

    public function delete($id)
    {
        Appointment::find($id)->delete();
        session()->flash('message', 'Cita eliminada correctamente.');
    }

    public function cancel($id)
    {
        $appointment = Appointment::find($id);
        $appointment->status = 3; // Cancelada
        $appointment->cancelled_at = now();
        $appointment->save();
        session()->flash('message', 'Cita cancelada correctamente.');
    }

    public function render()
    {
        $appointments = Appointment::with(['patient.user', 'doctor.user'])->latest()->paginate(10);
        return view('livewire.admin.appointment-index', compact('appointments'))
            ->layout('layouts.admin', [
                'title' => 'Citas Médicas',
                'breadcrumbs' => [
                    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
                    ['name' => 'Citas Médicas']
                ]
            ]);
    }
}
