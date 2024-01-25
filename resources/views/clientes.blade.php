<x-app-layout>
  <x-slot name="background-black header">
      <h2 class="background-black font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Clientes ClientUp') }}
      </h2>
  </x-slot>

  <div class="background-black py-12">
      <div class="background-black-2 max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="container-animation background-black bg-white overflow-hidden shadow-xl sm:rounded-lg">
              <livewire:client-table.client-table />
          </div>
      </div>
  </div>
</x-app-layout>
