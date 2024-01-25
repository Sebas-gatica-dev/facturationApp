<?php

namespace App\Http\Livewire\HistorialTable;

use App\Models\Facturacion;
use Livewire\Component;

class HistorialTable extends Component
{
    public $periodo;
    public $listaFacturaciones = [];
    
    protected $listeners = [
        'listarFacturacion' => 'obtenerListaFacturaciones',
    ];
    
    public function obtenerListaFacturaciones($periodo)
    {
        $this->listaFacturaciones = Facturacion::where('periodo', '===', $periodo)->get(); 

         dd($this->listarFacturaciones);
    }
    




    public function render()
    {

        // dd($this->listaFacturaciones);
        return view('livewire.historial-table.historial-table',[
            'listaFacturaciones' => $this->listaFacturaciones,
        ]);
    }
}
