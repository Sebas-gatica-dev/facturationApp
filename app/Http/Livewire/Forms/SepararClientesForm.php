<?php

namespace App\Http\Livewire\Forms;

use App\Bank\DataBase;
use App\Models\Cliente;
use Livewire\Component;

class SepararClientesForm extends Component
{



    public $moduloData = [];
    public $listaDeEmpresas = [];
    public $nombreEmpresa = '';
    public $numeroDeCliente = '';

   protected $listeners = [
     'handleObtenerEmpresas' => 'obtenerEmpresas',
   ];


   public function separarClientes()
   {
       foreach ($this->listaDeEmpresas as $empresa) {
           // Obtener el número ingresado para la empresa actual
           $modulo = $this->moduloData[$empresa['nombre_fantasia']];
           $formData = $this->moduloData;
           // Validar y procesar los datos según tus necesidades
           // ...



      //   var_dump($modulo);

          //  Ejemplo: Crear un nuevo Cliente con el número ingresado
           $newClient = new Cliente([
               'nro_cliente' => $modulo,
               'nombre' => $empresa['nombre_fantasia'].'('. $this->nombreEmpresa .')',
               'database' => 'rb3'. $this->nombreEmpresa,
               'modulo' => null,
               'separated' => true,
               'separated_reference' => $this->numeroDeCliente,
               'nombre_fantasia' => $empresa['nombre_fantasia'],
               // Otros campos necesarios...
           ]);



           $newClient->save();
       }

      $this->moduloData = [];
      $this->nombreEmpresa = '';
      $this->listaDeEmpresas = [];
      $this->numeroDeCliente = '';


       // Lógica adicional si es necesaria...

       // Cerrar el modal después de ejecutar la acción
       $this->openAndCloseSeparadosModal();

       $this->emit('itemsHaveBeenUpdated');
   }

   
   public function obtenerEmpresas($nombre_cliente, $nro_cliente)
   {
       $database = new Database('rb3'.$nombre_cliente);
       $this->nombreEmpresa = $nombre_cliente;
       $this->numeroDeCliente = $nro_cliente;
       // Obtener la lista de empresas
       $this->listaDeEmpresas = $database->obtenerEmpresas();
   
       // Generar dinámicamente el array $this->moduloData
       $this->moduloData = array_fill_keys(
           array_column($this->listaDeEmpresas, 'nombre_fantasia'),
           null
       );
   
       // Imprimir para verificar
       // dd($this->listaDeEmpresas, $this->moduloData);
   
       // Mostrar o cerrar el modal según sea necesario
       $this->emit('showSeparadosModal');
   }





    public function render()
    {
        return view('livewire.forms.separar-clientes-form');
    }
}
