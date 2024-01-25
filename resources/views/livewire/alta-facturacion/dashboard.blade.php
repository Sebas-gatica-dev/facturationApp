<div class="bg-gray-800 text-white container mx-auto p-6">

  <div class="mb-4">
      <h1 class="text-4xl text-center">Alta Facturación</h1>
  </div>

  <div class="flex justify-center items-center">
      <div class="mr-8">
          <a href="#" wire:click="iniciarProceso">
              <i class="fas fa-plus-circle text-5xl text-green-500"></i>
              <p class="text-center mt-2">Crear Alta</p>
          </a>
      </div>

      <div>
          <a href="#" wire:click="switchView('historial')">
              <i class="fas fa-history text-5xl text-blue-500"></i>
              <p class="text-center mt-2">Ver Historial</p>
          </a>
      </div>
  </div>
</div>
