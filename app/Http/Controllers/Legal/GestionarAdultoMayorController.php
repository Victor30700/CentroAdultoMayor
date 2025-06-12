<?php

namespace App\Http\Controllers\Legal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdultoMayor;

class GestionarAdultoMayorController extends Controller
{
    /**
     * Muestra la lista de adultos mayores para el rol Legal.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Aquí puedes agregar la lógica para obtener los datos necesarios.
        // Por ahora, solo retornamos la vista.
        $adultosMayores = AdultoMayor::all(); // O la consulta que necesites
        return view('pages.legal.GestionarAdultoMayor.index', compact('adultosMayores'));
    }

    // Aquí puedes agregar otros métodos como show, edit, update, etc. si son necesarios.
}

