<div x-data="{
    openSeparadosModal: @entangle('showCreateSeparadosModal'),
}" 
  class="container mx-auto p-6 "
>
    <div class="mb-4">
        {{-- <button wire:click="obtenerEmpresas('meridianonorte')" class="button-animation-1 bg-indigo-500 hover:bg-indigo-700 text-white font-bold px-4 rounded p-3" type="submit">
            mostrar empresas
        </button> --}}
        <form wire:submit.prevent="search" class="flex">
            <input type="text" wire:model.lazy="searchTerm" class="w-full rounded">
            <select wire:model="searchParam" class="mx-10 px-3 rounded">
                <option value="nombre">Nombre</option>
                <option value="nro_cliente">Número de Cliente</option>
            </select>
            <button wire:submit.prevent="addToFacturacion" class="button-animation-1 bg-indigo-500 hover:bg-indigo-700 text-white font-bold px-4 rounded p-3" type="submit">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
           
        </form>
        @if ($searchMode)
            <button wire:click="cancelSearch" class="button-animation-1 bg-red-500 hover:bg-red-700 mt-5 text-white font-bold py-2 px-4 rounded">
                Cancelar búsqueda
            </button>
        @endif
    </div>

    <form wire:submit.prevent="addToFacturacion">
        @csrf

        @if ($searchMode)
            @if (count($clientsResultsIterable) === 0)
                <div class="mb-4">
                    <p class="text-center text-white font-medium">No hay resultados</p>
                </div>
            @else
                <button type="submit" class="button-animation-1 mb-4 mt-4 bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Agregar a Facturación
                </button>
                <table class="border min-w-full shadow-md rounded-lg mb-5 pb-96">
                    <thead class="background-light-black rounded-lg text-white">
                        <tr>
                            <th class="border-b ">Action </th>
                            <th class="py-2 px-4 border-b">Nombre</th>
                            <th class="py-2 px-4 border-b">Nro_Cliente</th>
                            <th class="py-2 px-4 border-b">Separar</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientsResultsIterable->groupBy('nro_cliente') as $nroCliente => $groupedClients)
                            @foreach ($groupedClients as $index => $client)

                            @if($this->comprobarMasDeUnaEmpresa($client->nombre) !== 0)
                               
                                <tr wire:key="{{ $client->id }}" class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-100' }} hover:bg-gray-200">
                                    @if(count($groupedClients) > 1 && $index == 0)
                                        <td class="border py-2 px-4" rowspan="{{ count($groupedClients) }}">
                                            <input
                                                wire:click="toggleClientSelection('{{ json_encode(["num_cliente" => $client->nro_cliente, "nombre" => $client->nombre]) }}')"
                                                type="checkbox"
                                                name="clientes[{{ $client->nro_cliente }}][seleccionado]"
                                                {{ in_array(
                                                    json_encode([
                                                        "num_cliente" => $client->nro_cliente,
                                                        "nombre" => $client->nombre
                                                    ]),
                                                    $this->selectedClients)
                                                    ? 'checked'
                                                    : ''
                                                }}
                                            />
                                        </td>
                                    @elseif($index == 0)
                                        <td class="border py-2 px-4">
                                            <input
                                                wire:click="toggleClientSelection('{{ json_encode(["num_cliente" => $client->nro_cliente, "nombre" => $client->nombre]) }}')"
                                                type="checkbox"
                                                name="clientes[{{ $client->nro_cliente }}][seleccionado]"
                                                {{ in_array(
                                                    json_encode([
                                                        "num_cliente" => $client->nro_cliente,
                                                        "nombre" => $client->nombre
                                                    ]),
                                                    $this->selectedClients)
                                                    ? 'checked'
                                                    : ''
                                                }}
                                            />
                                        </td>
                                    @endif
                                    <td class="p-2 pl-10 whitespace-nowrap border">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 flex-shrink-0 mr-2 sm:mr-3">
                                                <img class="logo" src="https://recibos3.banksa.com.ar/logos/{{$client->nombre}}.png" width="40" height="40" alt="Alex Shatov">
                                            </div>
                                            <div class="font-medium text-gray-800">{{ $client->nombre }}</div>
                                        </div>
                                    </td>
                                    @if(count($groupedClients) > 1 && $index == 0)
                                        <td class="py-2 px-4 pl-10 border" rowspan="{{ count($groupedClients) }}">{{ $client->nro_cliente }}</td>
                                    @elseif($index == 0)
                                        <td class="py-2 px-4 pl-10 border">{{ $client->nro_cliente }}</td>
                                    @endif

                                    @if(
                                            $this->comprobarMasDeUnaEmpresa($client->nombre) > 1 && $this->comprobarEmpresasFiltradas($client->nombre) > 0
                                            )
                                            <td class="py-2 px-4 pl-10 border" >
        
                                            <button 
                                                wire:click="obtenerEmpresas('{{ $client->nombre }}', {{ $client->nro_cliente }})" 
                                                
                                                style="background-color: rgb(19, 15, 37); color: white; cursor:pointer;"
                                                class="button-animation-1 w-full md:w-1/2 mb-4 mt-4 py-2 px-4 rounded mr-2 "      
                                               
                                            >
                                                <i class="fa-solid fa-scissors"></i>
                                            </button>
        
        
                                            </td>
        
                                            @else
        
                                            <td class="py-2 px-4 pl-10 border" >
        
                                                
                                                
                                            <button 
                                                
                                                
                                            style="background-color: rgb(67, 101, 136); color: rgb(36, 34, 34); cursor:pointer;"
                                            class="button-animation-1 w-full md:w-1/2 mb-4 mt-4 py-2 px-4 rounded mr-2 "      
                                               
                                            >
                                               <i class="fa-solid fa-lock"></i>
                                           </button>
        
                                            </td>
        
                                            @endif
                                </tr>

                                @endif

                            @endforeach
                        @endforeach
                    </tbody>
                </table>
                
            @endif
        @elseif (!$searchMode)
                    @if (count($clientsIterable) === 0)
                            <div class="mb-4">
                                <p class="text-center text-white font-medium">Todos los clientes ya han sigo migrados.</p>
                            </div>
                   @else
            <button type="submit" class="button-animation-1 mb-4 mt-4 bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Agregar a Facturación
            </button>
            <table class="min-w-full bg-white shadow-md rounded-lg mb-5">
                <thead class="background-light-black text-white">
                    <tr>
                        <th class=" border-b ">Action </th>
                        <th class="py-2 px-4 border-b">Nombre</th>
                        <th class="py-2 px-4 border-b">Nro Cliente</th>
                        <th class="py-2 px-4 border-b">Separar</th>

                    </tr>
                </thead>
                <tbody>
                   
                    @foreach ($clientsIterable->groupBy('nro_cliente') as $nroCliente => $groupedClients)
                        @foreach ($groupedClients as $index => $client)

                             @if($this->comprobarMasDeUnaEmpresa($client->nombre) !== 0)

                                    <tr wire:key="{{ $client->id }}" class="hover:bg-gray-200">
                                        @if(count($groupedClients) > 1 && $index == 0)
                                            <td class="border py-2 px-4" rowspan="{{ count($groupedClients) }}">
                                                <input
                                                    wire:click="toggleClientSelection('{{ json_encode(["num_cliente" => $client->nro_cliente, "nombre" => $client->nombre]) }}')"
                                                    type="checkbox"
                                                    name="clientes[{{ $client->nro_cliente }}][seleccionado]"
                                                    {{ in_array(
                                                        json_encode([
                                                            "num_cliente" => $client->nro_cliente,
                                                            "nombre" => $client->nombre
                                                        ]),
                                                        $this->selectedClients)
                                                        ? 'checked'
                                                        : ''
                                                    }}
                                                />
                                            </td>
                                        @elseif($index == 0)
                                            <td class="border py-2 px-4">
                                                <input
                                                    wire:click="toggleClientSelection('{{ json_encode(["num_cliente" => $client->nro_cliente, "nombre" => $client->nombre]) }}')"
                                                    type="checkbox"
                                                    name="clientes[{{ $client->nro_cliente }}][seleccionado]"
                                                    {{ in_array(
                                                        json_encode([
                                                            "num_cliente" => $client->nro_cliente,
                                                            "nombre" => $client->nombre
                                                        ]),
                                                        $this->selectedClients)
                                                        ? 'checked'
                                                        : ''
                                                    }}
                                                />
                                            </td>
                                        @endif
                                        <td class="p-2 pl-10 whitespace-nowrap border">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 flex-shrink-0 mr-2 sm:mr-3">
                                                    <img class="logo" src="https://recibos3.banksa.com.ar/logos/{{$client->nombre}}.png" width="40" height="40" alt="Alex Shatov">
                                                </div>
                                                <div class="font-medium text-gray-800">{{ $client->nombre }}</div>
                                            </div>
                                        </td>
                                        @if(count($groupedClients) > 1 && $index == 0)
                                            <td class="py-2 px-4 pl-10 border" rowspan="{{ count($groupedClients) }}">{{ $client->nro_cliente }}</td>
                                        @elseif($index == 0)
                                            <td class="py-2 px-4 pl-10 border">{{ $client->nro_cliente }}</td>
                                        @endif


                                        @if(
                                            $this->comprobarMasDeUnaEmpresa($client->nombre) > 1 && $this->comprobarEmpresasFiltradas($client->nombre) >= 1
                                            )
                                            <td class="py-2 px-4 pl-10 border" >
        
                                            <button 
                                            wire:click="obtenerEmpresas('{{ $client->nombre }}', {{ $client->nro_cliente }})"
                                                
                                                style="background-color: rgb(19, 15, 37); color: white; cursor:pointer;"
                                                class="button-animation-1 w-full md:w-1/2 mb-4 mt-4 py-2 px-4 rounded mr-2 "      
                                               
                                            >
                                                <i class="fa-solid fa-scissors"></i>
                                            </button>
        
        
                                            </td>
        
                                            @else
        
                                            <td class="py-2 px-4 pl-10 border" >
        
                                                
                                                
                                            <button 
                                                
                                                
                                            style="background-color: rgb(67, 101, 136); color: rgb(36, 34, 34); cursor:pointer;"
                                            class="button-animation-1 w-full md:w-1/2 mb-4 mt-4 py-2 px-4 rounded mr-2 "      
                                               
                                            >
                                               <i class="fa-solid fa-lock"></i>
                                           </button>
        
                                            </td>
        
                                            @endif
                                    </tr>

                                    @endif
                        
                        @endforeach
                    @endforeach
                </tbody>
            </table>
            @if ($searchMode && $clientsResultsIterable)
                {{ $clientsResultsIterable->links() }}
            @elseif (!$searchMode)
                {{ $clientsIterable->links() }}
            @endif
        @endif
        @endif
    </form>




    <div x-show="openSeparadosModal" x-cloak class="fixed z-10 inset-0 overflow-y-auto flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="absolute top-1/4 left-1/2 transform -translate-x-1/2 bg-white rounded-lg text-left overflow-hidden shadow-xl transition-all sm:max-w-4xl sm:w-full">
            <div class="bg-withe00 px-6 py-8 sm:p-6 sm:pb-4 text-center">
                <!-- Icono de advertencia Font Awesome -->

                <div class="flex items-center justify-center mb-6">
                    <i class="fa-solid fa-scissors text-6xl"></i>
                </div>
    
                <h2 class="text-3xl font-semibold text-gray-800 mb-6">Separados</h2>
                
                <p class="text-gray-700 mb-6">
                      Debes asignar un numero de Cliente a la empresas que quieras separar se la facturacion regular. 
                </p>

                <form wire:submit.prevent="separarClientes">
                    <ul>
                        @foreach ($this->listaDeEmpresas as $empresa)
                            <li class="mb-4 border p-2 rounded">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="modulo{{ $empresa['nombre_fantasia'] }}">
                                    {{ $empresa['nombre_fantasia'] }}
                                </label>
                                <input
                                    wire:model.defer="moduloData.{{ $empresa['nombre_fantasia'] }}"
                                    type="number"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="modulo{{ $empresa['nombre_fantasia'] }}"
                                    placeholder="Ingresar número"
                                />
                            </li>
                        @endforeach
                    </ul>
                
                    <div class="flex justify-end">
                        <button class="button-animation-1 bg-red-500 hover:bg-red-600 text-white font-bold px-6 rounded p-3" type="submit">Separar Clientes</button>
                    </div>
                </form>
                
                <button class="button-animation-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold px-4 rounded p-3 mr-2" @click="openSeparadosModal = false">Cancelar</button>

    
              
            </div>
        </div>
    </div>



</div>
