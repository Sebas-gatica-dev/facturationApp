<?php

namespace App\Http\Livewire\Historial;

use App\Models\Facturacion;
use App\Models\Historial as ModelsHistorial;
use Livewire\Component;

class Historial extends Component
{



    public $periodo;
    public $listaFacturaciones = [];
    public $tableIsVisible = false;
    
    protected $listeners = [
        'listarFacturacion' => 'obtenerListaFacturaciones',
        'render' => 'render', 
    ];
    
    public function obtenerListaFacturaciones($periodo)
    {
        $this->listaFacturaciones = Facturacion::where('periodo', '=', $periodo)->get();
        $this->tableIsVisible = true;
    }
    
    




    public function switchPeriodo($periodo)
    {
        $this->emit('listarFacturacion', $periodo);
    }
    
   





    public function render()
    {

        $historialList = ModelsHistorial::all();


        $jsonFilePath = storage_path('json/historial_excel.json');
        $jsonContent = file_get_contents($jsonFilePath);
    //    dd($historialList);
   
       $jsonDecoded = json_decode($jsonContent);
         
    //    dd($jsonDecoded[269]);

        return view('livewire.historial.historial',[
            'historialList' => $historialList,
            'listaFacturaciones' => $this->listaFacturaciones,
        ]);
    }
}
