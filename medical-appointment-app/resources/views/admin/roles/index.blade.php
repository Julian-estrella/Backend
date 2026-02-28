<x-admin-layout title="Roles" :breadcrumbs="[
[
  'name' => 'Dashboard',
  'route' => route('admin.dashboard'),
],
[
  'name' => 'Roles',
  'href'=> route('admin.roles.index')
],
[
  'name'=> 'Roles',
],

]">

  <x-slot name="action">
    <x-wire-button blue href="{{route('admin.roles.create')}}">
        <i class="fa-solid fa-plus"></i>
        Nuevo
    </x-wire-button>
  </x-slot>

@livewire('admin.datatables.role-table')
  
</x-admin-layout>