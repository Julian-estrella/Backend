<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Doctor;
use App\Models\DoctorSchedule;

class DoctorScheduleManager extends Component
{
    public $user; // User model with role doctor
    public $doctor; // Doctor profile
    public $selectedSlots = []; // [day][time] = true
    
    public $days = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
        7 => 'Domingo'
    ];

    public function mount(User $doctor)
    {
        $this->user = $doctor;
        // Asegurar que tenga perfil de doctor
        $this->doctor = Doctor::firstOrCreate(['user_id' => $this->user->id]);
        
        $this->loadSchedules();
    }

    public function loadSchedules()
    {
        $schedules = DoctorSchedule::where('doctor_id', $this->doctor->id)->get();
        foreach ($schedules as $schedule) {
            $time = substr($schedule->start_time, 0, 5); // HH:mm
            $this->selectedSlots[$schedule->day_of_week][$time] = true;
        }
    }

    public function toggleHourForDay($dayId, $hour)
    {
        $slots = [
            $hour . ':00',
            $hour . ':15',
            $hour . ':30',
            $hour . ':45',
        ];

        $allSelected = true;
        foreach ($slots as $time) {
            if (!isset($this->selectedSlots[$dayId][$time]) || !$this->selectedSlots[$dayId][$time]) {
                $allSelected = false;
                break;
            }
        }

        foreach ($slots as $time) {
            $this->selectedSlots[$dayId][$time] = !$allSelected;
        }
    }

    public function toggleTime($time)
    {
        $allSelected = true;
        foreach ($this->days as $dayId => $name) {
            $slots = [
                $time . ':00',
                $time . ':15',
                $time . ':30',
                $time . ':45',
            ];

            foreach ($slots as $slot) {
                if (!isset($this->selectedSlots[$dayId][$slot]) || !$this->selectedSlots[$dayId][$slot]) {
                    $allSelected = false;
                    break 2;
                }
            }
        }

        foreach ($this->days as $dayId => $name) {
            $slots = [
                $time . ':00',
                $time . ':15',
                $time . ':30',
                $time . ':45',
            ];

            foreach ($slots as $slot) {
                $this->selectedSlots[$dayId][$slot] = !$allSelected;
            }
        }
    }

    public function save()
    {
        DoctorSchedule::where('doctor_id', $this->doctor->id)->delete();

        foreach ($this->selectedSlots as $day => $times) {
            foreach ($times as $time => $selected) {
                if ($selected) {
                    DoctorSchedule::create([
                        'doctor_id' => $this->doctor->id,
                        'day_of_week' => $day,
                        'start_time' => $time,
                        'end_time' => date('H:i', strtotime($time . ' +15 minutes'))
                    ]);
                }
            }
        }

        session()->flash('message', 'Horarios guardados exitosamente.');
    }

    public function render()
    {
        // Generar slots de 08:00 a 20:00 cada 15 min
        $timeSlots = [];
        $start = strtotime('08:00');
        $end = strtotime('20:00');
        
        while ($start < $end) {
            $timeSlots[] = date('H:i', $start);
            $start = strtotime('+15 minutes', $start);
        }

        return view('livewire.admin.doctor-schedule-manager', compact('timeSlots'))
            ->layout('layouts.admin', [
                'title' => 'Gestor de Horarios',
                'breadcrumbs' => [
                    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
                    ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
                    ['name' => 'Horarios']
                ]
            ]);
    }
}
