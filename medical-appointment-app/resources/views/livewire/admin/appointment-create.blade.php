<div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Parte Izquierda: Búsqueda y Resultados -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Buscar disponibilidad -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Buscar disponibilidad</h3>
                <p class="text-sm text-gray-500 mb-4">Encuentra el horario perfecto para tu cita.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Fecha</label>
                        <input wire:model="search_date" type="date" class="w-full rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Hora</label>
                        <select wire:model="search_time" class="w-full rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Cualquier hora</option>
                            <option value="08:00">08:00 AM</option>
                            <option value="09:00">09:00 AM</option>
                            <option value="10:00">10:00 AM</option>
                            <option value="11:00">11:00 AM</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Especialidad (opcional)</label>
                        <select wire:model="search_specialty" class="w-full rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todas las especialidades</option>
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty }}">{{ $specialty }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button wire:click="search" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-sm transition-colors text-sm">
                            Buscar disponibilidad
                        </button>
                    </div>
                </div>
            </div>

            <!-- Listado de Doctores -->
            <div class="space-y-4">
                @foreach($doctors_list as $doctor)
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-start space-x-4 {{ $doctor_id == $doctor->id ? 'ring-2 ring-blue-500' : '' }}">
                        <div class="bg-blue-50 text-blue-600 rounded-full w-12 h-12 flex items-center justify-center font-bold text-lg shrink-0">
                            {{ substr($doctor->name, 0, 2) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="text-lg font-bold text-gray-800">{{ $doctor->name }}</h4>
                            <p class="text-sm text-blue-600">{{ $doctor->doctor->specialty ?? 'Medicina General' }}</p>
                            
                            <div class="mt-4">
                                <p class="text-xs font-bold text-gray-500 uppercase mb-2">Horarios disponibles:</p>
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        // Asegurar que los horarios sean únicos para el día seleccionado
                                        $uniqueSchedules = $doctor->doctor->schedules->unique('start_time')->sortBy('start_time');
                                    @endphp
                                    @forelse($uniqueSchedules as $schedule)
                                        @php $slotTime = substr($schedule->start_time, 0, 5); @endphp
                                        <button wire:click="selectDoctorTime({{ $doctor->id }}, '{{ $slotTime }}')" class="px-4 py-2 rounded-lg text-sm font-medium {{ ($doctor_id == $doctor->id && $start_time == $slotTime) ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-700 hover:bg-blue-200' }}">
                                            {{ $slotTime }}
                                        </button>
                                    @empty
                                        <span class="text-xs text-red-400">Sin horarios disponibles para este criterio</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Parte Derecha: Resumen -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 sticky top-6">
                <h3 class="text-lg font-bold text-gray-800 mb-6">Resumen de la cita</h3>
                
                <div class="space-y-4 mb-8">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Doctor:</span>
                        <span class="text-sm font-bold text-gray-800">{{ $selectedDoctor ? $selectedDoctor->name : '--' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Fecha:</span>
                        <span class="text-sm font-bold text-gray-800">{{ $date ?: '--' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Horario:</span>
                        <span class="text-sm font-bold text-gray-800">{{ $start_time ? $start_time . ' - ' . $end_time : '--' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Duración:</span>
                        <span class="text-sm font-bold text-gray-800">15 minutos</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Paciente</label>
                        <select wire:model="patient_id" class="w-full rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecciona un paciente</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->user->name }}</option>
                            @endforeach
                        </select>
                        @error('patient_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Motivo de la cita</label>
                        <textarea wire:model="reason" rows="3" placeholder="Ej. Chequeo de medicamentos" class="w-full rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                        @error('reason') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <button wire:click="save" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-sm transition-colors mt-4">
                        Confirmar cita
                    </button>
                    
                    <a href="{{ route('admin.appointments.index') }}" class="block text-center text-sm text-gray-500 hover:text-gray-700 mt-2">
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
