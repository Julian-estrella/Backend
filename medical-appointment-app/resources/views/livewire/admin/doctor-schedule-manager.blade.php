<div>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gestor de horarios</h2>
            <p class="text-sm text-gray-500">Configura la disponibilidad para el Dr. {{ $user->name }}</p>
        </div>
        <button wire:click="save" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-sm">
            Guardar horario
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 font-bold text-gray-400 uppercase tracking-wider">DÍA/HORA</th>
                        @foreach($days as $id => $name)
                            <th class="px-6 py-4 font-bold text-center">
                                <div class="flex flex-col items-center space-y-2">
                                    <span>{{ $name }}</span>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        // Agrupar slots por hora para la vista
                        $groupedSlots = [];
                        foreach($timeSlots as $slot) {
                            $hour = substr($slot, 0, 2) . ':00';
                            $groupedSlots[$hour][] = $slot;
                        }
                    @endphp

                    @foreach($groupedSlots as $hour => $slots)
                        @php $hourPrefix = substr($hour, 0, 2); @endphp
                        <tr class="bg-white">
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" wire:click="toggleTime('{{ $hourPrefix }}')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                    <span class="font-bold text-gray-700">{{ $hour }}</span>
                                </div>
                            </td>
                            @foreach($days as $dayId => $dayName)
                                <td class="px-6 py-4">
                                    <div class="space-y-2">
                                        <!-- Opción "Todos" para la hora en este día -->
                                        <div class="flex items-center space-x-2 text-gray-600">
                                            <input type="checkbox" wire:click="toggleHourForDay({{ $dayId }}, '{{ $hourPrefix }}')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                            <span class="text-xs font-bold">Todos</span>
                                        </div>
                                        
                                        @foreach($slots as $slot)
                                            <div class="flex items-center space-x-2">
                                                <input type="checkbox" 
                                                       wire:model.defer="selectedSlots.{{ $dayId }}.{{ $slot }}" 
                                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                <span class="text-xs text-gray-600">{{ $slot }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
