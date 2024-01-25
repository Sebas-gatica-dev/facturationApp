<?php
// app/Http/Livewire/FacturationTable.php

namespace App\Http\Livewire\FacturationTable;

use App\Bank\DataBase;
use App\Models\Cliente;
use App\Models\Codigo;
use App\Models\Facturacion;
use App\Models\Historial;
use App\Models\Servicio;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;


class FacturationTable extends Component
{
    use WithPagination;

    public $model;

    protected $listeners = [
        'itemsHaveBeenUpdated' => 'render',
        'editClient' => 'editClient',
        'altaFinalEvent' => 'darAltaFinal',
        'advertenciaModalEvent' => 'openAndCloseAdvertenciaModal'
    ];
    protected $remember = ['clientsResults', 'searchMode', 'searchTerm', 'searchParam'];

    public $selectionMode = false;
    public $selectedItems = [];
    public $searchTerm = '';
    public $searchParam = 'nombre';
    public $searchMode = false;
    protected $clientsResults;
    public $clientsForCSV;
    public $clientsGrouped;
    public $editClientId;
    public $form = [    
        'modulo' => '',
    ];
    public $formCreate = [
        'nombre' => '',
        'nro_cliente' => '',
        'modulo' => '',
    ];
    public $formParadineiro = [
        'nro_cliente' => '',
    ];
    public $showCreateModal = false;
    public $showAdvertenciaModal = false;
    public $showUpdateModal = false;
    public $showNullModuloModal = false;
    public $showUpdateSuccessMessage =false;
    public $paradineiroExcepcion = '';
    public $periodoName = [
        'periodo_name' => ''
    ];
    
     
    public function openAndCloseNullModuloModal(){
       
        $this->showNullModuloModal = !$this->showNullModuloModal;
    }



    public function openAndCloseAdvertenciaModal(){

        if (in_array(null,Cliente::pluck('modulo')->toArray())) {
            $this->openAndCloseNullModuloModal();
            return;
        }

        $this->showAdvertenciaModal = !$this->showAdvertenciaModal ;
    }


    public function openAndCloseCreateModal(){
        // if($this->showUpdateSuccessMessage){
        //     $this->showUpdateSuccessMessage = false;

        // }
        $this->showCreateModal = !$this->showCreateModal;

         $this->formCreate = [
            'nombre' => '',
            'nro_cliente' => '',
            'modulo' => '',
        ];
    }


    public function openAndCloseUpdateModal(){
        if($this->showUpdateSuccessMessage){
            $this->showUpdateSuccessMessage = false;

        }
        $this->showUpdateModal = !$this->showUpdateModal;
    }


    public function closeUpdateModal(){

        $this->openAndCloseUpdateModal();

    }
 
    public function getClientsResultsProperty()
{
   
    return $this->model::where($this->searchParam, 'like', '%' . $this->searchTerm . '%')->paginate(10);
    

}



private function obtenerClientes()
    {
        
        $filePath = storage_path('json/clientes.json');


        if (File::exists($filePath)) {
            
            $jsonContents = File::get($filePath);
            $clientes = json_decode($jsonContents, true);


            if (!empty($clientes)) {

                return $clientes;

            } else {
                $this->error('El archivo JSON está vacío o no contiene objetos.');
            }

        } else {
            $this->error('El archivo JSON no existe.');
        }

    }



    private function obtenerMaximoPorCliente($nroCliente)
{
    $filePath = storage_path('json/clientes.json');

    if (File::exists($filePath)) {
        $jsonContents = File::get($filePath);
        $clientes = json_decode($jsonContents, true);

        foreach ($clientes as $cliente) {
            if ($cliente['cliente'] == $nroCliente) {
                // Encontrar el cliente en el JSON y devolver el valor de 'maximo'
                return $cliente['maximo'];
            }
        }

        // Si no se encuentra el cliente, puedes devolver un valor predeterminado o manejarlo según tu lógica
        return 'indefinido'; // O cualquier otro valor que desees
    } 
}




public function toggleItemSelection($clientId)
{
    if (in_array($clientId, $this->selectedItems)) {
        $this->selectedItems = array_diff($this->selectedItems, [$clientId]);
    } else {
        $this->selectedItems[] = $clientId;
    }
}



public function getActivos($db){

    $database = new DataBase($db);
 
    $totalActivos = $database->totalActivos();
 
    return $totalActivos;
             
  }
 
  public function getInactivos($db){
 
    $database = new DataBase($db);
    
    $totalInactivos = $database->totalInactivos();
 
    return $totalInactivos;
      
  }




  public function getActivosPorEmpresa($db, $nombre_fantasia){

    $database = new DataBase($db);
 
    $totalActivos = $database->totalActivosPorEmpresa($nombre_fantasia);
 
    return $totalActivos;
             
  }
 
  public function getInactivosPorEmpresa($db, $nombre_fantasia){
 
    $database = new DataBase($db);
    
    $totalInactivos = $database->totalActivosPorEmpresa($nombre_fantasia, 'inactivo');
 
    return $totalInactivos;
      
  }







    public function search()
    {
        $this->model = Cliente::class;
        $this->clientsResults = $this->model::where($this->searchParam, 'like', $this->searchTerm . '%')
            ->orWhere($this->searchParam, 'like', '%' . $this->searchTerm . '%')
            ->paginate(10);
        $this->searchMode = true;
        $this->resetPage();
 
    
    }

    public function cancelSearch()
    {
        $this->searchMode = false;
        $this->searchTerm = '';
        $this->mount();
    }

    public function toggleSelectionMode()
    {
        $this->selectionMode = !$this->selectionMode;

        if(!$this->selectionMode) {
            $this->selectedItems = [];
        }
    }

    // public function mount()
    // {
    //     if (!$this->searchMode) {
    //         $this->clients = Cliente::class;
    //     }
    // }

    public function deleteItem($id)
    {
       
        $clientForId = Cliente::Find($id);
        // dd($clientForId);


        $clientsToDelete = Cliente::where('nro_cliente', $clientForId->nro_cliente)->get();


        foreach ($clientsToDelete as $clientToDelete) {

            $clientToDelete->delete();

        }


        // Cliente::where('id', $id)->delete();
        $this->emit('itemsHaveBeenUpdated');


    }


    public function remove($id = null)
        {

            


            if ($id) {

                $this->deleteItem($id);

            } else {

                foreach ($this->selectedItems as $clientId) {

                    $this->deleteItem($clientId);

                }

                $this->selectedItems = [];

                $this->selectionMode = false;

                $this->resetPage();

            }

        
            $this->emit('itemsHaveBeenUpdated');

        }



    public function editClient($id)
    {

        
        $client = Cliente::find($id);


        $this->paradineiroExcepcion = $client->nombre;

     

        // Carga los datos del cliente en el formulario
        // $this->form['nombre'] = $client->nombre;
        // $this->form['nro_cliente'] = $client->nro_cliente;
        $this->form['modulo'] = $client->modulo;
        // $this->form['database'] = $client->database;

        $this->emit('itemsHaveBeenUpdated');

        // Guarda el ID del cliente que se está editando
        $this->editClientId = $id;
          
        // dd($client, $this->form, $this->editClientId);

        $this->openAndCloseUpdateModal();


    }

    public function update()
    {


        if($this->paradineiroExcepcion == 'FARMACIA DEL NVO.PASEO DE PILAR SOC.COMANDITA SIM.(paradineirofarmacias)'){

   // Valida los datos del formulario
       
        // Encuentra al cliente en la base de datos
        $client = Cliente::find($this->editClientId);
    
        
                $client->update($this->formParadineiro);
            
    
            // Configura la propiedad para mostrar el mensaje de éxito
            $this->showUpdateSuccessMessage = true;
        }else{

               // Valida los datos del formulario
        $this->validate([
            'form.modulo' => 'required'
        ]);
    
        // Encuentra al cliente en la base de datos
        $client = Cliente::find($this->editClientId);
    
        // Si se encuentra el cliente, busca y actualiza todos los elementos con el mismo nro_cliente
        if ($client) {
            // Busca todos los elementos con el mismo nro_cliente
            $clientsToUpdate = Cliente::where('nro_cliente', $client->nro_cliente)->get();
    
            // Actualiza los datos de todos los elementos encontrados
            foreach ($clientsToUpdate as $clientToUpdate) {
                $clientToUpdate->update($this->form);
            }
    
            // Configura la propiedad para mostrar el mensaje de éxito
            $this->showUpdateSuccessMessage = true;
                   
        
        }

     

            // Emite un evento para indicar que los datos del cliente han sido actualizados
            $this->emit('itemsHaveBeenUpdated');
        }
    }
           


        public function exportToCsv()
        {
            $fileName = 'clients.csv';
            $headers = array('Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename=' . $fileName);
        
            $this->clientsForCSV = $this->searchMode ? Cliente::where($this->searchParam, 'like', '%' . $this->searchTerm . '%')->get() : Cliente::all();
        
            $callback = function () {
                $file = fopen('php://output', 'w');
                // Use semicolon as delimiter
                fputcsv($file, ['Cliente', 'Razon Social', 'Remito', 'Modulo Maximo', 'Activos', 'Inactivos'], ';'); // Header
        
                foreach ($this->clientsForCSV as $client) {
                    $database = new DataBase('rb3' . $client->nombre);
                    $activos =  $database->totalActivos();
                    $inactivos = $database->totalInactivos();
                    // $moduloMaximo = $this->obtenerMaximoPorCliente($client->nro_cliente);
                    $moduloMaximo = $client->modulo;
                    // Use semicolon as delimiter
                    fputcsv($file, [
                        $client->nro_cliente,
                        $client->nombre,
                        $activos > $moduloMaximo ? 5029 : 5027,
                        $moduloMaximo,
                        $activos,
                        $inactivos,
                    ], ';');
        
                    // Add a space after each field to achieve left alignment
                    fputs($file, str_repeat(' ', 2));
                }
        
                fclose($file);
            };
        
            return response()->stream($callback, 200, $headers);
        
        
    }



    public function create()
    {


    

               // Valida los datos del formulario
        $this->validate([

            'formCreate.nombre' => 'required',
            'formCreate.nro_cliente' => 'required',
            'formCreate.modulo' => 'required'

        ]);
    
        // Encuentra al cliente en la base de datos
        $existingClient = Cliente::where('nro_cliente', $this->formCreate['nro_cliente'])
        ->where('nombre', $this->formCreate['nombre'])
        ->first();

            if ($existingClient) {
            // If the client already exists, show an error message in the modal
            $this->addError('formCreate.nro_cliente', 'Esos datos ya existen.');
            return;
            }

        

               // Crear un nuevo Cliente con el número ingresado
               $newClient = new Cliente([
                'nro_cliente' => $this->formCreate['nro_cliente'],
                'nombre' => $this->formCreate['nombre'],
                'modulo' => $this->formCreate['modulo'],
              
                // Otros campos necesarios...
            ]);
    
            $newClient->save();
        

          $this->openAndCloseCreateModal();



            // Emite un evento para indicar que los datos del cliente han sido actualizados
            $this->emit('itemsHaveBeenUpdated');
        
    }
           
    
        

//         public function exportToTxt()
// {
//     $fileName = 'clients.txt';
//     $headers = array('Content-Type' => 'text/plain', 'Content-Disposition' => 'attachment; filename=' . $fileName);

//     $this->clientsForCSV = $this->searchMode ? Cliente::where($this->searchParam, 'like', '%' . $this->searchTerm . '%')->get() : Cliente::all();

//     $callback = function () {
//         $file = fopen('php://output', 'w');

//         foreach ($this->clientsForCSV as $client) {
//             $database = new DataBase('rb3' . $client->nombre);
//             $activos =  $database->totalActivos();
//             $inactivos = $database->totalInactivos();
//             $moduloMaximo = $this->obtenerMaximoPorCliente($client->nro_cliente);

//             // Format data for text file
//             $line = sprintf(
//                 "%s\t%s\t%s\t%s\t%s\t%s\n",
//                 $client->nro_cliente,
//                 $client->nombre,
//                 $activos > $moduloMaximo ? 5029 : 5027,
//                 $moduloMaximo,
//                 $activos,
//                 $inactivos
//             );

//             // Write to file
//             fwrite($file, $line);
//         }

//         fclose($file);
//     };

//     return response()->stream($callback, 200, $headers);
// }



public function exportToTxt()
{
    $fileName = 'clients.txt';
    $headers = array('Content-Type' => 'text/plain', 'Content-Disposition' => 'attachment; filename=' . $fileName);

    $this->clientsForCSV = $this->searchMode ? Cliente::where($this->searchParam, 'like', '%' . $this->searchTerm . '%')->get() : Cliente::all();

    $callback = function () {
        $file = fopen('php://output', 'w');

        foreach ($this->clientsForCSV as $client) {
            $database = new DataBase('rb3' . $client->nombre);
            $activos =  $database->totalActivos();
            $inactivos = $database->totalInactivos();
            $moduloMaximo = $this->obtenerMaximoPorCliente($client->nro_cliente);

            // Format data for text file
            $line = sprintf(
                "%s\t%s\t%s\t%s\t%s\t%s\n",
                $client->nro_cliente,
                $client->nombre,
                $activos > $moduloMaximo ? 5029 : 5027,
                $moduloMaximo,
                $activos,
                $inactivos
            );

            // Write to file
            fwrite($file, $line);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}


// public function convert($file)
//     {
      

        
       
//         // Descargar el archivo
//         return response()->download($txtFilePath)->deleteFileAfterSend(true);
//     }

    private function convertToTabDelimitedTxt($collection)
    {
        $txtContent = '';

        foreach ($collection as $row) {
            $txtContent .= implode("\t", $row);
            $txtContent .= "\n";
        }

        return $txtContent;
    }



public function darAltaFinal()
{

    // if (Cliente::whereNull('modulo')->exists()) {
    //     $this->openAndCloseNullModuloModal();
    //     return;
    // }


   $this->openAndCloseAdvertenciaModal();


  $facturaciones = $this->searchMode ? Cliente::where($this->searchParam, 'like', '%' . $this->searchTerm . '%')->get() : Cliente::all();


   foreach($facturaciones as $fac){



        $database = new DataBase($fac->database);
        $activos = $fac->separated ? $database->totalActivosPorEmpresa($fac->nombre_fantasia) : $database->totalActivos();
        $inactivos = $fac->separated ? $database->totalActivosPorEmpresa($fac->nombre_fantasia, 'inactivo') : $database->totalInactivos();
        $remito = $fac->modulo < $activos ? 5027 : 5029;
        $codigo = Codigo::where('codigo', '=', $remito)->get();
        $servicio = Servicio::find(1);   
         $periodo = now()->format('F Y');
        
         $existingFac = Facturacion::where('nro_cliente', $fac->nro_cliente)->first();

         if ($existingFac) {
             // Si existe, actualiza el registro existente
             $existingFac->update([
                 'id_servicio' => $servicio->id,
                 'periodo' => $periodo,
                 'cantidad_activos' => $activos,
                 'cantidad_inactivos' => $inactivos,
                 'remito' => $remito,
                 'modulo' => $fac->modulo,
             ]);
         } else {
             // Si no existe, crea un nuevo registro
             $newFac = new Facturacion([
                 'nro_cliente' => $fac->nro_cliente,
                 'id_servicio' => $servicio->id,
                 'periodo' => $periodo,
                 'cantidad_activos' => $activos,
                 'cantidad_inactivos' => $inactivos,
                 'remito' => $remito,
                 'modulo' => $fac->modulo,
             ]);
 
             $newFac->save();
         }
     }
    


   $newHistorial = new Historial([
    'periodo_name' => now()->format('F Y'),
 ]);

   $newHistorial->save();


//    


    $csvContent = '';
    $txtContent = '';
    
    $this->clientsForCSV = $this->searchMode ? Cliente::where($this->searchParam, 'like', '%' . $this->searchTerm . '%')->get() : Cliente::all();

   // Encabezados para CSV
   $csvContent .= implode(';', ['Cliente', 'Razon Social', 'Remito', 'Modulo maximo', 'Activos', 'Inactivos']) . "\n";

   // Agrupa los clientes por nro_cliente
   $groupedClients = $this->clientsForCSV->groupBy('nro_cliente');
   
   foreach ($groupedClients as $nroCliente => $clients) {
       $activosTotal = 0;
       $inactivosTotal = 0;
       $moduloMaximoTotal = 0;
       $razonSocial = '';
   
       // Itera sobre los clientes con el mismo nro_cliente
       foreach ($clients as $client) {
           $database = new DataBase($client->database);
           $activosTotal += $client->separated ? $database->totalActivosPorEmpresa($fac->nombre_fantasia) : $database->totalActivos();
           $inactivosTotal += $client->separated ? $database->totalActivosPorEmpresa($fac->nombre_fantasia, 'inactivo') : $database->totalInactivos();
           $moduloMaximoTotal = $client->modulo;
   
           // Concatena los nombres solo si hay más de un nombre
           if ($clients->count() > 1) {
               $razonSocial .= empty($razonSocial) ? $client->nombre : ' - ' . $client->nombre;
           } else {
               $razonSocial = $client->nombre; // Si solo hay un nombre, asignamos directamente
           }
       }
   
       // Determina el remito basado en la lógica de tu código original
       $remito = $activosTotal > $moduloMaximoTotal ? 5029 : 5027;
   
       // Contenido para CSV
       $csvContent .= implode(';', [
           $nroCliente,
           $razonSocial,
           $remito,
           $moduloMaximoTotal,
           $activosTotal,
           $inactivosTotal
       ]) . "\n";
   }
   



// Ruta para el archivo CSV
$csvFilePath = storage_path('app/excel-imports/exportCsv.csv');
file_put_contents($csvFilePath, $csvContent);

// Convertir el contenido del CSV a un array
$collection = Excel::toArray([], $csvFilePath);

// Convertir el array a un archivo de texto con formato tabulado
$txtContent = $this->convertToTabDelimitedTxt($collection[0]);

// Ruta para el archivo TXT
$txtFilePath = storage_path('app/exportTxt.txt');
file_put_contents($txtFilePath, $txtContent);


   

//     // Crear archivo TXT
//     $txtFilePath = storage_path('app/exportTxt.txt');
//     file_put_contents($txtFilePath, $txtContent);

    // Comprimir archivos en ZIP
    $zip = new \ZipArchive();
    $zipFilePath = storage_path('app/exports.zip');
    
    if ($zip->open($zipFilePath, \ZipArchive::CREATE) === true) {
        $zip->addFile($csvFilePath, 'clients.csv');
        $zip->addFile($txtFilePath, 'clients.txt');
        $zip->close();

        $this->emit('backToDashboard');
 
        // Descargar archivo ZIP
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    } else {
        // Manejar error al abrir el archivo ZIP
        return response()->json(['error' => 'No se pudo crear el archivo ZIP'], 500);
    }
}







    public function render()
    {


        $clientsResults = null;

    if ($this->searchMode) {
        $clientsResults = $this->model::where($this->searchParam, 'like', '%' . $this->searchTerm . '%')->paginate(10);
    }



        return view(
            'livewire.facturation-table.facturation-table', 
            [
                'totalElements' => Cliente::all()->count(),
                'clients' => Cliente::all()->toArray(),
                'clientsPaginated' =>  Cliente::paginate(10),
                 'clientsResults' =>  $clientsResults,
                 'totalResultsElements' => Cliente::where($this->searchParam, 'like', '%' . $this->searchTerm . '%')->count(),
            ]
            );
        
    }
}