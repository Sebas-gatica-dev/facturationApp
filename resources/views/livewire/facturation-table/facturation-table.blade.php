<div class="background-black-1 container mx-auto p-4 relative" 

x-data="{
     openCreateModal: @entangle('showCreateModal'),
     openUpdateModal: @entangle('showUpdateModal'),
     openAdvertenciaModal: @entangle('showAdvertenciaModal'),
     openNullModuloModal: @entangle('showNullModuloModal'), 
    }">

   

    <div class="mb-4">

        <form wire:submit.prevent="search" class="flex">

            <input type="text" wire:model.lazy="searchTerm" class="w-full rounded">

            <select wire:model="searchParam" class="mx-10 px-3 rounded">

                <option value="nombre">Nombre</option>
                <option value="nro_cliente">Número de Cliente</option>

            </select>

            <button
            class="button-animation-1 bg-indigo-500 hover:bg-indigo-700 text-white font-bold px-4  rounded p-3"
            type="submit"
            data-tippy-content="Iniciar Búsqueda"
            data-tippy-duration="3000"
            >
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>



        </form>

        <div class="mb-2 flex justify-start">
            {{-- <button
            class="button-animation-1 m-4 bg-indigo-500 hover:bg-indigo-700 text-white font-bold px-4  rounded p-3"
             wire:click="decemberCheckpoint"
            
            >
                Checkpoint December
            </button> --}}

            <button
            class="button-animation-1 m-4 bg-indigo-500 hover:bg-indigo-700 text-white font-bold px-4  rounded p-3"
              wire:click="openAndCloseCreateModal"
            >
            <i class="fa-solid fa-plus"></i>           
        
        </button>



        </div>    


        
        {{-- <button
        wire:click="darAltaFinal"
        class="button-animation-1 bg-green-500 hover:bg-green-700 mt-5 text-white font-bold py-3 px-3 rounded absolute">
        <i
        class="fa-solid fa-file-lines"        
        style="font-size: 2rem;"
        ></i>
        </button> --}}

        <div class="text-xl font-semibold text-gray-700 mt-6 py-3 px-3 rounded absolute">
            <i class="fa-regular fa-calendar-alt"></i> {{ now()->format('F Y') }}
        </div>


        
        @if ($searchMode)
            <button
             wire:click="cancelSearch"
             class="button-animation-1 bg-red-500 hover:bg-red-700 mt-5 text-white font-bold py-3 px-3 mr-4 rounded absolute left-36"
             style="font-size: 1.45rem; margin-left:2.3em;"
             >
             <i class="fa-solid fa-xmark"></i>
             </button>


        @endif

    </div>

      
           

   



    <div class="mb-2 flex justify-end">

     
        

        @if (count($clients) >= 2)
        {{-- Este botón habilita o deshabilita el modo de selección múltiple --}}
        <button wire:click="toggleSelectionMode"
            class="button-animation-1 bg-indigo-500 hover:bg-indigo-700 w-40 mt-4 mb-4 ml-4
            text-white py-2 px-4 rounded">
            @if ($selectionMode)
                Cancelar
            @else
            <i class="fa-solid fa-list"></i>
            @endif
        </button>
    @endif
    
    
        @if ($selectionMode)

            {{-- Este boton borra los elementos seleccionados y solo es visible el modo de
                seleccion esta activado, si no hay ningun elemento seleccionado y se apreta este boton,
                 simplemente cancelara la operacion
                --}}

            <button
            wire:click="remove"
            x-on:click="$dispatch('itemsHaveBeenUpdated')"
            class="button-animation-1 scale w-40 mt-4 mb-4 ml-4 bg-red-700 text-white py-2 px-4 rounded hover:bg-red-800"
            >
            <i class="fa-solid fa-trash"></i>
            </button>

        @endif
        
        {{-- Este span se encarga de mostrar la cantiodad de elementos que fueroon agregados a facturacion --}}

        <span class="w-40 mt-4 mb-4 ml-4 bg-gray-800 text-white py-2 pl-8 rounded">

            @if($searchMode)

                {{$totalResultsElements}} elementos

            @elseif(!$searchMode)

            
                {{ $totalElements }} elementos


            @endif
        
        </span>
    
    </div>


    @if (count($clients) === 0)

    {{-- Mensaje en caso de que no haya facturaciones pendientes --}}
    <div class="bg-white p-6 rounded shadow">

        <p class="text-center text-gray-700 mb-3">No hay clientes por facturar</p>
        {{-- <p class="text-center"><a href="{{ route('dashboard') }}" class="text-blue-500 underline">indexar aquí</a></p> --}}

    </div>

    
    

    @elseif (count($clients) > 0)

            <table class="mb-4 min-w-full bg-white ">

                <thead>

                    <tr
                    class="background-light-black "

                    >

                        @if ($selectionMode)
                            <th class="border "></th>
                        @endif
                        <th class="text-white  p-2">Nro Cliente</th>
                        <th class="text-white  p-2">Razon Social</th>
                        <th class="text-white  p-2">Database</th>
                        <th class="text-white  p-2">Modulo </th>                       
                        <th class="text-white  p-2">Acciones</th>

                    </tr>

                </thead>
                <tbody>
                    {{-- {{
                        dd($clientsPaginated->groupBy('nro_cliente'));
                    }}
                         --}}





                @if($searchMode && $clientsResults)
                       
                {{-- {{dd($clientsResults);}} --}}
              
                @foreach ($clientsResults->groupBy('nro_cliente') as $nroCliente => $groupedClients)
                      
                
                
                
               
                @foreach ($groupedClients as $index => $client)

                {{-- {{dd($groupedClients, $client['id']);}} --}}



            <tr wire:key="{{ $client['id'] }}" class="hover-bg-gray-200">
                <!-- el id del cliente será la key de cada tr renderizado -->
                @if ($selectionMode)
                    <td class="border " >
                   
                        
         {{-- <input class="" type="checkbox" wire:model="selectedItems" value="{{ $client['id'] }}"> --}}

         <input
         wire:click="toggleItemSelection('{{ $client['id'] }}')"
         type="checkbox"
         name="clientes[{{ $client->nro_cliente }}][seleccionado]"
         {{ in_array($client['id'], $this->selectedItems) ? 'checked' : '' }}
     />
     

                        
                    </td>
                @endif

                @if (count($groupedClients) > 1 && $index == 0)

                    <td class="border p-2 hover:bg-gray-100" rowspan="{{ count($groupedClients) }}">{{ $client['nro_cliente'] }}</td>

                @elseif($index == 0)

                    <td class="border p-2 hover:bg-gray-100">{{ $client['nro_cliente'] }}</td>

                @endif

                <td class="border p-2 hover:bg-gray-100">

                    <div class="flex items-center">
                                <div class="w-10 h-10 flex-shrink-0 mr-2 sm:mr-3"><img class="logo" src="https://recibos3.banksa.com.ar/logos/{{ $client['nombre'] }}.png" width="40" height="40" alt="Alex Shatov"></div>
                                <div class="font-medium text-gray-800"> {{ $client['nombre'] }}</div>
                            </div>
                    
                   
                
                </td>
                <td class="border p-2 hover:bg-gray-100">
                    @if($client['database'])
                        {{
                            $client['database']
                        }}
                    @else
                         
                    @endif
                </td>

                <td class="border p-2 hover:bg-gray-100">

                   {{-- {{$this->obtenerMaximoPorCliente($client['nro_cliente'])}} --}}
                   {{

                     $client['modulo'] === null ? 'Indefinido' : $client['modulo'] 

                   }}

                </td>



  
                {{-- @if (count($groupedClients) > 1 && $index == 0)
                <!-- Este es el primer cliente del grupo, muestra el total de activos -->
                <td class="border p-2" rowspan="{{ count($groupedClients) }}">
                    @php
                        $totalActivos = 0;
                    @endphp
                    @foreach ($groupedClients as $grouped)
                        @php
                            $totalActivos += $this->getActivos('rb3' . $grouped['nombre']);
                        @endphp
                    @endforeach
                    {{ $totalActivos }}
                </td>
                @elseif($index == 0)
                    <!-- No es un grupo, muestra solo el valor para este cliente -->
                    <td class="border p-2">
                        {{ $this->getActivos('rb3' . $client['nombre']) }}
                    </td>
                @endif




                @if (count($groupedClients) > 1 && $index == 0)
                <!-- Este es el primer cliente del grupo, muestra el total de activos -->
                <td class="border p-2" rowspan="{{ count($groupedClients) }}">
                    @php
                        $totalInactivos = 0;
                    @endphp
                    @foreach ($groupedClients as $grouped)
                        @php
                            $totalInactivos += $this->getInactivos('rb3' . $grouped['nombre']);
                        @endphp
                    @endforeach
                    {{ $totalInactivos }}
                </td>
            @elseif($index == 0)
                <!-- No es un grupo, muestra solo el valor para este cliente -->
                <td class="border p-2">
                    {{ $this->getInactivos('rb3' . $client['nombre']) }}
                </td>
            @endif --}}






                <td class="border p-2 flex">
                    
                    <button 
                                wire:click="editClient({{ $client['id'] }})" 
                                
                                style="{{ $selectionMode ? 'background-color: gray; color: white;' : 'background-color: slateblue; color: white;'}}"
                                class="button-animation-1 w-full md:w-1/2 mb-4 mt-4 py-2 px-4 rounded mr-2 {{ $selectionMode ? 'cursor-not-allowed' : '' }}"      
                                {{ $selectionMode ? 'disabled' : '' }}
                            >
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
               
            
                    <button 
                        wire:click="remove({{$client['id']}})" 
                        x-on:click="$dispatch('itemsHaveBeenUpdated')"
                        class="button-animation-1 w-full md:w-1/2 mb-4 mt-4  text-white py-2 px-4 rounded {{ $selectionMode ? 'bg-gray-500 cursor-not-allowed' : 'bg-red-700 text-white hover:bg-red-800' }}"
                        {{ $selectionMode ? 'disabled' : '' }}
                    >

                        <i class="fa-solid fa-trash"></i>

                    </button>

                </td>
            </tr>







                @endforeach
        
        
            
            @endforeach







            @elseif (!$searchMode && $clientsPaginated)

            @foreach ($clientsPaginated->groupBy('nro_cliente') as $nroCliente => $groupedClients)

            {{-- {{
                dd($clientsPaginated);
            }} --}}
                @foreach ($groupedClients as $index => $client)
                    <tr wire:key="{{ $client['id'] }}" class="group hover:bg-gray-100">
                        <!-- el id del cliente será la key de cada tr renderizado -->
                        @if ($selectionMode)
                            <td class="border p-2">
                                {{-- <input class="m-2" type="checkbox" wire:model="selectedItems" value="{{ $client['id'] }}"> --}}
                                <input
                                class="m-2"
                                wire:click="toggleItemSelection('{{ $client['id'] }}')"
                                type="checkbox"
                                name="clientes[{{ $client->nro_cliente }}][seleccionado]"
                                {{ in_array($client['id'], $this->selectedItems) ? 'checked' : '' }}
                            />
                            
                           
                           
                            </td>
                        @endif
    
                        @if (count($groupedClients) > 1 && $index == 0)
                            <td class="border p-2" rowspan="{{ count($groupedClients) }}">{{ $client['nro_cliente'] }}</td>
                        @elseif($index == 0)
                            <td class="border p-2">{{ $client['nro_cliente'] }}</td>
                        @endif
    
                        <td class="border p-2">
                            @if($client->separated)

                            <div class="flex items-center">
                                <div class="font-medium text-gray-800"> {{ $client['nombre'] }}</div>
                            </div>

                            @else

                            <div class="flex items-center">
                                <div class="w-10 h-10 flex-shrink-0 mr-2 sm:mr-3"><img class="logo" src="https://recibos3.banksa.com.ar/logos/{{ $client['nombre'] }}.png" width="40" height="40" alt="Alex Shatov"></div>
                                <div class="font-medium text-gray-800"> {{ $client['nombre'] }}</div>
                            </div>
                            
                            @endif

                        </td>
                        <td class="border p-2 hover:bg-gray-100">
                            {{
                                $client['database']
                            }}
                        </td>





                            
                        @if (count($groupedClients) > 1 && $index == 0)
                        <!-- Este es el primer cliente del grupo, muestra el total de activos -->
                        <td class="border p-2" rowspan="{{ count($groupedClients) }}">
                            {{
                                $client['modulo'] === null ? 'Indefinido' : $client['modulo'] 
                              
                             }}
                        </td>
                        @elseif($index == 0)
                            <!-- No es un grupo, muestra solo el valor para este cliente -->
                            
                        <td class="border p-2 hover:bg-gray-100">
        
                            {{-- {{$this->obtenerMaximoPorCliente($client['nro_cliente'])}} --}}
                            {{
                               $client['modulo'] === null ? 'Indefinido' : $client['modulo'] 
                             
                            }}
         
                         </td>
 
                        @endif
                     
        
                    

{{-- 
                    @if($client->separated)
                       


                    <td class="border p-2">
                        {{ $this->getActivosPorEmpresa($client['database'], $client['nombre_fantasia'])}}
                    </td>



                    @else

                        @if (count($groupedClients) > 1 && $index == 0)
                        <!-- Este es el primer cliente del grupo, muestra el total de activos -->
                        <td class="border p-2" rowspan="{{ count($groupedClients) }}">
                            @php
                                $totalActivos = 0;
                            @endphp
                            @foreach ($groupedClients as $grouped)
                                @php
                                    $totalActivos += $this->getActivos($grouped['database']);
                                @endphp
                            @endforeach
                            {{ $totalActivos }}
                        </td>
                        @elseif($index == 0)
                            <!-- No es un grupo, muestra solo el valor para este cliente -->
                            <td class="border p-2">
                                {{ $this->getActivos($client['database']) }}
                            </td>
                        @endif

                    @endif
                     


                    @if($client->separated)



                    <td class="border p-2">
                        {{ $this->getInactivosPorEmpresa($client['database'], $client['nombre_fantasia'])}}
                    </td>


                    @else

                        @if (count($groupedClients) > 1 && $index == 0)
                        <!-- Este es el primer cliente del grupo, muestra el total de activos -->
                        <td class="border p-2" rowspan="{{ count($groupedClients) }}">
                            @php
                                $totalInactivos = 0;
                            @endphp
                            @foreach ($groupedClients as $grouped)
                                @php
                                    $totalInactivos += $this->getInactivos($grouped['database']);
                                @endphp
                            @endforeach
                            {{ $totalInactivos }}
                        </td>
                        @elseif($index == 0)
                            <!-- No es un grupo, muestra solo el valor para este cliente -->
                            <td class="border p-2">
                                {{ $this->getInactivos($client['database']) }}
                            </td>
                        @endif
  
                    @endif      --}}


                        {{-- <td class="border p-2 hover:bg-gray-100">

                           @if($this->getActivos('rb3'. $client['nombre']) > $this->obtenerMaximoPorCliente($client['nro_cliente']))

                                  5029
                           @else
                                  5027
                           @endif  

                        </td>
     --}}


                     
               
     @if (count($groupedClients) > 1 && $index == 0)
     <!-- Este es el primer cliente del grupo, muestra el total de activos -->
     
     <td class="border p-2 flex items-stretch" rowspan="{{count($groupedClients)}}" >
        <button 
            wire:click="editClient({{ $client['id'] }})" 
            
            style="{{ $selectionMode ? 'background-color: gray; color: white;' : 'background-color: slateblue; color: white;'}}"
            class="button-animation-1 w-full md:w-1/2 mb-4 mt-4 py-2 px-4 rounded mr-2 {{ $selectionMode ? 'cursor-not-allowed' : '' }}"      
            {{ $selectionMode ? 'disabled' : '' }}
        >
            <i class="fa-solid fa-pen-to-square"></i>
        </button>
        <button 
            wire:click="remove({{$client['id']}})" 
            x-on:click="$dispatch('itemsHaveBeenUpdated')"
            class="button-animation-1 w-full md:w-1/2 mb-4 mt-4  text-white py-2 px-4 rounded {{ $selectionMode ? 'bg-gray-500 cursor-not-allowed' : 'bg-red-700 text-white hover:bg-red-800' }}"
            {{ $selectionMode ? 'disabled' : '' }}
        >
            <i class="fa-solid fa-trash"></i>
        </button>
    </td>




     @elseif($index == 0)
         <!-- No es un grupo, muestra solo el valor para este cliente -->
         
    
         <td class="border p-2 flex">
            <button 
                wire:click="editClient({{ $client['id'] }})" 
                
                style="{{ $selectionMode ? 'background-color: gray; color: white;' : 'background-color: slateblue; color: white;'}}"
                class="button-animation-1 w-full md:w-1/2 mb-4 mt-4 py-2 px-4 rounded mr-2 {{ $selectionMode ? 'cursor-not-allowed' : '' }}"      
                {{ $selectionMode ? 'disabled' : '' }}
            >
                <i class="fa-solid fa-pen-to-square"></i>
            </button>
            <button 
                wire:click="remove({{$client['id']}})" 
                x-on:click="$dispatch('itemsHaveBeenUpdated')"
                class="button-animation-1 w-full md:w-1/2 mb-4 mt-4  text-white py-2 px-4 rounded {{ $selectionMode ? 'bg-gray-500 cursor-not-allowed' : 'bg-red-700 text-white hover:bg-red-800' }}"
                {{ $selectionMode ? 'disabled' : '' }}
            >
                <i class="fa-solid fa-trash"></i>
            </button>
        </td>



     @endif

                        {{-- <td class="border p-2 flex">
                            <button 
                                wire:click="editClient({{ $client['id'] }})" 
                                
                                style="{{ $selectionMode ? 'background-color: gray; color: white;' : 'background-color: slateblue; color: white;'}}"
                                class="button-animation-1 w-full md:w-1/2 mb-4 mt-4 py-2 px-4 rounded mr-2 {{ $selectionMode ? 'cursor-not-allowed' : '' }}"      
                                {{ $selectionMode ? 'disabled' : '' }}
                            >
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button 
                                wire:click="remove({{$client['id']}})" 
                                x-on:click="$dispatch('itemsHaveBeenUpdated')"
                                class="button-animation-1 w-full md:w-1/2 mb-4 mt-4  text-white py-2 px-4 rounded {{ $selectionMode ? 'bg-gray-500 cursor-not-allowed' : 'bg-red-700 text-white hover:bg-red-800' }}"
                                {{ $selectionMode ? 'disabled' : '' }}
                            >
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
 --}}


                    </tr>
                @endforeach
            @endforeach
        @endif

                </tbody>
            </table>


            @if ($searchMode && $clientsResults)

            {{ $clientsResults->links() }}

           @elseif(!$searchMode)

           {{ $clientsPaginated->links() }}


           @endif
            
    @endif

 

    <div x-show="openCreateModal" class="fixed z-10 inset-0 overflow-y-auto flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
    
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <!-- Formulario de creación -->
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <!-- Aquí va tu formulario de edición -->
                    <form wire:submit.prevent="create">
            
                        <div class="mb-4 border p-2 rounded">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                Nombre
                            </label>
                            <input wire:model="formCreate.nombre" value="{{ $formCreate['nombre'] }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" placeholder="Nombre">
                            @error('formCreate.nombre')
                                <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
            
                        <div class="mb-4 border p-2 rounded">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="nrocliente">
                                Nro Cliente
                            </label>
                            <input wire:model="formCreate.nro_cliente" value="{{ $formCreate['nro_cliente'] }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nrocliente" type="number" placeholder="nrocliente">
                            @error('formCreate.nro_cliente')
                                <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
            
                        <div class="mb-4 border p-2 rounded">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="modulo">
                                Modulo
                            </label>
                            <input wire:model="formCreate.modulo" value="{{ $formCreate['modulo'] }}" type="number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="modulo" placeholder="Modulo">
                            @error('formCreate.modulo')
                                <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
            
                        <!-- ... (existing form fields) ... -->
            
                        @if ($showUpdateSuccessMessage)
                            <div class="bg-green-200 text-green-800 p-2 mb-4 rounded">
                                Cliente actualizado correctamente.
                            </div>
                        @endif
            
                        <div class="flex justify-between">
                            <button type="submit" class="button-animation-1 bg-blue-500 text-white py-2 px-4 rounded">Crear Cliente</button>
                        </div>
                    </form>
        
                    <!-- Botón de cierre -->
                    <div class="flex justify-end">
                         <button class="button-animation-1 bg-red-500 hover:bg-red-600 text-white font-bold px-6 rounded p-3" wire:click="openAndCloseCreateModal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    


      
    
            <div x-show="openUpdateModal"   class="fixed z-10 inset-0 overflow-y-auto flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                
                <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <!-- Aquí va tu formulario de edición -->
                        <form wire:submit.prevent="update">
                            {{-- <div class="mb-4 border p-2 rounded">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                    Nombre
                                </label>
                                <input wire:model="form.nombre" value="{{ $form['nombre'] }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" placeholder="Nombre">
                            </div>
                            --}}
                            @if($this->paradineiroExcepcion == 'FARMACIA DEL NVO.PASEO DE PILAR SOC.COMANDITA SIM.(paradineirofarmacias)')
                            <div class="mb-4 border p-2 rounded">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="nrocliente">
                                    Nro Cliente
                                </label>
                                <input wire:model="formParadineiro.nro_cliente" value="{{ $formParadineiro['nro_cliente'] }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nrocliente" type="number" placeholder="nrocliente">
                            </div>  
                            @endif
                            <div class="mb-4 border p-2 rounded">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="modulo">
                                    Modulo
                                </label>
                                
                                <input wire:model="form.modulo" value="{{ $form['modulo'] }}" type="number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="modulo"  placeholder="Modulo">
                            </div>
                            {{-- <div class="mb-4 border p-2 rounded">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="database">
                                    Database
                                </label>
                                <input wire:model="form.database" value="{{ $form['database'] }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nrocliente" type="text"  placeholder="Database">
                            </div>  --}}

                            @if ($showUpdateSuccessMessage)
                                <div class="bg-green-200 text-green-800 p-2 mb-4 rounded">
                                    Cliente actualizado correctamente.
                                </div>
                            @endif
                        
                            <div class="flex justify-between">
                                <button  type="submit"  class="button-animation-1 bg-blue-500 text-white py-2 px-4 rounded" >Editar Cliente</button>
                                <button class="button-animation-1 bg-red-500 hover:bg-red-600 text-white font-bold px-6 rounded p-3" wire:click="openAndCloseUpdateModal">Cerrar</button>
                            </div>
                        </form>
                        <!-- Puedes usar `editClientId` para saber qué cliente se está editando -->
                        
                    </div>
                    <!-- ... -->

                </div>



                </div>

                <div x-show="openAdvertenciaModal" x-cloak class="fixed z-10 inset-0 overflow-y-auto flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="absolute top-1/4 left-1/2 transform -translate-x-1/2 bg-white rounded-lg text-left overflow-hidden shadow-xl transition-all sm:max-w-4xl sm:w-full">
                        <div class="bg-yellow-400 px-6 py-8 sm:p-6 sm:pb-4 text-center">
                            <!-- Icono de advertencia Font Awesome -->
                            <div class="flex items-center justify-center mb-6">
                                <i class="fa-solid fa-exclamation-triangle text-6xl text-red-500"></i>
                            </div>
                
                            <h2 class="text-3xl font-semibold text-gray-800 mb-6">¡Advertencia!</h2>
                            
                            <p class="text-gray-700 mb-6">
                                Esta acción es irreversible. ¿Deseas volver a controlar los datos de la tabla?
                            </p>
                
                            <div class="flex justify-end">
                                <button class="button-animation-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold px-4 rounded p-3 mr-2" wire:click="openAndCloseAdvertenciaModal">Cancelar</button>
                                <button class="button-animation-1 bg-red-500 hover:bg-red-600 text-white font-bold px-6 rounded p-3" ="darAltaFinal">Dar Alta Final</button>
                            </div>
                        </div>
                    </div>
                </div>
                

                
                <div x-show="openNullModuloModal" x-cloak class="fixed z-10 inset-0 overflow-y-auto flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="absolute top-1/4 left-1/2 transform -translate-x-1/2 bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transition-all sm:max-w-4xl sm:w-full">
                        <div class="bg-grey-700 px-6 py-8 sm:p-6 sm:pb-4 text-center">
                            <!-- Icono de advertencia Font Awesome -->
                            <div class="flex items-center justify-center mb-6">
                                <i class="fa-solid fa-circle-exclamation text-6xl text-orange-500"></i>
                            </div>
                
                            <h2 class="text-3xl font-semibold text-white mb-6">¡Ups!</h2>
                            
                            <p class="text-white text-base mb-6">
                                Aun hay modulos indefinidos, todos los modulos maximos deben esta definidos antes de dar el alta.
                            </p>
                
                            <div class="flex justify-center">
                                <button class="button-animation-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold px-4 rounded p-3 mr-2" wire:click="openAndCloseNullModuloModal">Aceptar</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                
                
                

</div>






