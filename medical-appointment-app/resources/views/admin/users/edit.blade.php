<x-admin-layout title="Usuarios" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Usuarios',
        'href' => route('admin.users.index'),
    ],
    [
        'name' => 'Crear',
    ],
]">

    <x-wire-card>
      <x-validation-errors class="mb-4"/>
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
              <div class="grid lg:grid-cols-2 gap-4">
                <x-wire-input
                    label="Nombre"
                    name="name"
                    placeholder="Nombre completo"
                    required
                    :value="old('name', $user->name)"
                />
                
                <x-wire-input
                    label="Correo electronico"
                    name="email"
                    type="email"
                    placeholder="ejemplo@dominio.com"
                    required
                    autocomplete="email"
                    :value="old('email', $user->email)"
                />
                


                <x-wire-input
                    label="Contraseña"
                    name="password"
                    type="password"
                    placeholder="Mínimo 8 caracteres"
                    autocomplete="new-password"
                />

                <x-wire-input
                    label="Confirmar Contraseña"
                    name="password_confirmation"
                    type="password"
                    placeholder="Repita la contraseña"
                    autocomplete="new-password"
                />

                <x-wire-input
                    label="Numero de ID"
                    name="id_number"
                    placeholder="Ej. 123456789"
                    autocomplete="off"
                    required
                    inputmode="numeric"
                    :value="old('id_number', $user->id_number)"
                />

                <x-wire-input
                    label="Telefono"
                    name="phone"
                    placeholder="Ej. 99999999"
                    autocomplete="tel"
                    required
                    inputmode="tel"
                    :value="old('phone', $user->phone)"
                />
                </div>
                <x-wire-input name="address" label="Dirección" required :value="old('address', $user->address)" placeholder="Ej. Calle 90 293" autocomplete="street-address"></x-wire-input>

                <div class="space-y-1">
                  <x-wire-native-select name="role_id" label="Rol" required>
                    <option value="">
                      Seleccione un rol
                    </option>
                  
                  @foreach ($roles as $role)
                  <option value="{{$role->id}}" @selected(old('role_id', $user->roles->first()->id) == $role->id)>
                    {{$role->name}}
                  </option>
                      
                  @endforeach
                </x-wire-native-select>
                <p class="text-sm text-gray-500">
                  Define los permisos y accesos del usuario
                </p>
                </div>

                <div class="flex justify-end">
                    <x-wire-button type="submit" blue>Actualizar</x-wire-button>
                </div>
            </div>
        </form>
    </x-wire-card>

</x-admin-layout>