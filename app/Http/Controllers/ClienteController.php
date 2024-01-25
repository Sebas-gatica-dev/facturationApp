<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    //
    public function index()
    {
        return view('clientes.index');
    }
    

    public function store(Request $request){
        // Validar los datos del request
        $request->validate([
            'clientes.*.nombre' => 'required',
            'clientes.*.email' => 'required|email',
        ]);
    
        // Crear los nuevos clientes
        foreach ($request->clientes as $clienteData) {
            $client = Cliente::create($clienteData);
        }
    
        // Redirigir al usuario a la página de clientes con un mensaje de éxito
        return redirect()->route('clientes.index')->with('success', 'Clientes creados con éxito.');
    }
}
