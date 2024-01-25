<div 
class="bg-gray-800 text-white container mx-auto p-6"
>
    <div class="mb-4">
        <h1 class="text-4xl text-center">Crear Alta Facturacion</h1>
    </div>

    <!-- Contenido específico del historial -->

    <div class="flex justify-between items-center mb-4">
        <div>
            <button class="button-animation-1 bg-indigo-500 hover:bg-indigo-700 text-white font-bold px-4  rounded p-3" wire:click="switchView('dashboard')">Cancelar proceso</button>
        </div>

        <div>
            <button class="button-animation-1 bg-indigo-500 hover:bg-indigo-700 text-white font-bold px-4  rounded p-3" wire:click="switchView('alta-facturacion-facturaciones')">Ir a Tabla de Facturaciones</button>
        </div>
    </div>

    <div class="text-black">    
     
            <livewire:client-table.client-table />
       
      
    </div>
</div>
