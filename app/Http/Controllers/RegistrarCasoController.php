<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdultoMayor;
use App\Models\ActividadLaboral;
use App\Models\Encargado;
use App\Models\PersonaNatural;
use App\Models\PersonaJuridica;
use App\Models\Denunciado;
use App\Models\GrupoFamiliar;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RegistrarCasoController extends Controller
{
    /**
     * Lista de adultos mayores registrados
     */
    public function index()
    {
        $adultos = AdultoMayor::with('persona')->get();
        return view('Proteccion.indexPro', compact('adultos'));
    }

    /**
     * Formulario en tabs para registrar actividad laboral y encargado
     */
    public function show($id_adulto)
    {
        $adulto = AdultoMayor::with('persona')->findOrFail($id_adulto);
        return view('Proteccion.registrarCaso', compact('adulto'));
    }
    // VER DETALLE DE TODO EL CASO
    public function showDetalle($id_adulto)
    {
        $adulto = AdultoMayor::with([
            'persona',
            'actividadLaboral',
            'encargados.personaNatural',
            'encargados.personaJuridica',
        ])->findOrFail($id_adulto);

        $encargado = $adulto->encargados->first();

        return view('Proteccion.verDetalleCaso', compact('adulto', 'encargado'));
    }

    /**
     * Guardar actividad laboral
     */
// -------------------------------------------------------------------------------------------------------------------------------- 
    public function storeActividadLaboral(Request $request, $id_adulto)
    {
        $request->validate([
            'nombre_actividad' => 'required|string|max:255',
            'direccion_trabajo' => 'nullable|string|max:255',
            'telefono_trabajo' => 'nullable|string|max:20',
            'horas_x_dia' => 'nullable|string|max:50',
            'ingreso_men_aprox' => 'nullable|string|max:100',
        ]);

        ActividadLaboral::create([
            'id_adulto' => $id_adulto,
            'nombre_actividad' => $request->nombre_actividad,
            'direccion_trabajo' => $request->direccion_trabajo,
            'telefono_trabajo' => $request->telefono_trabajo,
            'horas_x_dia' => $request->horas_x_dia,
            'ingreso_men_aprox' => $request->ingreso_men_aprox,
        ]);

        return back()->with('success', 'Actividad laboral registrada correctamente.');
    }

    /**
     * Guardar encargado
     */
    public function storeEncargado(Request $request, $id_adulto)
    {
        $request->validate([
            'tipo_encargado' => 'required|string|max:100',
        ]);

        Encargado::create([
            'id_adulto' => $id_adulto,
            'tipo_encargado' => $request->tipo_encargado,
        ]);

        return back()->with('success', 'Encargado registrado correctamente.');
    }
    public function storeCompleto(Request $request, $id_adulto)
{
    // Validación dinámica según tipo de encargado
    $rules = [
        // Actividad laboral (opcional)
        'nombre_actividad'     => 'nullable|string|max:255',
        'direccion_trabajo'    => 'nullable|string|max:255',
        'telefono_trabajo'     => 'nullable|string|max:20',
        'horas_x_dia'          => 'nullable|string|max:50',
        'ingreso_men_aprox'    => 'nullable|string|max:100',

        // Encargado (obligatorio)
        'tipo_encargado'       => 'required|in:natural,juridica',
    ];

    // Reglas para DENUNCIADO (siempre se registrará)
    $rules = array_merge($rules, [
        'nombres_den'             => 'required|string|max:255',
        'primer_apellido_den'     => 'required|string|max:100',
        'edad_den'                => 'required|integer|min:1|max:120',
        'ci_den'                  => 'required|string|max:20',
        'sexo_den'                => 'required|in:M,F',
        'descripcion_hechos'      => 'required|string|max:1000',
    ]);

    // Grupo Familiar (uno solo por ahora)
    $rules = array_merge($rules, [
        'apellido_paterno'   => 'required|string|max:100',
        'apellido_materno'   => 'nullable|string|max:100',
        'nombres_fam'        => 'required|string|max:255',
        'parentesco'         => 'required|string|max:100',
        'edad_fam'           => 'required|integer|min:0|max:120',
        'ocupacion_fam'      => 'nullable|string|max:100',
        'direccion_fam'      => 'nullable|string|max:255',
        'telefono_fam'       => 'nullable|string|max:20',
    ]);


    // Reglas para persona natural (si aplica)
    if ($request->tipo_encargado === 'natural') {
        $rules = array_merge($rules, [
            'nombres_natural'         => 'required|string|max:255',
            'primer_apellido_natural' => 'required|string|max:100',
            'segundo_apellido_natural'=> 'nullable|string|max:100',
            'edad_natural'            => 'required|integer|min:1|max:120',
            'ci_natural'              => 'required|string|max:20',
            'telefono_natural'        => 'nullable|string|max:20',
            'direccion_domicilio'     => 'nullable|string|max:255',
            'relacion_parentesco'     => 'nullable|string|max:100',
            'direccion_trabajo_nat'   => 'nullable|string|max:255',
            'ocupacion'               => 'nullable|string|max:100',
        ]);
    }

    // Reglas para persona jurídica (si aplica)
    if ($request->tipo_encargado === 'juridica') {
        $rules = array_merge($rules, [
            'nombre_institucion'   => 'required|string|max:255',
            'direccion_juridica'   => 'required|string|max:255',
            'telefono_juridica'    => 'required|string|max:20',
            'nombre_funcionario'   => 'required|string|max:255',
        ]);
    }

    $validated = $request->validate($rules);

    DB::beginTransaction();

    try {
        // 1. Actividad Laboral (opcional)
        $hayActividad = $request->filled([
            'nombre_actividad',
            'direccion_trabajo',
            'telefono_trabajo',
            'horas_x_dia',
            'ingreso_men_aprox',
        ]);

        if ($hayActividad) {
            ActividadLaboral::create([
                'id_adulto'         => $id_adulto,
                'nombre_actividad'  => $request->nombre_actividad,
                'direccion_trabajo' => $request->direccion_trabajo,
                'telefono_trabajo'  => $request->telefono_trabajo,
                'horas_x_dia'       => $request->horas_x_dia,
                'ingreso_men_aprox' => $request->ingreso_men_aprox,
            ]);
        }

        // 2. Encargado
        $encargado = Encargado::create([
            'id_adulto' => $id_adulto,
            'tipo_encargado' => $request->tipo_encargado,
        ]);

        if ($request->tipo_encargado === 'natural') {
            PersonaNatural::create([
                'id_encargado'         => $encargado->id_encargado,
                'nombres'              => $request->nombres_natural,
                'primer_apellido'      => $request->primer_apellido_natural,
                'segundo_apellido'     => $request->segundo_apellido_natural,
                'edad'                 => $request->edad_natural,
                'ci'                   => $request->ci_natural,
                'telefono'             => $request->telefono_natural,
                'direccion_domicilio'  => $request->direccion_domicilio,
                'relacion_parentesco'  => $request->relacion_parentesco,
                'direccion_de_trabajo' => $request->direccion_trabajo_nat,
                'ocupacion'            => $request->ocupacion,
            ]);
        }

        if ($request->tipo_encargado === 'juridica') {
            PersonaJuridica::create([
                'id_encargado'        => $encargado->id_encargado,
                'nombre_institucion'  => $request->nombre_institucion,
                'direccion'           => $request->direccion_juridica,
                'telefono'            => $request->telefono_juridica,
                'nombre_funcionario'  => $request->nombre_funcionario,
            ]);
        }

        // 3. Denunciado (siempre se registra)
        $personaDen = PersonaNatural::create([
            'nombres'              => $request->nombres_den,
            'primer_apellido'      => $request->primer_apellido_den,
            'segundo_apellido'     => $request->segundo_apellido_den,
            'edad'                 => $request->edad_den,
            'ci'                   => $request->ci_den,
            'telefono'             => $request->telefono_den,
            'direccion_domicilio'  => $request->direccion_domicilio_den,
            'direccion_de_trabajo' => $request->direccion_trabajo_den,
            'ocupacion'            => $request->ocupacion_den,
        ]);

        Denunciado::create([
            'id_natural'         => $personaDen->id_natural,
            'sexo'               => $request->sexo_den,
            'descripcion_hechos' => $request->descripcion_hechos,
        ]);

        GrupoFamiliar::create([
            'id_adulto'         => $id_adulto,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'nombres'          => $request->nombres_fam,
            'parentesco'       => $request->parentesco,
            'edad'             => $request->edad_fam,
            'ocupacion'        => $request->ocupacion_fam,
            'direccion'        => $request->direccion_fam,
            'telefono'         => $request->telefono_fam,
        ]);
        DB::commit();

        return redirect()->route('admin.caso.index')->with('success', 'Registro de caso completo exitosamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error en registro de caso: ' . $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);
        return back()->withErrors(['error' => 'Error al guardar los datos.'])->withInput();
    }
}


    // Editar Caso
    public function edit($id_adulto)
    {
        $adulto = AdultoMayor::with([
            'persona',
            'actividadLaboral',
            'encargados.personaNatural',
            'encargados.personaJuridica',
        ])->findOrFail($id_adulto);

        // Solo usamos un encargado por ahora
        $encargado = $adulto->encargados->first();

        return view('Admin.registerCaso.registrarCaso', compact('adulto', 'encargado'));
    }

    // Actualizar Caso
    public function update(Request $request, $id_adulto)
    {
        $rules = [
            // Actividad laboral (opcional)
            'nombre_actividad'     => 'nullable|string|max:255',
            'direccion_trabajo'    => 'nullable|string|max:255',
            'telefono_trabajo'     => 'nullable|string|max:20',
            'horas_x_dia'          => 'nullable|string|max:50',
            'ingreso_men_aprox'    => 'nullable|string|max:100',

            // Encargado base
            'tipo_encargado'       => 'required|in:natural,juridica',
        ];

        if ($request->tipo_encargado === 'natural') {
            $rules = array_merge($rules, [
                'nombres_natural'         => 'required|string|max:255',
                'primer_apellido_natural' => 'required|string|max:100',
                'segundo_apellido_natural'=> 'nullable|string|max:100',
                'edad_natural'            => 'required|integer|min:1|max:120',
                'ci_natural'              => 'required|string|max:20',
                'telefono_natural'        => 'nullable|string|max:20',
                'direccion_domicilio'     => 'nullable|string|max:255',
                'relacion_parentesco'     => 'nullable|string|max:100',
                'direccion_trabajo_nat'   => 'nullable|string|max:255',
                'ocupacion'               => 'nullable|string|max:100',
            ]);
        }

        if ($request->tipo_encargado === 'juridica') {
            $rules = array_merge($rules, [
                'nombre_institucion'   => 'required|string|max:255',
                'direccion_juridica'   => 'required|string|max:255',
                'telefono_juridica'    => 'required|string|max:20',
                'nombre_funcionario'   => 'required|string|max:255',
            ]);
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();

        try {
            // ACTUALIZAR / INSERTAR actividad laboral
            $hayActividad = $request->filled([
                'nombre_actividad',
                'direccion_trabajo',
                'telefono_trabajo',
                'horas_x_dia',
                'ingreso_men_aprox',
            ]);

            if ($hayActividad) {
                $actividad = ActividadLaboral::firstOrNew(['id_adulto' => $id_adulto]);
                $actividad->fill([
                    'nombre_actividad'  => $request->nombre_actividad,
                    'direccion_trabajo' => $request->direccion_trabajo,
                    'telefono_trabajo'  => $request->telefono_trabajo,
                    'horas_x_dia'       => $request->horas_x_dia,
                    'ingreso_men_aprox' => $request->ingreso_men_aprox,
                ])->save();
            }

            // Encargado
            $encargado = Encargado::firstOrNew(['id_adulto' => $id_adulto]);
            $encargado->tipo_encargado = $request->tipo_encargado;
            $encargado->save();

            // NATURAL
            if ($request->tipo_encargado === 'natural') {
                $personaNatural = PersonaNatural::firstOrNew(['id_encargado' => $encargado->id_encargado]);
                $personaNatural->fill([
                    'nombres'              => $request->nombres_natural,
                    'primer_apellido'      => $request->primer_apellido_natural,
                    'segundo_apellido'     => $request->segundo_apellido_natural,
                    'edad'                 => $request->edad_natural,
                    'ci'                   => $request->ci_natural,
                    'telefono'             => $request->telefono_natural,
                    'direccion_domicilio'  => $request->direccion_domicilio,
                    'relacion_parentesco'  => $request->relacion_parentesco,
                    'direccion_de_trabajo' => $request->direccion_trabajo_nat,
                    'ocupacion'            => $request->ocupacion,
                ])->save();
            }

            // JURIDICA
            if ($request->tipo_encargado === 'juridica') {
                $personaJuridica = PersonaJuridica::firstOrNew(['id_encargado' => $encargado->id_encargado]);
                $personaJuridica->fill([
                    'nombre_institucion' => $request->nombre_institucion,
                    'direccion'          => $request->direccion_juridica,
                    'telefono'           => $request->telefono_juridica,
                    'nombre_funcionario' => $request->nombre_funcionario,
                ])->save();
            }

            DB::commit();

            return redirect()->route('admin.caso.index')->with('success', 'Caso actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error actualizando caso: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return back()->withErrors(['error' => 'Error al actualizar los datos.'])->withInput();
        }
    }

// VAC FALTA QUE CHAT REVISE LOS METODOS EDIT Y UPDATE PARA DENUNCIADO Y GRUPO FAMILIAR
// --------------------------------------------------------------------------------------------------------------------------------------------------

}
