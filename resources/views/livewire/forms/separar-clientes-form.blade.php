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
