<x-admin-layout title="Editar Perfil de Doctor" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
    ['name' => 'Editar Perfil']
]">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-bold mb-6">Información Profesional: {{ $doctor->name }}</h3>
                
                <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label for="specialty" class="block text-sm font-medium text-gray-700">Especialidad</label>
                            <select name="specialty" id="specialty" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Seleccione una especialidad</option>
                                <option value="Medicina Interna" {{ old('specialty', $profile->specialty) == 'Medicina Interna' ? 'selected' : '' }}>Medicina Interna</option>
                                <option value="Pediatría" {{ old('specialty', $profile->specialty) == 'Pediatría' ? 'selected' : '' }}>Pediatría</option>
                                <option value="Ginecología y Obstetricia" {{ old('specialty', $profile->specialty) == 'Ginecología y Obstetricia' ? 'selected' : '' }}>Ginecología y Obstetricia</option>
                                <option value="Medicina Familiar/General" {{ old('specialty', $profile->specialty) == 'Medicina Familiar/General' ? 'selected' : '' }}>Medicina Familiar/General</option>
                                <option value="Cirugía General" {{ old('specialty', $profile->specialty) == 'Cirugía General' ? 'selected' : '' }}>Cirugía General</option>
                                <option value="Dermatología" {{ old('specialty', $profile->specialty) == 'Dermatología' ? 'selected' : '' }}>Dermatología</option>
                                <option value="Cardiología" {{ old('specialty', $profile->specialty) == 'Cardiología' ? 'selected' : '' }}>Cardiología</option>
                                <option value="Anestesiología" {{ old('specialty', $profile->specialty) == 'Anestesiología' ? 'selected' : '' }}>Anestesiología</option>
                                <option value="Traumatología y Ortopedia" {{ old('specialty', $profile->specialty) == 'Traumatología y Ortopedia' ? 'selected' : '' }}>Traumatología y Ortopedia</option>
                                <option value="Psiquiatría" {{ old('specialty', $profile->specialty) == 'Psiquiatría' ? 'selected' : '' }}>Psiquiatría</option>
                                <option value="Oftalmología" {{ old('specialty', $profile->specialty) == 'Oftalmología' ? 'selected' : '' }}>Oftalmología</option>
                                <option value="Urología" {{ old('specialty', $profile->specialty) == 'Urología' ? 'selected' : '' }}>Urología</option>
                                <option value="Neurología" {{ old('specialty', $profile->specialty) == 'Neurología' ? 'selected' : '' }}>Neurología</option>
                                <option value="Endocrinología" {{ old('specialty', $profile->specialty) == 'Endocrinología' ? 'selected' : '' }}>Endocrinología</option>
                            </select>
                            @error('specialty') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="license_number" class="block text-sm font-medium text-gray-700">Número de Cédula/Licencia</label>
                            <input type="text" name="license_number" id="license_number" value="{{ old('license_number', $profile->license_number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Ej. 12345678">
                            @error('license_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-2">
                        <a href="{{ route('admin.doctors.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded shadow">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
