<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use App\Models\Appointment;

class ConsultationManager extends Component
{
    public Appointment $appointment;
    public $activeTab = 'consulta';
    public $showHistoryModal = false;
    public $showMedicalHistoryModal = false;

    public $diagnosis = '';
    public $treatment = '';
    public $notes = '';
    public $medicines = [];

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment->load(['patient.user', 'patient.bloodType']);
        
        if ($this->appointment->status == 2) {
            $this->diagnosis = $this->appointment->diagnosis;
            $this->treatment = $this->appointment->treatment;
            $this->notes = $this->appointment->notes;
            $this->medicines = $this->appointment->prescription ?: [];
        } else {
            // Inicializar un medicamento por defecto
            $this->addMedicine();
        }
    }

    public function addMedicine()
    {
        $this->medicines[] = ['name' => '', 'dosage' => '', 'instructions' => ''];
    }

    public function removeMedicine($index)
    {
        unset($this->medicines[$index]);
        $this->medicines = array_values($this->medicines);
    }

    public function save()
    {
        $this->validate([
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'notes' => 'nullable|string',
            'medicines.*.name' => 'required|string',
        ]);

        $this->appointment->update([
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'notes' => $this->notes,
            'prescription' => $this->medicines,
            'status' => 2, // 2: Completada
        ]);

        session()->flash('message', 'Consulta guardada exitosamente.');

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        $pastAppointments = Appointment::where('patient_id', $this->appointment->patient_id)
            ->where('id', '!=', $this->appointment->id)
            ->where('status', 2) // Asumiendo 2 es Completada
            ->with('doctor.user')
            ->latest()
            ->get();

        return view('livewire.admin.consultation-manager', compact('pastAppointments'))
            ->layout('layouts.admin', [
                'title' => 'Atención Médica',
                'breadcrumbs' => [
                    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
                    ['name' => 'Citas Médicas', 'href' => route('admin.appointments.index')],
                    ['name' => 'Consulta']
                ]
            ]);
    }

    public function toggleMedicalHistory()
    {
        $this->showMedicalHistoryModal = !$this->showMedicalHistoryModal;
    }
}
