<div class="flex gap-2">
    @if($appointment->status == 1)
        <!-- Atender -->
        <a href="{{ route('admin.appointments.consultation', $appointment) }}" title="Atender Consulta" class="text-white bg-green-500 hover:bg-green-600 font-medium rounded-lg text-sm px-3 py-2 text-center">
            <i class="fa-solid fa-stethoscope"></i>
        </a>
        <!-- Cancelar -->
        <button wire:click="cancel({{ $appointment->id }})" title="Cancelar Cita" class="text-white bg-orange-500 hover:bg-orange-600 font-medium rounded-lg text-sm px-3 py-2 text-center" onclick="confirm('¿Estás seguro de cancelar esta cita?') || event.stopImmediatePropagation()">
            <i class="fa-solid fa-ban"></i>
        </button>
    @else
        <!-- Ver (Finalizada o Cancelada) -->
        <a href="{{ route('admin.appointments.consultation', $appointment) }}" title="Ver Detalles" class="text-white bg-blue-500 hover:bg-blue-600 font-medium rounded-lg text-sm px-3 py-2 text-center">
            <i class="fa-solid fa-eye"></i>
        </a>
    @endif

    <!-- Eliminar -->
    <button wire:click="delete({{ $appointment->id }})" title="Eliminar Cita" class="text-white bg-red-500 hover:bg-red-600 font-medium rounded-lg text-sm px-3 py-2 text-center" onclick="confirm('¿Estás seguro de eliminar esta cita?') || event.stopImmediatePropagation()">
        <i class="fa-solid fa-trash"></i>
    </button>
</div>
