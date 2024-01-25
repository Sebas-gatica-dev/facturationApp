<div 
class="container background-black-1 mx-auto p-4 relative {{count($serviciosCodigos) === 0 ? 'h-96' : ''}} "
x-data="{ 
    openCreateServicioModal: @entangle('showCreateServicioModal'), 
    openEditServicioModal: @entangle('showEditServicioModal'),
    openUpdateServicioModal: @entangle('showUpdateServicioModal'),
    
    openCreateCodigoModal: @entangle('showCreateCodigoModal'), 
    openEditCodigoModal: @entangle('showEditCodigoModal'), 
    openUpdateCodigoModal: @entangle('showUpdateCodigoModal'),

    openCreateServicioCodigoModal: @entangle('showAddServicioModal'), 
    openEditServicioCodigoModal: @entangle('showEditAddedServicioModal'), 
        }"
 >


   {{-- {{dd($formServicioEdit);}} --}}

    <div class="grid grid-cols-3 gap-4">

        <button wire:click="openCreateServicioModal" class="button-animation-1 m-1 bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 border border-blue-700 rounded-full" >Crear Servicio</button>        
        <button wire:click="openCreateServicioCodigoModal" class="button-animation-1 m-1 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 border border-green-700 rounded-full" >Adherir Servicio</button>
        <button wire:click="openCreateCodigoModal" class="button-animation-1 m-1 bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 border border-blue-700 rounded-full" >Crear Codigo</button>

        
       
      </div>
    
     @if (count($serviciosCodigos) === 0)

    {{-- Mensaje en caso de que no haya facturaciones pendientes --}}
    <div class="bg-white p-6 rounded shadow ">
        <p class="text-center text-gray-700 mb-3">No hay servicios enlazados</p>
    </div>

@else


    <table class="mt-10 min-w-full bg-white shadow-md rounded-lg ">
        <thead class="background-light-black text-white">
            <tr>
                <th class="py-2 px-4 border-b">Servicios 
                    <button wire:click="openEditServicioModal" class="button-animation-1 m-4 bg-purple-500 hover:bg-purple-700 text-white font-bold py-1 px-2 border border-purple-700 rounded" ><i class="fa-solid fa-eye"></i></button>            
                </th>
                <th class="py-2 px-4 border-b">Codigos
                    <button wire:click="openEditCodigoModal" class="button-animation-1 m-4 bg-purple-500 hover:bg-purple-700 text-white font-bold py-1 px-2 border border-purple-700 rounded" ><i class="fa-solid fa-eye"></i></button>

                </th>
                <th class="py-2 px-4 border-b">Acciones</th>
            </tr>
        </thead>
        <tbody>
        

           @foreach ($serviciosCodigos as $id_servicio => $servicioCodigos)

           {{-- {{dd($serviciosCodigos, $id_servicio, $servicioCodigos);}} --}}

            @foreach ($servicioCodigos as $index => $servicioCodigo)

                  {{-- {{dd($servicioCodigos, $index, $servicioCodigo);}} --}}

                <tr wire:key="{{ $servicioCodigo->id}}" class="hover:bg-gray-200">
                    @if ($index == 0)
                        <td class="py-2 px-4 border-b" rowspan="{{ $servicioCodigos->count() }}">
                            {{ $servicioCodigo->servicio->servicio }}
                            <button 
                                wire:click="editServicio({{ $servicioCodigo['id_servicio'] }})" 
                                style="background-color: gray; color: white;' 
                                : 'background-color: slateblue; color: white;"
                                class="button-animation-1 p-2 mb-2 mt-2 py-2  rounded ml-2"
                            >
                            <i class="fa-solid fa-pen-to-square"></i>
                             </button>
                        </td>
                    @endif
                    <td class="py-2 px-4 border-b">
                        {{ $servicioCodigo->codigo }}
                        <button 
                        wire:click="editCodigo({{ $servicioCodigo->codigo }})" 
                        style="background-color: gray; color: white;' 
                        : 'background-color: slateblue; color: white;"
                        class="button-animation-1  p-2 mb-2 mt-2 py-2  rounded ml-2"
                    >
                    <i class="fa-solid fa-pen-to-square"></i>
                     </button>
                    </td>
                    <td class="py-2 px-4 border-b flex">
                    <button
                        wire:click="openEditServicioCodigoModal({{ $servicioCodigo->id }})"
                        class="button-animation-1 m-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 border border-green-700 rounded-full" 
                    >
                        Editar Adhesión
                    </button>

                    <button 
                        wire:click="deleteServicioCodigo({{ $servicioCodigo->id_servicio }}, '{{ $servicioCodigo->codigo }}')" 
                        x-on:click="$dispatch('itemsHaveBeenUpdated')"
                        class="button-animation-1 m-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 border border-red-700 rounded"      
                    >
                       <i class="fa-solid fa-trash"></i>
                    </button>
                    </td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>

@endif












{{-- COMIENZO MODALES CREAR Y EDITAR SERVICIOS --}}

          <div
                x-show="openCreateServicioModal" 
                @click.away="closeCreateServicioModal"
                x-cloak  
                class="fixed z-10 inset-0 overflow-y-auto flex items-center justify-center" 
                aria-labelledby="modal-title"
                role="dialog" 
                aria-modal="true"
          >
            <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <!-- Aquí va tu formulario de edición -->
                    <form wire:submit.prevent="createServicio">
                        <div class="mb-4 border p-2 rounded">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="servicio">
                                Servicio
                            </label>
                            <input wire:model="formCreateServicio.servicio"  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" placeholder="Servicio">
                        </div>
                        <div class="mb-4 border p-2 rounded">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="descripcion">
                                Descripcion
                            </label>
                            <input wire:model="formCreateServicio.descripcion"  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nrocliente" type="text" placeholder="Descripcion">
                        </div> 
                    
                        <div class="flex justify-end">
                            <button  type="submit" class="button-animation-1 bg-indigo-500 hover:bg-indigo-700 text-white py-2 px-4 rounded" >Guardar Servicio</button>
                        </div>
                    </form>
                    <button wire:click="closeCreateServicioModal" class="button-animation-1 absolute bottom-0 left-0  m-4 bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded" >Cerrar</button>

                    <!-- Puedes usar `editClientId` para saber qué cliente se está editando -->
                </div>
                <!-- ... -->
            </div>

        </div> 



        {{-- MODAL PARA SELECCION DE EDICION Y ELIMINACION --}}
        
        <div
        x-show="openEditServicioModal" 
        @click.away="closeCreateServicioModal"
        x-cloak  
        class="fixed z-10 inset-0 overflow-y-auto flex items-center justify-center" 
        aria-labelledby="modal-title"
        role="dialog" 
        aria-modal="true"
    >
        <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <!-- Aquí va tu formulario de edición -->
                
                <table>
                    <thead class="bg-indigo-500 text-white">
                        <tr>
                            <th class="py-2 px-10 border-b">Servicio </th>
                            <th class="py-2 px-10 border-b">Descripcion</th>
                            <th class="py-2 px-10 border-b">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($servicios as $servicio)
                            <tr wire:key="{{ $servicio['id'] }}">
                                <!-- el id del cliente será la key de cada tr renderizado -->
                                <td class="border p-2">
                                    {{$servicio['servicio']}}
                                </td>
                                <td class="border p-2">
                                    {{$servicio['descripcion']}}
                                </td>
                                <td class="border flex">
                                    <!-- Botón de Editar -->
                                    <button 
                                        wire:click="editServicio({{ $servicio['id'] }})" 
                                        style="background-color: gray; color: white;' : 'background-color: slateblue; color: white;"
                                        class="button-animation-1 px-4 mb-2 mt-2 py-2 rounded ml-2"
                                    >
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <!-- Botón de Borrar -->
                                    <button 
                                        wire:click="deleteServicio({{$servicio['id']}})" 
                                        x-on:click="$dispatch('itemsHaveBeenUpdated')"
                                        class="button-animation-1  px-4 mb-2 mt-2 py-2  bg-red-500 text-white  rounded ml-2"     
                                    >
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button wire:click="closeEditServicioModal" class=" m-4 bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded" >Cerrar</button>
                <!-- Puedes usar `editClientId` para saber qué cliente se está editando -->
            </div>
            <!-- ... -->
        </div>
    </div>






{{-- MODAL TIPO UPDATE PARA UN SERVICIO --}}

    <div x-show="openUpdateServicioModal" x-cloak  class="fixed z-10 inset-0 overflow-y-auto flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <!-- Aquí va tu formulario de edición -->
                <form wire:submit.prevent="updateServicio">
                    <div class="mb-4 border p-2 rounded">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                            Servicio
                        </label>
                        <input wire:model="formUpdateServicio.servicio" value="{{ $formUpdateServicio['servicio'] }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="Servicio" type="text">
                    </div>
                    <div class="mb-4 border p-2 rounded">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nrocliente">
                            Descripcion
                        </label>
                        <input wire:model="formUpdateServicio.descripcion" value="{{ $formUpdateServicio['descripcion'] }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="Descirpcion" type="text">
                    </div> 
                
                    <div class="flex justify-end">
                        <button  type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white py-2 px-4 rounded" >Editar Servicio</button>
                    </div>
                </form>
                <button wire:click="closeUpdateServicioModal" class="absolute bottom-0 left-0  m-4 bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded" >Cerrar</button>

                <!-- Puedes usar `editClientId` para saber qué cliente se está editando -->
            </div>
            <!-- ... -->
        </div>

    </div>


            


{{-- FIN DE MODALES PARA CREAR Y EDITAR SERVICIOS --}}
















{{-- COMIENZO MODALES CREAR Y EDITAR CODIGOS --}}

<div
x-show="openCreateCodigoModal" 
@click.away="closeCreateCodigoModal"
x-cloak  
class="fixed z-10 inset-0 overflow-y-auto flex items-center justify-center" 
aria-labelledby="modal-title"
role="dialog" 
aria-modal="true"
>
<div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
<div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
    <!-- Aquí va tu formulario de edición -->
    <form wire:submit.prevent="createCodigo">
        <div class="mb-4 border p-2 rounded">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="servicio">
                Codigo
            </label>
            <input wire:model="formCreateCodigo.codigo"  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="codigo" type="number" placeholder="Codigo">
        </div>
        <div class="mb-4 border p-2 rounded">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                Descripcion
            </label>
            <input wire:model="formCreateCodigo.description"  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" type="text" placeholder="Description">
        </div> 
    
        <div class="flex justify-end">
            <button  type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white py-2 px-4 rounded" >Guardar Codigo</button>
        </div>
    </form>
    <button wire:click="closeCreateCodigoModal" class="absolute bottom-0 left-0  m-4 bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded" >Cerrar</button>

    <!-- SE UTILIZA `editClientId` PARA SABER QUE CLIENTE SE ESTA EDITANDO -->
</div>
<!-- ... -->
</div>

</div> 



<div
x-show="openEditCodigoModal" 
@click.away="closeCreateCodigoModal"
x-cloak  
class="fixed z-10 inset-0 overflow-y-auto flex items-center justify-center" 
aria-labelledby="modal-title"
role="dialog" 
aria-modal="true"
>
<div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
<div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
<!-- Aquí va tu formulario de edición -->
 

<table>

    <thead class="bg-indigo-500 text-white">
        <tr>
            <th class="py-2 px-10 border-b">Codigo</th>
            <th class="py-2 px-10 border-b">Descripcion</th>
            <th class="py-2 px-10 border-b">Acciones</th>
           
        </tr>
    </thead>


    <tbody>



        @foreach ($codigos as $codigo)

{{-- {{dd($servicio['servicio']);}} --}}



<tr wire:key="{{ $codigo['codigo'] }}" >
<!-- el id del cliente será la key de cada tr renderizado -->



<td class="border p-2">

   {{$codigo['codigo']}}

</td>


<td class="border p-2">
    
   {{$codigo['description']}}                      

</td>


<td class="border flex">

{{-- {{dd($client);}} --}}

    <!-- Botón de Editar -->
    
    <button 
    wire:click="editCodigo({{ $codigo['codigo'] }})" 
    style="background-color: gray; color: white;' 
    : 'background-color: slateblue; color: white;"
    class="button-animation-1   px-4 mb-2 mt-2 py-2  rounded ml-2"
>
<i class="fa-solid fa-pen-to-square"></i>
 </button>
  

    <!-- Botón de Borrar -->
    <button 
        wire:click="deleteCodigo({{$codigo['codigo']}})" 
        x-on:click="$dispatch('itemsHaveBeenUpdated')"
        class="button-animation-1 bg-red-500 text-white  px-4 mb-2 mt-2 py-2  rounded ml-2"      
    >
    <i class="fa-solid fa-trash"></i>

    </button>

</td>
</tr>

@endforeach


    </tbody>


</table>


<button wire:click="closeEditCodigoModal" class=" m-4 bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded" >Cerrar</button>



<!-- Puedes usar `editClientId` para saber qué cliente se está editando -->
</div>
<!-- ... -->
</div>

</div>





<div x-show="openUpdateCodigoModal" x-cloak  class="fixed z-10 inset-0 overflow-y-auto flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
<div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
<div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
<!-- Aquí va tu formulario de edición -->
<form wire:submit.prevent="updateCodigo">
    <div class="mb-4 border p-2 rounded">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="codigo">
            Codigo
        </label>
        <input wire:model="formUpdateCodigo.codigo" value="{{ $formUpdateCodigo['codigo'] }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="Servicio" type="number">
    </div>
    <div class="mb-4 border p-2 rounded">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
            Descripcion
        </label>
        <input wire:model="formUpdateCodigo.description" value="{{ $formUpdateCodigo['description'] }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="Description" type="text">
    </div> 

    <div class="flex justify-end">
        <button  type="submit" class="ml-2 mt-5 bg-indigo-500 hover:bg-indigo-700 text-white py-2 px-4 rounded" >Editar Codigo</button>
    </div>
</form>
<button wire:click="closeUpdateCodigoModal" class="absolute bottom-0 left-0  m-4 bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded" >Cerrar</button>

<!-- Puedes usar `editClientId` para saber qué cliente se está editando -->
</div>
<!-- ... -->
</div>

</div>





{{-- FIN DE MODALES PARA CREAR Y EDITAR CODIGOS --}}





{{-- COMIENZO DE MODALES PARA CREAR Y EDITAR SERVICIOS CODIGOS --}}


<div x-show="openCreateServicioCodigoModal" @click.away="closeCreateServicioCodigoModal" x-cloak class="fixed z-10 inset-0 overflow-y-auto flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <!-- Aquí va tu formulario de edición -->
            <form wire:submit.prevent="createServicioCodigo">
                <div class="mb-4 border p-2 rounded">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="servicio">
                        Servicio
                    </label>
                    <select wire:model="formCreateServicioCodigo.id_servicio" class="p-2 rounded">
                        <option value="">Selecciona un servicio</option>
                        @foreach ($servicios as $servicio)
                        <option value="{{$servicio['id']}}">{{$servicio['servicio']}}</option>
                        @endforeach
                    </select>
                    @error('formCreateServicioCodigo.id_servicio') <span class="text-red-500">Selecciona un servicio.</span> @enderror
                </div>
                <div class="mb-4 border p-2 rounded">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="codigo">
                        Codigo
                    </label>
                    <select wire:model="formCreateServicioCodigo.codigo" class="p-2 rounded">
                        <option value="">Selecciona un codigo</option>
                        @foreach ($codigos as $codigo)
                        <option value="{{$codigo['codigo']}}">{{$codigo['codigo']}}</option>
                        @endforeach
                    </select>
                    @error('formCreateServicioCodigo.codigo') <span class="text-red-500">Selecciona un codigo.</span> @enderror
                </div>

                <div class="flex justify-end mt-4">
                    @if (!empty($customErrors['existingServicioCodigo']))
                    <div class="text-red-500 m-2 mt-10">{{ $customErrors['existingServicioCodigo'] }}</div>
                    @endif
                    <button type="submit" class="ml-2 mt-5 bg-green-500 hover:bg-green-700 text-white py-2 px-4 rounded">Crear Adhesión</button>
                </div>
            </form>
        </div>

        <!-- Botón "Cerrar" fuera del formulario -->
        <button wire:click="closeCreateServicioCodigoModal" class="absolute bottom-0 left-0  m-4 bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded">Cerrar</button>

        <!-- Puedes usar `editClientId` para saber qué cliente se está editando -->
    </div>
    <!-- ... -->
</div>








{{-- FIN DE MODALES PARA CREAR Y EDITAR SERVICIOS CODIGOS --}}

</div>



