@props([
    "client" => null,
    "selectedItems" => null,
    "selectionMode" => null,
])
{{
    dd(
        $client,
        $selectedItems,
        $selectionMode
    );
}}
<tr wire:key="{{ $client['id'] }}" >
    <!-- el id del cliente será la key de cada tr renderizado -->
    @if ($selectionMode)
          <td class="border p-2">
            
                  <input class="m-2" type="checkbox" wire:model="selectedClients" value="{{ $client['id'] }}">
              
          </td>
    @endif

    <td class="border p-2">{{ $client['nro_cliente'] }}</td>
    <td class="border p-2">{{ $client['nombre'] }}</td>

    <td class="border p-2 flex">
        <!-- Botón de Editar -->
        <button 
        @click="console.log('{{ $client['id'] }}'); open = true; $dispatch('editClient', '{{ $client['id'] }}')" 
        style="{{ $selectionMode ? 'background-color: gray; color: white;' : 'background-color: slateblue; color: white;'}}"
        class="w-full md:w-1/2 mb-4 mt-4 py-2 px-4 rounded mr-2 {{ $selectionMode ? 'cursor-not-allowed' : '' }}"      
        {{ $selectionMode ? 'disabled' : '' }}
    >
        Editar
    </button>
    
    


        <!-- Botón de Borrar -->
        <button 
            wire:click="remove({{ $client['id'] }})" 
            x-on:click="$dispatch('itemsHaveBeenUpdated')"
            class="w-full md:w-1/2 mb-4 mt-4  text-white py-2 px-4 rounded {{ $selectionMode ? 'bg-gray-500 cursor-not-allowed' : 'bg-red-700 text-white hover:bg-red-800' }}"
            {{ $selectionMode ? 'disabled' : '' }}
        >
            Borrar
        </button>
    </td>
</tr>