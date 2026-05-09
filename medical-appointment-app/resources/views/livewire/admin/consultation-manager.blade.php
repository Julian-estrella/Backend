<div>
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-800">
            Paciente: {{ $appointment->patient->user->name ?? 'N/A' }} 
            <span class="text-sm font-normal text-gray-500">({{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }})</span>
        </h2>
        <div class="flex gap-2">
            <button wire:click="toggleMedicalHistory" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow">
                <i class="fa-solid fa-file-medical mr-2"></i> Ver Historia
            </button>
            <button wire:click="$set('showHistoryModal', true)" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
                <i class="fa-solid fa-clock-rotate-left mr-2"></i> Consultas Anteriores
            </button>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        @if($appointment->status != 3)
        <div class="border-b border-gray-200">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
                <li class="me-2">
                    <button wire:click="$set('activeTab', 'consulta')" class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group {{ $activeTab === 'consulta' ? 'text-blue-600 border-blue-600 active' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                        <i class="fa-solid fa-user-doctor mr-2 {{ $activeTab === 'consulta' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Consulta
                    </button>
                </li>
                <li class="me-2">
                    <button wire:click="$set('activeTab', 'receta')" class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group {{ $activeTab === 'receta' ? 'text-blue-600 border-blue-600 active' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                        <i class="fa-solid fa-prescription-bottle-medical mr-2 {{ $activeTab === 'receta' ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Receta
                    </button>
                </li>
            </ul>
        </div>
        @endif

        <div class="p-6 text-gray-900">
            @if($appointment->status == 3)
                <div class="bg-red-50 border-l-4 border-red-400 p-6 rounded-lg text-center">
                    <div class="flex items-center justify-center mb-4">
                        <i class="fa-solid fa-circle-xmark text-red-500 text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-red-800 mb-2">Esta cita ha sido cancelada</h3>
                    <p class="text-gray-600 mb-4">La cita programada para el {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }} fue cancelada oficialmente el:</p>
                    <div class="inline-block bg-white px-6 py-3 rounded-full shadow-sm border border-red-100">
                        <span class="text-lg font-bold text-red-600">
                            {{ $appointment->cancelled_at ? \Carbon\Carbon::parse($appointment->cancelled_at)->format('d/m/Y - h:i A') : 'Fecha no registrada' }}
                        </span>
                    </div>
                </div>
            @elseif($activeTab === 'consulta')
                <div class="space-y-4">
                    <div>
                        <label for="diagnosis" class="block text-sm font-medium text-gray-700">Diagnóstico <span class="text-red-500">*</span></label>
                        <textarea wire:model="diagnosis" id="diagnosis" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        @error('diagnosis') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="treatment" class="block text-sm font-medium text-gray-700">Tratamiento <span class="text-red-500">*</span></label>
                        <textarea wire:model="treatment" id="treatment" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        @error('treatment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notas Adicionales</label>
                        <textarea wire:model="notes" id="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    </div>
                </div>
            @elseif($activeTab === 'receta')
                <div class="space-y-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-800">Medicamentos</h3>
                        <button wire:click="addMedicine" type="button" class="text-sm bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded shadow">
                            <i class="fa-solid fa-plus"></i> Añadir Medicamento
                        </button>
                    </div>

                    @foreach($medicines as $index => $medicine)
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end p-4 bg-gray-50 rounded-lg relative">
                            <div class="md:col-span-4">
                                <label class="block text-xs font-medium text-gray-700">Nombre del Medicamento</label>
                                <input wire:model="medicines.{{ $index }}.name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('medicines.' . $index . '.name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-xs font-medium text-gray-700">Dosis</label>
                                <input wire:model="medicines.{{ $index }}.dosage" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Ej. 1 tableta">
                            </div>
                            <div class="md:col-span-4">
                                <label class="block text-xs font-medium text-gray-700">Frecuencia / Duración</label>
                                <input wire:model="medicines.{{ $index }}.instructions" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Ej. Cada 8 horas por 7 días">
                            </div>
                            <div class="md:col-span-1 text-right">
                                <button wire:click="removeMedicine({{ $index }})" type="button" class="text-red-500 hover:text-red-700" title="Eliminar">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-8 flex justify-end">
                <a href="{{ route('admin.appointments.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded shadow mr-2">
                    Volver
                </a>
                @if($appointment->status != 3)
                <button type="button" wire:click="save" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                    <i class="fa-solid fa-save mr-2"></i> {{ $appointment->status == 2 ? 'Guardar Cambios' : 'Finalizar Consulta' }}
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Historial Clínico -->
    @if($showHistoryModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
            <div class="relative w-full max-w-3xl max-h-full p-4">
                <div class="relative bg-white rounded-lg shadow">
                    <!-- Header -->
                    <div class="flex items-start justify-between p-4 border-b rounded-t">
                        <h3 class="text-xl font-semibold text-gray-900">
                            Historial Clínico - {{ $appointment->patient->user->name ?? 'Paciente' }}
                        </h3>
                        <button wire:click="$set('showHistoryModal', false)" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <!-- Body -->
                    <div class="p-6 space-y-6 overflow-y-auto max-h-[60vh]">
                        @forelse($pastAppointments as $past)
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($past->date)->format('d/m/Y') }}</span>
                                    <span class="text-sm text-gray-500">Dr. {{ $past->doctor->user->name ?? 'N/A' }}</span>
                                </div>
                                <div class="text-sm text-gray-700">
                                    <p><strong>Motivo:</strong> {{ $past->reason ?: 'No especificado' }}</p>
                                    <p class="mt-1"><strong>Diagnóstico:</strong> {{ $past->diagnosis ?: 'Sin diagnóstico' }}</p>
                                    <p class="mt-1"><strong>Tratamiento:</strong> {{ $past->treatment ?: 'Sin tratamiento' }}</p>
                                    @if($past->notes)
                                        <p class="mt-1"><strong>Notas:</strong> {{ $past->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500">No hay consultas anteriores para este paciente.</p>
                        @endforelse
                    </div>
                    <!-- Footer -->
                    <div class="flex items-center justify-end p-4 border-t border-gray-200 rounded-b">
                        <button wire:click="$set('showHistoryModal', false)" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Historia Médica del Paciente -->
    @if($showMedicalHistoryModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
            <div class="relative w-full max-w-2xl max-h-full p-4">
                <div class="relative bg-white rounded-lg shadow">
                    <!-- Header -->
                    <div class="flex items-start justify-between p-4 border-b rounded-t">
                        <h3 class="text-xl font-semibold text-gray-900">
                            Historia médica del paciente
                        </h3>
                        <button wire:click="toggleMedicalHistory" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <!-- Body -->
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-xs font-bold uppercase text-gray-500">Tipo de sangre:</h4>
                                <p class="text-sm text-gray-800">{{ $appointment->patient->bloodType->name ?? 'No registrado' }}</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold uppercase text-gray-500">Alergias:</h4>
                                <p class="text-sm text-gray-800">{{ $appointment->patient->allergies ?: 'No registradas' }}</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold uppercase text-gray-500">Enfermedades crónicas:</h4>
                                <p class="text-sm text-gray-800">{{ $appointment->patient->chronic_conditions ?: 'No registradas' }}</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold uppercase text-gray-500">Antecedentes quirúrgicos:</h4>
                                <p class="text-sm text-gray-800">{{ $appointment->patient->surgical_history ?: 'No registrados' }}</p>
                            </div>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                            <a href="{{ route('admin.patients.edit', $appointment->patient) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Ver / Editar Historia Médica
                            </a>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="flex items-center justify-end p-4 border-t border-gray-200 rounded-b">
                        <button wire:click="toggleMedicalHistory" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
