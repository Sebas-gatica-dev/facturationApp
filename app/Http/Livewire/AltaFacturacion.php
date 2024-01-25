<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cliente;
use App\Bank\DataBase;
use Illuminate\Support\Facades\File;
use Livewire\WithPagination;


class AltaFacturacion extends Component
{
    use WithPagination;


    public $viewType = 'dashboard';
    public $subViewType = 'clients';
    private $separados;

    public $procesoEnCurso = false;



  
    protected $listeners = [
        'backToDashboard' => 'backDashboard',
        'switchView' => 'switchView',
       
    ];

    public function __construct()
    {

        $this->separados = [
            3123,
            3254,
            3297,
        ];
    }


    public function backDashboard(){

        $this->switchView('dashboard');
    }

    



    public function switchView($type)
    {

        $this->viewType = $type;

        session(['viewType' => $this->viewType]);
    }






    public function iniciarProceso()
    {
        // Lógica de inicio del proceso
        $this->procesoEnCurso = true;
    
        // Guardar el estado en la sesión
        session(['procesoEnCurso' => true]);
    

        $this->switchView('alta-facturacion-clientes');
        // ... Resto del código ...
    }
    

    public function terminarProceso()
    {
        // Lógica de finalización del proceso
        $this->procesoEnCurso = false;
    
        // Eliminar el contenido de la entidad Client
        Cliente::truncate();
    
        // Guardar el estado en la sesión
        session(['procesoEnCurso' => false]);
    
        // ... Resto del código ...
    }
    




    
    public function cancelarProceso()
    {
        // Lógica de cancelación del proceso
        $this->procesoEnCurso = false;
    
        // Eliminar el contenido de la entidad Client
        Cliente::truncate();
    
        // Guardar el estado en la sesión
        session(['procesoEnCurso' => false]);
    
        // ... Resto del código ...
    }
    

    public function darAltaFinal(){


        $this->emit('advertenciaModalEvent');
    }




 public function crearAltaDeFacturacion() {

    $activos = 0;
    $inactivos = 0;

    $facturacion = [];

    $grupos = [];


     foreach(Cliente::all() as $cliente){
         
        if($this -> activos( $cliente->nro_cliente )){
           $activos++;
           $remito = $this->obtenerRemito($cliente->nro_cliente);

           if(is_null($remito) && !in_array($cliente->nro_cliente, $this->separados)){
              
          $cliente_db = 'rb3'. $cliente->nombre;
          $database = new DataBase($cliente_db);

          if(empty( $facturacion[$cliente -> nro_cliente] )){ // Si no existe lo creo

            $nro_remito = 5027;

            if($database -> totalActivos() -> $remito -> maximo){
                $nro_remito = 5029;
            }
           

            $facturacion[$cliente->nro_cliente] = [
                 'Cliente' => $cliente->nro_cliente,
                 'Razon Social' => $cliente->nombre,
                 'Remito' =>  $nro_remito,
                 'Modulo maximo' => $remito->maximo,
                 'Activos' => $database->totalActivos(),
                 'Inactivos' => $database->totalInactivos(),
             ];
        }
        else{

            $facturacion[$cliente -> nro_cliente]['Razon Social'] .= ' - ' . $cliente -> nombre;
            $facturacion[$cliente -> nro_cliente]['Activos'] += $database -> totalActivos();
            $facturacion[$cliente -> nro_cliente]['Inactivos'] += $database -> totalInactivos();
        }


        }
        else{
            $this->error("{$cliente -> nombre} - Nro:{$cliente -> nro_cliente} | Error cliente sin codigo de remito");
        }
              
           }
        

           else{
            $inactivos++;
            //$this->error("{$cliente-> nombre}");
        }



        }


        $this->info('Total clientes activos: ' . $activos);
        $this->error('Total clientes inactivos: ' . $inactivos);

        $facturacion2 = array();

        foreach($facturacion as $factura){
            $facturacion2[] =  $factura;
        }

        $this -> crearFacturacion($facturacion2);

        return 0;



     }



     private function crearFacturacion(array $facturacion, string $nombre_file = "facturacion2")
    {

        $filePath = storage_path('json/'.$nombre_file.'.json'); // Ruta del archivo JSON

        if (File::exists($filePath)) {

            File::delete($filePath);

        }

        File::put($filePath, json_encode($facturacion, JSON_PRETTY_PRINT));
        $this->info('Archivo JSON creado exitosamente.');
    }



    private function separarClientesPorEmpresa(){

        $this->info('Creando Separados');

        $nro_clientes = $this -> separados;
        $separados = array();

        foreach($nro_clientes as $nro_cliente)
        {
            $remito = $this -> obtenerRemito($nro_cliente);
            $cliente  = Cliente::where('nro_cliente', $nro_cliente)->first();

            $cliente_db = 'rb3'. $cliente -> nombre;
            $database = new DataBase($cliente_db);

            $empresas = $database -> totalActivosSeparados();


            $cont = intval($cliente -> nro_cliente);

            foreach($empresas as $empresa){

                 $separados[] = [
                     'Cliente' => $cont,
                     'Razon Social' => $empresa['nombre'] . " (" . $cliente -> nombre . ")",
                     'Remito' => $remito -> remito,
                     'Modulo maximo' => $remito -> maximo,
                     'Activos' => $empresa['activos'],
                     'Inactivos' =>   $empresa['inactivos'],
                 ];

                 $cont++;


            }

        }

        $this -> crearFacturacion($separados, 'separados');

    }





    private function obtenerRemito($nro_cliente) 
    {
        
        $filePath = storage_path('json/clientes.json');


        if (File::exists($filePath)) {
            
            $jsonContents = File::get($filePath);
            $clientes = json_decode($jsonContents);


            if (!empty($clientes)) {

                foreach ($clientes as $cliente) { // Itero por todos los cliene que tengo en mi JSON

                   if($cliente -> cliente == $nro_cliente){
                     return $cliente;
                   }
                    
                }


                return $clientes;

            } else {
                $this->error('El archivo JSON está vacío o no contiene objetos.');
            }

        } else {
            $this->error('El archivo JSON no existe.');
        }

    }




    public function render()
    {
        if (session('procesoEnCurso', false)) {
            // Si el proceso está en curso, redirigir a la vista adecuada
            // return redirect()->route('vista_proceso_en_curso');

        }

        if (session('viewType')) {
            $this->viewType = session('viewType');
        }

        if ($this->viewType === 'dashboard') {
            return view('livewire.alta-facturacion.dashboard', [
                'subViewType' => $this->subViewType,
                'viewType' => $this->viewType,
            ]);
        } elseif ($this->viewType === 'historial') {
            return view('livewire.alta-facturacion.historial', []);
        } elseif ($this->viewType === 'alta-facturacion-clientes') {
            return view('livewire.alta-facturacion.alta-facturacion', [
                'subViewType' => $this->subViewType,
                'viewType' => $this->viewType,
            ]);
        } elseif ($this->viewType === 'alta-facturacion-facturaciones') {
            return view('livewire.alta-facturacion.facturaciones', [
                'subViewType' => $this->subViewType,
                'viewType' => $this->viewType,
            ]);
        }elseif($this->viewType === 'historial-table'){
            return view('livewire.alta-facturacion.historial-table');
        }

        // Si ninguna de las condiciones anteriores se cumple, simplemente devolver una vista vacía.
        return view('livewire.alta-facturacion.dashboard', [
            'subViewType' => $this->subViewType,
            'viewType' => $this->viewType,
        ]);
    }
    
}
