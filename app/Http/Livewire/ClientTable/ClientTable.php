<?php

namespace App\Http\Livewire\ClientTable;

use App\Models\Cliente;
use App\Models\ExternalClient;
use Livewire\Component;
use Livewire\WithPagination;
use App\Bank\DataBase;
use Illuminate\Support\Facades\File;

class ClientTable extends Component
{
    use WithPagination;

    public $selectionMode = false;
    public $selectedItems = [];
    public $searchTerm = '';
    public $searchParam = 'nombre';
    public $searchMode = false;
    protected $clientsResults;
    public $selectedClients = [];
    public $moduloData = [];
    public $listaDeNombres;
    public $showCreateSeparadosModal = false;
    public $listaDeEmpresas = [];
    public $empresasCliente = [];
    protected $listeners = [
        'showSeparadosModal' => 'openAndCloseSeparadosModal',
        'itemsHaveBeenUpdated' => 'render',

    ];
    public $nombreEmpresa = '';
    public $numeroDeCliente = '';
    public $listaNombresFantasiaCliente;

public function mount()
{
    $this->listaNombresFantasiaCliente = Cliente::pluck('nombre_fantasia')->toArray();
}
    public function openAndCloseSeparadosModal(){
         
       
        
        $this->showCreateSeparadosModal = !$this->showCreateSeparadosModal;

        
    } 

    public function getModuloMaximo($nro_cliente)
    {
        return $this->modulosMaximos[$nro_cliente];
    }


    public function comprobarEmpresasFiltradas($nombre_cliente){
       
        $database = new Database('rb3'.$nombre_cliente);

        $empresasFiltradas = $database->obtenerEmpresasFiltradas();
    
        return $empresasFiltradas;
    }

    public function listaDeEmpresas($nombre_cliente){

        $database = new Database('rb3'.$nombre_cliente);
 
        
 
        $empresas = $database->obtenerEmpresas();
 
         
        return $empresas;
 
     }


    public function comprobarMasDeUnaEmpresa($nombre_cliente){

       $database = new Database('rb3'.$nombre_cliente);

       

       $empresas = $database->cantidadDeEmpresas();

        
       return $empresas;

    }

    public function obtenerEmpresas($nombre_cliente,$nro_cliente)
    {
        $database = new Database('rb3'.$nombre_cliente);
        $this->nombreEmpresa = $nombre_cliente;
        $this->numeroDeCliente = $nro_cliente;
        // Obtener la lista de empresas


        $this->empresasCliente = Cliente::pluck('nombre_fantasia')->toArray();

        $this->listaDeEmpresas = $database->obtenerEmpresas();

    

         $this->listaDeEmpresas = array_filter($this->listaDeEmpresas, function ($empresa) {
            return !in_array($empresa['nombre_fantasia'], $this->empresasCliente);
        });

        // dd($this->listaDeEmpresas, $this->empresasCliente);



        // Generar dinámicamente el array $this->moduloData
        $this->moduloData = array_fill_keys(
            array_column($this->listaDeEmpresas, 'nombre_fantasia'),
            null
        );
    
        // Imprimir para verificar
        // dd($this->listaDeEmpresas, $this->moduloData);
    
        // Mostrar o cerrar el modal según sea necesario
        $this->openAndCloseSeparadosModal();
    }
    

    public function separarClientes()
    {
        foreach ($this->listaDeEmpresas as $empresa) {
            // Obtener el número ingresado para la empresa actual
            $modulo = $this->moduloData[$empresa['nombre_fantasia']];
    
            // Si $modulo es null o está vacío, asignar un valor específico
            if ($modulo === null && $empresa['nombre_fantasia'] == 'FARMACIA DEL NVO.PASEO DE PILAR SOC.COMANDITA SIM.') {


                if (!isset($this->moduloData['nombre_fantasia'])) {
                    continue;
                }
                 
                $modulo = $this->moduloData['FARMACIA DEL NVO']['PASEO DE PILAR SOC']['COMANDITA SIM'][''];
            }
            
            if ($modulo === null) {
                continue;
            }
            $formData = $this->moduloData;
    
            // Crear un nuevo Cliente con el número ingresado
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
    



    private function obtenerClientesNombres()
    {
        $filePath = storage_path('json/clientes.json');

        if (File::exists($filePath)) {
            $jsonContents = File::get($filePath);
            $clientes = json_decode($jsonContents, true);

            if (!empty($clientes)) {
                return array_column($clientes, 'nombre');
            } else {
                $this->error('El archivo JSON está vacío o no contiene objetos.');
            }
        } else {
            $this->error('El archivo JSON no existe.');
        }
    }

    private function obtenerClientes()
    {
        $filePath = storage_path('json/clientes.json');

        if (File::exists($filePath)) {
            $jsonContents = File::get($filePath);
            $clientes = json_decode($jsonContents, true);

            if (!empty($clientes)) {
                $clientesConMaximo = [];

                foreach ($clientes as $cliente) {
                    $maximoDesdeJSON = $this->obtenerMaximoPorCliente($cliente['cliente']);
                    $cliente['maximo'] = $maximoDesdeJSON;
                    $clientesConMaximo[] = $cliente;
                }

                return $clientesConMaximo;
            } else {
                $this->error('El archivo JSON está vacío o no contiene objetos.');
            }
        } else {
            $this->error('El archivo JSON no existe.');
        }
    }

    public function toggleClientSelection($clientData)
    {
        $clientData = json_decode($clientData, true);

        if (in_array(json_encode($clientData), $this->selectedClients)) {
            $this->selectedClients = array_diff($this->selectedClients, [json_encode($clientData)]);
        } else {
            $this->selectedClients[] = json_encode($clientData);
        }
    }

    public function addToFacturacion()
    {
        $selectedClientsData = [];

        foreach ($this->selectedClients as $client) {
            $clientData = json_decode($client, true);
            $selectedClientsData[] = [
                'nro_cliente' => $clientData['num_cliente'],
                'nombre' => $clientData['nombre'],
            ];
        }

        foreach ($selectedClientsData as $clientData) {
            $clientDataS = ExternalClient::where('nro_cliente', $clientData['nro_cliente'])->get();

            foreach ($clientDataS as $clientDataFinal) {
                $dataBase = new DataBase('rb3'. $clientDataFinal['nombre']);
                $statusCliente = $dataBase->getStatusClientePorNombre($clientDataFinal['nombre']);
                
                $newClient = new Cliente([
                    'nro_cliente' => $clientDataFinal['nro_cliente'],
                    'nombre' => $clientDataFinal['nombre'],
                    'database' => 'rb3'. $clientDataFinal['nombre'],
                    'estado' => $statusCliente ? $statusCliente : 'indefinido',
                    'modulo' => null,
                ]);

                $newClient->save();
            }
        }

        $this->selectedClients = [];
    }

    public function cancelSearch()
    {
        $this->searchMode = false;
        $this->searchTerm = '';
    }

    public function getClientsResultsProperty()
    {
        return ExternalClient::whereNotIn('nombre', Cliente::pluck('nombre')->toArray())
            ->where($this->searchParam, 'like', '%' . $this->searchTerm . '%')->paginate(10);
    }

    public function search()
    {
        if (!empty($this->searchTerm)) {
            $this->clientsResults = ExternalClient::whereNotIn('nombre', Cliente::pluck('nombre')->toArray())
                ->whereNotIn('nro_cliente',Cliente::pluck('separated_reference')->toArray())
                ->where($this->searchParam, 'like', $this->searchTerm . '%')
                ->orWhere($this->searchParam, 'like', '%' . $this->searchTerm . '%')
                ->paginate(10);

            $this->searchMode = true;
            $this->resetPage();
        }
    }


    // public function search()
    // {
    //     if (!empty($this->searchTerm)) {
    //         $query = ExternalClient::whereNotIn('nombre', Cliente::pluck('nombre')->toArray())
    //             ->where(function ($q) {
    //                 $q->where($this->searchParam, 'like', $this->searchTerm . '%')
    //                   ->orWhere($this->searchParam, 'like', '%' . $this->searchTerm . '%');
    //             });
    
    //         if ($this->searchParam === 'nro_cliente') {
    //             // Excluir nro_clientes presentes en al menos 1 elemento del modelo Cliente
    //             $excludedNroClientes = Cliente::pluck('separated_reference')->toArray();
    
    //             $query->whereNotIn('nro_cliente', $excludedNroClientes);
    //         }
    
    //         $this->clientsResults = $query->paginate(10);
    
    //         $this->searchMode = true;
    //         $this->resetPage();
    //     }
    // }

    public function render()
{
    // Obtener los nro_cliente de los clientes separados
    $separatedReferences = Cliente::pluck('separated_reference')->toArray();
    $namesExternalClients = ExternalClient::pluck('nombre')->toArray();
    
    // foreach($namesExternalClients as $name){
            
    //     $dataBase = new DataBase('rb3'. $name);
        
        

    // }

    $separatedReferences = array_map('intval', $separatedReferences);

    $clients = ExternalClient::where('nro_cliente', '>', 0)
        ->whereNotIn('nombre', Cliente::pluck('nombre')->toArray())
        // ->whereNotIn('nro_cliente', $separatedReferences)
        ->orderBy('nro_cliente', 'desc')
        ->paginate(10);
    // dd($clients);


    $clientsResults = null;

    if ($this->searchMode) {
        $clientsResults = ExternalClient::where('nro_cliente', '>', 0)
            ->whereNotIn('nombre', Cliente::pluck('nombre')->toArray())
            // ->whereNotIn('nro_cliente', $separatedReferences)
            ->where(function ($query) {
                $query->where($this->searchParam, 'like', '%' . $this->searchTerm . '%')
                    ->orWhere($this->searchParam, 'like', '%' . $this->searchTerm . '%');
            })
            ->paginate(10);
    }

    return view('livewire.client-table.client-table', [
        'clientsIterable' => $clients,
        'clientsResultsIterable' =>  $clientsResults,
    ]);
}




    


}
