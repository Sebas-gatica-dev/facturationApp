<div class="bg-gray-800 text-white container mx-auto p-6">
  <div class="mb-4">
      <h1 class="text-4xl text-center">Tabla de Historial</h1>
  </div>

  <!-- Contenido específico del historial -->

  <div class="flex justify-start">
      <div class="mr-8">
          <button class="button-animation-1 bg-indigo-500 hover:bg-indigo-700 text-white font-bold px-4  rounded p-3" wire:click="switchView('historial')">Volver al Historial</button>
      </div>
       

      <livewire:historial-table.historial-table />

        
      <!-- ... Otro contenido del historial ... -->
  </div>
</div>
