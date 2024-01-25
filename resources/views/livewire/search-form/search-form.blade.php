<div>
    <form wire:submit.prevent="search">
        <input type="text" wire:model.lazy="searchTerm">
        <select wire:model="searchParam">
            <option value="nombre">Nombre</option>
            <option value="nro_cliente">Número de Cliente</option>
        </select>
        <button type="submit">Buscar</button>
    </form>

    <ul>
        @foreach ($results as $result)
            <li>{{ $result->name }}</li>
        @endforeach
    </ul>
</div>