<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\User;

class AppointmentCreate extends Component
{
    public $patient_id;
    public $doctor_id;
    public $date;
    public $start_time;
    public $end_time;
    public $reason;

    // Campos de búsqueda
    public $search_date;
    public $search_time;
    public $search_specialty;

    public $selectedDoctor = null;
    public $doctors_list = [];
    public $specialties = [];

    public function mount()
    {
        $this->search_date = date('Y-m-d');
        $this->specialties = Doctor::whereNotNull('specialty')->distinct()->pluck('specialty')->toArray();
        $this->search();
    }

    public function selectDoctorTime($doctorId, $time)
    {
        $this->doctor_id = $doctorId; // User ID
        $this->selectedDoctor = User::role('doctor')->find($doctorId);
        $this->date = $this->search_date;
        $this->start_time = $time;
        
        // Calcular hora fin (+15 min)
        $this->end_time = date('H:i', strtotime($time . ' +15 minutes'));
    }

    public function selectDoctor($doctorId)
    {
        $this->doctor_id = $doctorId; // User ID
        $this->selectedDoctor = User::role('doctor')->find($doctorId);
        $this->date = $this->search_date;
        $this->start_time = $this->search_time ?: '08:00';
        
        // Calcular hora fin (ej. +15 min como en la imagen)
        if ($this->start_time) {
            $this->end_time = date('H:i', strtotime($this->start_time . ' +15 minutes'));
        }
    }

    public function search()
    {
        $this->doctor_id = null;
        $this->selectedDoctor = null;
        
        $dayOfWeek = $this->search_date ? date('N', strtotime($this->search_date)) : null;

        $query = User::role('doctor')->with(['doctor.schedules' => function($q) use ($dayOfWeek) {
            if ($dayOfWeek) {
                $q->where('day_of_week', $dayOfWeek)->orderBy('start_time');
            } else {
                // Si no hay fecha, no cargar horarios para evitar confusión
                $q->whereRaw('1 = 0');
            }
        }]);
        
        if ($this->search_specialty) {
            $query->whereHas('doctor', function($q) {
                $q->where('specialty', $this->search_specialty);
            });
        }

        // Filtrar por disponibilidad si hay fecha
        if ($dayOfWeek) {
            $query->whereHas('doctor.schedules', function($q) use ($dayOfWeek) {
                $q->where('day_of_week', $dayOfWeek);
                
                if ($this->search_time) {
                    $q->where('start_time', $this->search_time);
                }
            });
        }

        $this->doctors_list = $query->get();
    }

    public function save()
    {
        $this->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id', // doctor_id es el ID del Usuario
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'reason' => 'required|string|min:5'
        ]);

        // Asegurar que el usuario tenga un perfil de doctor
        $doctorProfile = Doctor::firstOrCreate(['user_id' => $this->doctor_id]);

        $appointment = Appointment::create([
            'patient_id' => $this->patient_id,
            'doctor_id' => $doctorProfile->id,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'reason' => $this->reason,
            'status' => 1 // 1: Pendiente
        ]);

        // Ejecutar notificaciones de forma síncrona para mostrar el resultado inmediato
        $notificationService = app(\App\Services\NotificationService::class);
        $results = $notificationService->sendAppointmentNotifications($appointment->id);

        $statusMessage = "Cita creada exitosamente.";
        $icon = "success";

        if ($results['patient_email'] && $results['doctor_email']) {
            $statusMessage .= "\n- Correos enviados a paciente y doctor.";
        } else {
            $statusMessage .= "\n- Error al enviar algunos correos.";
            $icon = "warning";
        }

        if ($results['whatsapp']) {
            $statusMessage .= "\n- WhatsApp de confirmación enviado.";
        }

        // Usar swal para mostrar la "ventana" solicitada
        session()->flash('swal', [
            'icon' => $icon,
            'title' => 'Resultado de Notificaciones',
            'text' => $statusMessage,
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        $patients = Patient::with('user')->get();
        return view('livewire.admin.appointment-create', compact('patients'))
            ->layout('layouts.admin', [
                'title' => 'Nueva Cita',
                'breadcrumbs' => [
                    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
                    ['name' => 'Citas Médicas', 'href' => route('admin.appointments.index')],
                    ['name' => 'Nueva Cita']
                ]
            ]);
    }
}
