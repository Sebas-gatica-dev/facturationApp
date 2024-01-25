<?php

namespace App\Http\Livewire\Servicios;

use Livewire\Component;
use App\Models\Servicio;
use App\Models\ServicioCodigo;
use App\Models\Codigo;
use App\Models\Cliente;
use Livewire\WithPagination;




class Servicios extends Component
{


    use WithPagination;

   public $model;
   public $customErrors = [];



    public $showCreateServicioModal = false;
    public $showEditServicioModal = false;
    public $showUpdateServicioModal = false;



    public $showAddServicioModal = false;
    public $showEditAddedServicioModal = false;
    


    public $showCreateCodigoModal = false;
    public $showEditCodigoModal = false;
    public $showUpdateCodigoModal = false;
    


    public function openCreateServicioModal() {
        $this->showCreateServicioModal = true;
    }
    
    public function closeCreateServicioModal() {
        $this->showCreateServicioModal = false;
    }








    public function openEditServicioModal() {
        $this->showEditServicioModal = true;
    }
    
    public function closeEditServicioModal() {
        $this->showEditServicioModal = false;
    }

    
    public function openUpdateServicioModal() {
        $this->showUpdateServicioModal = true;
    }

    public function closeUpdateServicioModal() {
        $this->showUpdateServicioModal = false;
    }









    public function openCreateCodigoModal() {
        $this->showCreateCodigoModal = true;
    }
    
    public function closeCreateCodigoModal() {
        $this->showCreateCodigoModal = false;
    }



    public function openEditCodigoModal() {
        $this->showEditCodigoModal = true;
    }
    
    public function closeEditCodigoModal() {
        $this->showEditCodigoModal = false;
    }

    public function openUpdateCodigoModal(){
       $this->showUpdateCodigoModal = true;        
    }

    public function closeUpdateCodigoModal(){
        $this->showUpdateCodigoModal = false;        
     }






    public function openCreateServicioCodigoModal() {
        $this->showAddServicioModal = true;
    }
    
    public function closeCreateServicioCodigoModal() {
        $this->showAddServicioModal = false;
    }
    
    public $editingServicioCodigo = null;

    public function openEditServicioCodigoModal($servicioCodigoId) {
        $this->editingServicioCodigo = ServicioCodigo::find($servicioCodigoId);
        $this->formCreateServicioCodigo['id_servicio'] = $this->editingServicioCodigo->id_servicio;
        $this->formCreateServicioCodigo['codigo'] = $this->editingServicioCodigo->codigo;
        $this->showAddServicioModal = true;
    }
    
    public function closeEditServicioCodigoModal() {
        $this->showEditAddedServicioModal = false;
    }





    public $selectedItems = [];



    public $searchTerm = '';
    public $searchParam = 'servicio';
    public $searchMode = false;
    protected $serviciosResults;



    public $serviciosForCSV;
    public $serviciosGrouped;


    public $editServicioId;
    public $editCodigoId;
    public $editServicioCodigoId;

    public $formServicioEdit;



    public $formCreateServicio = [
        'servicio' => '',
        'descripcion' => '',
    ];

    public $formUpdateServicio = [
        'servicio' => '',
        'descripcion' => '',
    ];







    public $formCreateCodigo = [
        'codigo' => '',
        'description' => '',
    ];

    
    public $formUpdateCodigo = [
        'codigo' => '',
        'description' => '',
    ];







    public $formCreateServicioCodigo = [
        'id_servicio' => '',
        'codigo' => '',
    ];

    public $formUpdateServicioCodigo = [
        'id_servicio' => '',
        'codigo' => '',
    ];

    public function mount()
    {
        $this->formServicioEdit = Servicio::all()->toArray();
    }



    protected $listeners = [
        'itemsHasBeenUpdated' => 'render',
        'createServicio' => 'createServicio',
        'createCodigo' => 'createCodigo',
        'editServicio' => 'editServicio',
        'editCodigo' => 'editCodigo',
        'createServicioCodgio' => 'createServicioCodigo',
        'editServicioCodigo' => 'editServicioCodigo',
        
        
    ];


    public function getServiciosResultsProperty()
{
   
        return $this->model::where($this->searchParam, 'like', '%' . $this->searchTerm . '%')->paginate(10);
    

}


public function search()
{
    $this->model = ServicioCodigo::class;
    $this->serviciosResults = $this->model::where($this->searchParam, 'like', '%' . $this->searchTerm . '%')->paginate(10);
    $this->searchMode = true;
    $this->resetPage();



}

public function cancelSearch()
    {
        $this->searchMode = false;
        $this->searchTerm = '';
        $this->mount();
    }






    public function deleteServicio($id)
    {
        Servicio::where('id', $id)->delete();
        // $this->emit('itemsHaveBeenUpdated');

    }



    public function editServicio($id)
    {
        $servicio = Servicio::find($id);
        // dd($servicio);

        // Carga los datos del cliente en el formulario
        $this->formUpdateServicio['servicio'] = $servicio->servicio;
        $this->formUpdateServicio['descripcion'] = $servicio->descripcion;

        // $this->emit('itemsHaveBeenUpdated');

        // Guarda el ID del cliente que se está editando
        $this->editServicioId = $id;
          
        // dd($client, $this->form, $this->editClientId);

       $this->openUpdateServicioModal();
    }


    public function updateServicio()
    {
        // Valida los datos del formulario
        $this->validate([
            'formUpdateServicio.servicio' => 'required',
            'formUpdateServicio.descripcion' => 'required',
        ]);

        // Encuentra al cliente en la base de datos
        $servicio = Servicio::find($this->editServicioId);

        // dd($client);

        // Actualiza los datos del cliente
        $servicio->update($this->formUpdateServicio);



        // Cierra el modal
        $this->closeUpdateServicioModal();

        // Emite un evento para indicar que los datos del cliente han sido actualizados
        $this->emit('itemsHaveBeenUpdated');
    }

    

    public function createServicio()
    {


   
       // Almacenar los clientes en el modelo Client
           $newServicio = new Servicio([
               'servicio' => $this->formCreateServicio['servicio'],
               'descripcion' => $this->formCreateServicio['descripcion'],
           

               //hay que aclarar que campos hayq ue intorducir realmente
           ]);
           $newServicio->save();



    
           $this->closeCreateServicioModal();

           $this->formCreateServicio = [
            'servicio' => '',
            'descripcion' => '', 
           ];   
           
    }










    public function createCodigo()
    {


   
       // Almacenar los clientes en el modelo Client
           $newCodigo = new Codigo([
               'codigo' => $this->formCreateCodigo['codigo'],
               'description' => $this->formCreateCodigo['description'],
           

               //hay que aclarar que campos hayq ue intorducir realmente
           ]);
           $newCodigo->save();



    
           $this->closeCreateCodigoModal();

           $this->formCreateCodigo = [
            'servicio' => '',
            'description' => '', 
           ];   
           
    }





    public function editCodigo($id)
    {
        $codigo = Codigo::find($id);
     

        // Carga los datos del cliente en el formulario
        $this->formUpdateCodigo['codigo'] = $codigo->codigo;
        $this->formUpdateCodigo['description'] = $codigo->description;

       
 
        

        // Guarda el ID del cliente que se está editando
        $this->editCodigoId = $id;
          
        // dd($client, $this->form, $this->editClientId);

        $this->openUpdateCodigoModal();


    }



    public function updateCodigo()
    {
        // Valida los datos del formulario
        $this->validate([
            'formUpdateCodigo.codigo' => 'required',
            'formUpdateCodigo.description' => 'required',
        ]);

        // Encuentra al cliente en la base de datos
        $codigo = Codigo::find($this->editCodigoId);

        //  dd($codigo);

        // Actualiza los datos del cliente
        $codigo->update($this->formUpdateCodigo);



        // Cierra el modal
        $this->closeUpdateCodigoModal();

        // Emite un evento para indicar que los datos del cliente han sido actualizados
        $this->emit('itemsHaveBeenUpdated');
    }

public function deleteCodigo($id)
    {
        Codigo::where('codigo', $id)->delete();
        // $this->emit('itemsHaveBeenUpdated');

    }












    public function createServicioCodigo()
    {

     $this->customErrors = [];


     $existingServicioCodigo = ServicioCodigo::where('id_servicio', $this->formCreateServicioCodigo['id_servicio'])
        ->where('codigo', $this->formCreateServicioCodigo['codigo'])->first();



        if($existingServicioCodigo) {
            $this->customErrors['existingServicioCodigo'] = 'Esa adhesión ya está creada.';
            return;
        }

  
    $this->validate([
        'formCreateServicioCodigo.id_servicio' => 'required',
        'formCreateServicioCodigo.codigo' => 'required',
    ]);


   // Almacenar los clientes en el modelo Client
       $newServicioCodigo = new ServicioCodigo([
           'id_servicio' => $this->formCreateServicioCodigo['id_servicio'],
           'codigo' => $this->formCreateServicioCodigo['codigo'],
       

           //hay que aclarar que campos hayq ue intorducir realmente
       ]);
       $newServicioCodigo->save();




       $this->closeCreateServicioCodigoModal();

       $this->formCreateServicioCodigo = [
        'id_servicio' => '',
        'codigo' => '', 
       ];   
       
  

        
    }




    public function editServicioCodigo($id)
    {
        $client = Cliente::find($id);
     

        // Carga los datos del cliente en el formulario
        // $this->formServicio['servicio'] = $client->servicio;
        // $this->formServicio['descripcion'] = $client->descripcion;

        // $this->emit('itemsHaveBeenUpdated');

        // Guarda el ID del cliente que se está editando
        $this->editServicioId = $id;
          
        // dd($client, $this->form, $this->editClientId);


    }


    public function updateServicioCodigo()
{
    $this->validate([
        'formCreateServicioCodigo.id_servicio' => 'required',
        'formCreateServicioCodigo.codigo' => 'required',
    ]);

    $this->editingServicioCodigo->update($this->formCreateServicioCodigo);

    $this->closeCreateServicioCodigoModal();
}

    // public function updateServicioCodigo()
    // {
    //     // Valida los datos del formulario
    //     $this->validate([
    //         'formServicio.nombre' => 'required',
    //         'formServicio.nro_cliente' => 'required',
    //     ]);

    //     // Encuentra al cliente en la base de datos
    //     $client = Cliente::find($this->editClientId);


    //     // ServicioCodigo::where('id_servicio', $idServicio)->where('codigo', $codigo)->update($newData);
    //     // dd($client);

    //     // Actualiza los datos del cliente
    //     $client->update($this->form);



    //     // Cierra el modal
    //     $this->closeModal();

    //     // Emite un evento para indicar que los datos del cliente han sido actualizados
    //     $this->emit('itemsHaveBeenUpdated');
    // }


   

    public function deleteServicioCodigo($idServicio, $codigo)
    {
    
        ServicioCodigo::where('id_servicio', $idServicio)->where('codigo', $codigo)->delete();

        // $this->emit('itemsHaveBeenUpdated');
        $this->emit('$refresh');

    }


    public function render()
    {
        // $servicios = Servicio::with('codigos')->get();
        $serviciosCodigosArray = ServicioCodigo::all()->toArray();
        $serviciosCodigos = ServicioCodigo::all()->groupBy('id_servicio');
        // dd($serviciosCodigos);
        
        $clientes = Cliente::all();
        $servicios = Servicio::all();
        $codigos = Codigo::all();
        $serviciosResults = Servicio::where($this->searchParam, 'like', '%' . $this->searchTerm . '%')->get();

        //  dd($serviciosResults, $codigos, $clientes);  

        return view('livewire.servicios.servicios',
        [
            'serviciosCodigosArray' => $serviciosCodigosArray,
            'serviciosCodigos' => $serviciosCodigos,
            'servicios' => $servicios,
            'codigos' => $codigos,
            'totalElements' => Servicio::all()->count(),
            'clients' => Cliente::all()->toArray(),
            'serviciosCodigosTotal' => ServicioCodigo::all()->count(),
            'clientsPaginated' =>  Cliente::paginate(10),
             'clientsResults' =>  $serviciosResults,
             'totalResultsElements' => Servicio::where($this->searchParam, 'like', '%' . $this->searchTerm . '%')->count(),
        ]
        );
    }

  
   
}
