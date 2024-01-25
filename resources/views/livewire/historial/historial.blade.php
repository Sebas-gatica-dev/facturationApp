<div class="p-4 mt-10 flex">
    {{-- Barra lateral izquierda --}}
    <div class="w-1/4 pr-8 bg-gray-200 text-gray-800 h-screen p-4">
        <h1 class="text-2xl font-bold mb-4">Historial List</h1>

        @if($historialList->isEmpty())
            <p class="text-gray-500">No hay elementos en el historial.</p>
        @else
            <ul class="list-disc pl-4 mt-14">
                @foreach ($historialList as $h)
                    <li class="mb-2">
                        <button 
                            wire:click="switchPeriodo('{{ $h->periodo }}')"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline-blue active:bg-blue-800"
                        >
                            {{ $h->periodo }}
                        </button>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Contenido principal --}}
    <div class="w-3/4 overflow-auto">
        @if($tableIsVisible)
            {{-- Vista de la tabla --}}
            <h1 class="text-2xl font-bold mb-4">Historial Table</h1>
            <table class="w-full border border-collapse">
                <thead>
                    <tr>
                        <th>Nro_Cliente</th>
                        <th>Servicio</th>
                        <th>Periodo</th>
                        <th>Activos</th>
                        <th>Inactivos</th>
                        <th>Remito</th>
                        <th>Modulo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listaFacturaciones as $f)

                      {{-- {{dd($f->cliente, $f->servicio)}} --}}
                        <tr>
                            <td>{{ $f->id_cliente }}</td>
                            <td>{{ $f->id_servicio }}</td>
                            <td>{{ $f->periodo }}</td>
                            <td>{{ $f->cantidad_activos }}</td>
                            <td>{{ $f->cantidad_inactivos }}</td>
                            <td>{{ $f->remito }}</td>
                            <td>{{ $f->modulo }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500">Contenido por defecto cuando la tabla no es visible.</p>
        @endif
    </div>
</div>
