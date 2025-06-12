<?php

namespace App\Http\Controllers\Legal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdultoMayor;
use App\Models\Persona; // Asegúrate que el namespace sea correcto
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator; // Usar Validator para más control
use Carbon\Carbon; // Para calcular la edad


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
    public function storeAdultoMayor(Request $request)
    {
        // Agregar logging para depuración
        Log::info('Iniciando registro de adulto mayor', ['data' => $request->all()]);

        $validator = Validator::make($request->all(), [
            // Pestaña 1: Datos Personales (tabla 'persona')
            'nombres' => 'required|string|max:255',
            'primer_apellido' => 'required|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'ci' => 'required|string|max:20|unique:persona,ci',
            'fecha_nacimiento' => 'required|date|before_or_equal:today',
            'sexo' => 'required|string|in:F,M,O',
            'estado_civil' => 'required|string|in:casado,divorciado,soltero,otro',
            'domicilio' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'zona_comunidad' => 'nullable|string|max:100',

            // Pestaña 2: Datos específicos de adulto mayor (tabla 'adulto_mayor')
            'discapacidad' => 'nullable|string',
            'vive_con' => 'nullable|string|max:200',
            'migrante' => 'nullable|in:0,1',
            'nro_caso' => 'nullable|string|max:50|unique:adulto_mayor,nro_caso',
            'fecha' => 'required|date',
        ], [
            // Mensajes personalizados
            'nombres.required' => 'El campo nombres es obligatorio.',
            'primer_apellido.required' => 'El campo primer apellido es obligatorio.',
            'ci.required' => 'El campo CI es obligatorio.',
            'ci.unique' => 'Este CI ya ha sido registrado.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento no puede ser futura.',
            'sexo.required' => 'El campo sexo es obligatorio.',
            'sexo.in' => 'El sexo debe ser Femenino, Masculino u Otro.',
            'estado_civil.required' => 'El estado civil es obligatorio.',
            'estado_civil.in' => 'El estado civil debe ser uno de los valores válidos.',
            'domicilio.required' => 'El domicilio es obligatorio.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'nro_caso.unique' => 'Este número de caso ya ha sido registrado.',
            'fecha.required' => 'La fecha de registro es obligatoria.',
            'fecha.date' => 'La fecha de registro debe ser una fecha válida.',
        ]);

        if ($validator->fails()) {
            Log::warning('Validación falló para registro de adulto mayor', ['errors' => $validator->errors()]);
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        DB::beginTransaction();

        try {
            // Calcular edad
            $edad = Carbon::parse($request->fecha_nacimiento)->age;
            
            Log::info('Calculando edad', ['fecha_nacimiento' => $request->fecha_nacimiento, 'edad' => $edad]);

            // 1. Crear Persona
            // Si estás usando modelos Eloquent y tienen $fillable configurado, puedes usar create.
            // Si no, el Query Builder como lo tenías está bien, pero Persona::create es más idiomático de Eloquent.
            $persona = Persona::create([
                'ci' => $request->ci,
                'primer_apellido' => $request->primer_apellido,
                'segundo_apellido' => $request->segundo_apellido,
                'nombres' => $request->nombres,
                'sexo' => $request->sexo,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'edad' => $edad,
                'estado_civil' => $request->estado_civil,
                'domicilio' => $request->domicilio,
                'telefono' => $request->telefono,
                'zona_comunidad' => $request->zona_comunidad,
                // 'created_at' y 'updated_at' usualmente son manejados por Eloquent automáticamente
            ]);

            Log::info('Persona creada exitosamente', ['persona_id' => $persona->id, 'ci' => $persona->ci]);

            // 2. Preparar datos para adulto_mayor
            $adultoMayorData = [
                'ci' => $request->ci, // Clave foránea hacia persona
                'discapacidad' => $request->discapacidad,
                'vive_con' => $request->vive_con,
                'migrante' => $request->migrante == '1' ? true : false, // Conversión explícita
                'fecha' => $request->fecha,
                // 'created_at' y 'updated_at' usualmente son manejados por Eloquent automáticamente
            ];

            // Solo agregar nro_caso si se proporcionó y no está vacío
            if ($request->filled('nro_caso')) {
                $adultoMayorData['nro_caso'] = $request->nro_caso;
            }

            // Si usas Eloquent para AdultoMayor y tiene una relación definida con Persona,
            // podrías hacer algo como $persona->adultoMayor()->create($adultoMayorData);
            // o si 'ci' es la clave primaria/única en adulto_mayor y también la FK:
            $adultoMayor = AdultoMayor::create($adultoMayorData);


            Log::info('Adulto mayor creado exitosamente', ['adulto_mayor_id' => $adultoMayor->id_adulto ?? $adultoMayor->id]); // Ajusta según tu PK

            DB::commit();

            Log::info('Transacción completada exitosamente');

            return redirect()->route('admin.gestionar-adultomayor.index') // Asumiendo que esta es tu ruta de listado
                                ->with('success', 'Adulto Mayor registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error registrando adulto mayor: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                // 'trace' => $e->getTraceAsString(), // Puede ser muy verboso para logs regulares
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()
                            ->withErrors(['error_registro' => 'Ocurrió un error interno al registrar al adulto mayor. Por favor, intente de nuevo. Detalles: ' . $e->getMessage()])
                            ->withInput();
        }
    }

    /**
    * Mostrar listado de adultos mayores
    */
    public function gestionarAdultoMayorIndex()
    {
        try {
            // Obtener todos los adultos mayores con sus datos de persona
            $adultosMayores = DB::table('adulto_mayor as am')
                ->join('persona as p', 'am.ci', '=', 'p.ci')
                ->select([
                    'p.ci',
                    'p.nombres',
                    'p.primer_apellido',
                    'p.segundo_apellido',
                    'p.sexo',
                    'p.fecha_nacimiento',
                    'p.edad',
                    'p.estado_civil',
                    'p.domicilio',
                    'p.telefono',
                    'p.zona_comunidad',
                    'am.id_adulto', // Clave primaria de adulto_mayor
                    'am.discapacidad',
                    'am.vive_con',
                    'am.migrante',
                    'am.nro_caso',
                    'am.fecha as fecha_registro'
                ])
                ->orderBy('p.primer_apellido', 'asc')
                ->orderBy('p.nombres', 'asc')
                ->paginate(10); // Paginación

            return view('Admin.gestionarAdultoMayor.index', compact('adultosMayores'));
            
        } catch (\Exception $e) {
            Log::error('Error al cargar listado de adultos mayores: ' . $e->getMessage());
            return redirect()->route('admin.dashboard') // O alguna otra ruta de fallback
                            ->with('error', 'Error al cargar el listado de adultos mayores.');
        }
    }

    /**
    * Buscar adultos mayores (AJAX)
    */
    public function buscarAdultoMayor(Request $request)
    {
        try {
            $busqueda = $request->get('busqueda', '');
            
            $query = DB::table('adulto_mayor as am')
                ->join('persona as p', 'am.ci', '=', 'p.ci')
                ->select([
                    'p.ci',
                    'p.nombres',
                    'p.primer_apellido',
                    'p.segundo_apellido',
                    'p.sexo',
                    'p.fecha_nacimiento',
                    'p.edad',
                    'p.estado_civil',
                    'p.domicilio',
                    'p.telefono',
                    'p.zona_comunidad',
                    'am.id_adulto',
                    'am.discapacidad',
                    'am.vive_con',
                    'am.migrante',
                    'am.nro_caso',
                    'am.fecha as fecha_registro'
                ]);

            if (!empty($busqueda)) {
                $query->where(function($q) use ($busqueda) {
                    $q->where('p.ci', 'ILIKE', '%' . $busqueda . '%') // ILIKE para PostgreSQL (case-insensitive)
                    ->orWhere('p.nombres', 'ILIKE', '%' . $busqueda . '%')
                    ->orWhere('p.primer_apellido', 'ILIKE', '%' . $busqueda . '%')
                    ->orWhere('p.segundo_apellido', 'ILIKE', '%' . $busqueda . '%')
                    // Para PostgreSQL, la concatenación es con ||
                    ->orWhereRaw("p.nombres || ' ' || p.primer_apellido || ' ' || COALESCE(p.segundo_apellido, '') ILIKE ?", ['%' . $busqueda . '%']);
                });
            }

            $adultosMayores = $query->orderBy('p.primer_apellido', 'asc')
                                    ->orderBy('p.nombres', 'asc')
                                    ->paginate(10); // Paginación también aquí

            // Devolver HTML de la tabla y de la paginación
            return response()->json([
                'success' => true,
                'html' => view('Admin.gestionarAdultoMayor.partials.tabla-adultos', compact('adultosMayores'))->render(),
                'pagination' => $adultosMayores->links()->toHtml(), // CORRECCIÓN AQUÍ
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en búsqueda de adultos mayores: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Error en la búsqueda. Detalles: ' . $e->getMessage()
            ], 500); // Es buena práctica devolver un código de error HTTP apropiado
        }
    }

    /**
    * Mostrar formulario de edición
    */
    public function editarAdultoMayor($ci) // Se recibe el CI de la persona
    {
        try {
            $adultoMayor = DB::table('persona as p') // Empezar por persona para obtener todos sus datos
                ->join('adulto_mayor as am', 'p.ci', '=', 'am.ci')
                ->select([
                    'p.*', // Todos los campos de persona
                    'am.id_adulto', // Clave primaria de adulto_mayor
                    'am.discapacidad',
                    'am.vive_con',
                    'am.migrante',
                    'am.nro_caso',
                    'am.fecha as fecha_registro_am' // Renombrar para evitar colisión con persona.fecha si existiera
                ])
                ->where('p.ci', $ci)
                ->first();

            if (!$adultoMayor) {
                return redirect()->route('admin.gestionar-adultomayor.index')
                                ->with('error', 'Adulto mayor no encontrado.');
            }
            
            // Convertir migrante a string '0' o '1' para el select del formulario si es necesario
            if (isset($adultoMayor->migrante)) {
                $adultoMayor->migrante = $adultoMayor->migrante ? '1' : '0';
            }


            return view('Admin.gestionarAdultoMayor.editar.editAdultoMayor', compact('adultoMayor'));
            
        } catch (\Exception $e) {
            Log::error('Error al cargar datos para edición: ' . $e->getMessage());
            return redirect()->route('admin.gestionar-adultomayor.index')
                            ->with('error', 'Error al cargar los datos del adulto mayor.');
        }
    }

    /**
    * Actualizar adulto mayor
    */
    public function actualizarAdultoMayor(Request $request, $ci_original) // $ci_original es el CI antes de la edición
    {
        // Obtener el id_adulto para la validación unique de nro_caso
        $adultoMayorDb = DB::table('adulto_mayor')->where('ci', $ci_original)->first();

        if (!$adultoMayorDb) {
            return redirect()->back()
                            ->withErrors(['error_actualizacion' => 'Registro de Adulto Mayor no encontrado para el CI proporcionado.'])
                            ->withInput();
        }
        $idAdultoMayor = $adultoMayorDb->id_adulto;


        $validator = Validator::make($request->all(), [
            // Datos de persona
            'nombres' => 'required|string|max:255',
            'primer_apellido' => 'required|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            // Al actualizar, el CI puede cambiar. Si cambia, debe ser único.
            // Si no cambia, la regla unique debe ignorar el registro actual.
            'ci' => 'required|string|max:20|unique:persona,ci,' . $ci_original . ',ci',
            'fecha_nacimiento' => 'required|date|before_or_equal:today',
            'sexo' => 'required|string|in:F,M,O',
            'estado_civil' => 'required|string|in:casado,divorciado,soltero,otro',
            'domicilio' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'zona_comunidad' => 'nullable|string|max:100',
            
            // Datos de adulto mayor
            'discapacidad' => 'nullable|string',
            'vive_con' => 'nullable|string|max:200',
            'migrante' => 'nullable|in:0,1', // Validar como string '0' o '1'
            // nro_caso debe ser único en la tabla adulto_mayor, ignorando el registro actual (identificado por id_adulto)
            'nro_caso' => 'nullable|string|max:50|unique:adulto_mayor,nro_caso,' . $idAdultoMayor . ',id_adulto',
            'fecha' => 'required|date', // Fecha de registro del adulto mayor
        ], [
            'nombres.required' => 'El campo nombres es obligatorio.',
            'primer_apellido.required' => 'El campo primer apellido es obligatorio.',
            'ci.required' => 'El campo CI es obligatorio.',
            'ci.unique' => 'Este CI ya ha sido registrado por otra persona.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento no puede ser futura.',
            'sexo.required' => 'El campo sexo es obligatorio.',
            'estado_civil.required' => 'El estado civil es obligatorio.',
            'domicilio.required' => 'El domicilio es obligatorio.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'nro_caso.unique' => 'Este número de caso ya ha sido registrado para otro adulto mayor.',
            'fecha.required' => 'La fecha de registro del adulto mayor es obligatoria.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput(); // Devuelve los datos antiguos y los errores
        }

        DB::beginTransaction();

        try {
            // Calcular nueva edad
            $edad = Carbon::parse($request->fecha_nacimiento)->age;

            // 1. Actualizar Persona
            DB::table('persona')
                ->where('ci', $ci_original) // Usar el CI original para encontrar el registro
                ->update([
                    'ci' => $request->ci, // Actualizar al nuevo CI si cambió
                    'primer_apellido' => $request->primer_apellido,
                    'segundo_apellido' => $request->segundo_apellido,
                    'nombres' => $request->nombres,
                    'sexo' => $request->sexo,
                    'fecha_nacimiento' => $request->fecha_nacimiento,
                    'edad' => $edad,
                    'estado_civil' => $request->estado_civil,
                    'domicilio' => $request->domicilio,
                    'telefono' => $request->telefono,
                    'zona_comunidad' => $request->zona_comunidad,
                    'updated_at' => now()
                ]);

            // 2. Preparar datos para actualizar adulto_mayor
            $adultoMayorData = [
                // Si el CI de la persona cambió, también debe actualizarse en adulto_mayor
                'ci' => $request->ci,
                'discapacidad' => $request->discapacidad,
                'vive_con' => $request->vive_con,
                'migrante' => $request->migrante == '1' ? true : false, // Convertir a booleano para la BD
                'fecha' => $request->fecha,
                'updated_at' => now()
            ];
            
            // Solo agregar nro_caso si se proporcionó y no está vacío
            // Si se envía vacío, se puede interpretar como que se quiere eliminar/poner a null.
            // Si tu base de datos permite NULL para nro_caso:
            $adultoMayorData['nro_caso'] = $request->filled('nro_caso') ? $request->nro_caso : null;


            // Actualizar adulto_mayor usando el CI que se usó para encontrar la persona
            // (que podría ser el nuevo CI si se actualizó en persona y la relación es por CI)
            DB::table('adulto_mayor')
                ->where('id_adulto', $idAdultoMayor) // Mejor usar la PK de adulto_mayor para la actualización
                ->update($adultoMayorData);

            DB::commit();

            return redirect()->route('admin.gestionar-adultomayor.index')
                            ->with('success', 'Adulto mayor actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error actualizando adulto mayor: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            
            return redirect()->back()
                            ->withErrors(['error_actualizacion' => 'Error al actualizar: ' . $e->getMessage()])
                            ->withInput();
        }
    }

    /**
    * Eliminar adulto mayor
    */
    public function eliminarAdultoMayor($ci)
    {
        DB::beginTransaction();

        try {
            // Verificar si existe la persona y el adulto mayor asociado
            $persona = DB::table('persona')->where('ci', $ci)->first();
            
            if (!$persona) {
                 DB::rollBack(); // No es necesario si no se ha hecho ninguna operación aún, pero por consistencia.
                return redirect()->route('admin.gestionar-adultomayor.index')
                                ->with('error', 'Persona no encontrada.');
            }

            $existeAdultoMayor = DB::table('adulto_mayor')->where('ci', $ci)->exists();
            if (!$existeAdultoMayor) {
                DB::rollBack();
                 // Si la persona existe pero no el adulto mayor, podría ser un estado inconsistente.
                 // O quizás solo se quiere eliminar la persona si no hay adulto mayor.
                 // Por ahora, asumimos que si se intenta eliminar "adulto mayor", ambos deben existir.
                Log::warning('Intento de eliminar adulto mayor no encontrado, pero persona sí existe.', ['ci' => $ci]);
                // Decidir si eliminar solo persona o devolver error. Aquí devuelvo error.
                return redirect()->route('admin.gestionar-adultomayor.index')
                                ->with('error', 'Registro de Adulto Mayor asociado a la persona no encontrado.');
            }


            // Eliminar adulto mayor primero (por la relación de clave foránea si persona.ci es referenciada)
            // O si la FK es de adulto_mayor.ci a persona.ci, el orden es correcto.
            DB::table('adulto_mayor')->where('ci', $ci)->delete();
            
            // Eliminar persona
            DB::table('persona')->where('ci', $ci)->delete();

            DB::commit();

            return redirect()->route('admin.gestionar-adultomayor.index')
                            ->with('success', 'Adulto mayor y datos personales asociados eliminados exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error eliminando adulto mayor: ' . $e->getMessage());
            
            return redirect()->route('admin.gestionar-adultomayor.index')
                            ->with('error', 'Error al eliminar el adulto mayor. Detalles: ' . $e->getMessage());
        }
    }

    // Aquí puedes agregar otros métodos como show, edit, update, etc. si son necesarios.
}

