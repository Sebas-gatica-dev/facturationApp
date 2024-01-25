<x-app-layout>
  {{-- <x-slot name="header">
      <h2 class="background-black font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Clientes por Facturar') }}
      </h2>
  </x-slot> --}}

  <div class=" py-12">
      <div class=" max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="container-animation background-black-2 bg-white overflow-hidden shadow-xl sm:rounded-lg">
              <livewire:facturation-table.facturation-table />
          </div>
      </div>
  </div>
</x-app-layout>
