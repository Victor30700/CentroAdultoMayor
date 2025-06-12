
@include('header')


@php
    $modoEdicion = isset($encargado);
@endphp

<div class="container">
    <h4>
        {{ $modoEdicion ? 'Editar Caso de:' : 'Registrar Caso para:' }}
        {{ $adulto->persona->nombres }} {{ $adulto->persona->primer_apellido }}
    </h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Se encontraron errores:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $modoEdicion ? route('admin.caso.update', $adulto->id_adulto) : route('admin.caso.completo.store', $adulto->id_adulto) }}">
        @csrf

        <!-- Tabs -->
        <ul class="nav nav-tabs mt-3" id="formTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="actividad-tab" data-bs-toggle="tab" href="#actividad" role="tab">1. Actividad Laboral</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="encargado-tab" data-bs-toggle="tab" href="#encargado" role="tab">2. Encargado</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="denunciado-tab" data-bs-toggle="tab" href="#denunciado" role="tab">3. Denunciado</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="grupo-tab" data-bs-toggle="tab" href="#grupo" role="tab">4. Grupo Familiar</a>
            </li>

        </ul>


        <!-- Contenido de tabs -->
        <div class="tab-content mt-3">

            {{-- TAB 1: Actividad Laboral --}}
            <div class="tab-pane fade show active" id="actividad" role="tabpanel">
                <div class="mb-3">
                    <label>Nombre de la Actividad</label>
                    <input type="text" name="nombre_actividad" class="form-control"
                        value="{{ old('nombre_actividad', $adulto->actividadLaboral->nombre_actividad ?? '') }}">
                </div>

                <div class="mb-3">
                    <label>Dirección de Trabajo</label>
                    <input type="text" name="direccion_trabajo" class="form-control"
                        value="{{ old('direccion_trabajo', $adulto->actividadLaboral->direccion_trabajo ?? '') }}">
                </div>

                <div class="mb-3">
                    <label>Teléfono del Trabajo</label>
                    <input type="text" name="telefono_trabajo" class="form-control"
                        value="{{ old('telefono_trabajo', $adulto->actividadLaboral->telefono_trabajo ?? '') }}">
                </div>

                <div class="mb-3">
                    <label>Horas por Día</label>
                    <input type="text" name="horas_x_dia" class="form-control"
                        value="{{ old('horas_x_dia', $adulto->actividadLaboral->horas_x_dia ?? '') }}">
                </div>

                <div class="mb-3">
                    <label>Ingreso Mensual Aproximado</label>
                    <input type="text" name="ingreso_men_aprox" class="form-control"
                        value="{{ old('ingreso_men_aprox', $adulto->actividadLaboral->ingreso_men_aprox ?? '') }}">
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-secondary" onclick="skipTab()">Omitir esta pestaña</button>
                    <button type="button" class="btn btn-primary" onclick="nextTab()">Siguiente</button>
                </div>
            </div>

            {{-- TAB 2: Encargado --}}
            <div class="tab-pane fade" id="encargado" role="tabpanel">
                <div class="mb-3">
                    <label>Tipo de Encargado</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipo_encargado" id="naturalRadio" value="natural"
                            onclick="toggleTipoEncargado('natural')"
                            {{ old('tipo_encargado', $encargado->tipo_encargado ?? '') === 'natural' ? 'checked' : '' }}>
                        <label class="form-check-label" for="naturalRadio">Persona Natural</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipo_encargado" id="juridicaRadio" value="juridica"
                            onclick="toggleTipoEncargado('juridica')"
                            {{ old('tipo_encargado', $encargado->tipo_encargado ?? '') === 'juridica' ? 'checked' : '' }}>
                        <label class="form-check-label" for="juridicaRadio">Persona Jurídica</label>
                    </div>
                </div>

                {{-- Persona Natural --}}
                <div id="naturalFields" style="display: none;">
                    <h5>Datos de Persona Natural</h5>

                    <div class="mb-3">
                        <label>Nombres</label>
                        <input type="text" name="nombres_natural" class="form-control"
                            value="{{ old('nombres_natural', $encargado->personaNatural->nombres ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label>Primer Apellido</label>
                        <input type="text" name="primer_apellido_natural" class="form-control"
                            value="{{ old('primer_apellido_natural', $encargado->personaNatural->primer_apellido ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label>Segundo Apellido</label>
                        <input type="text" name="segundo_apellido_natural" class="form-control"
                            value="{{ old('segundo_apellido_natural', $encargado->personaNatural->segundo_apellido ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label>Edad</label>
                        <input type="number" name="edad_natural" class="form-control"
                            value="{{ old('edad_natural', $encargado->personaNatural->edad ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label>CI</label>
                        <input type="text" name="ci_natural" class="form-control"
                            value="{{ old('ci_natural', $encargado->personaNatural->ci ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label>Teléfono</label>
                        <input type="text" name="telefono_natural" class="form-control"
                            value="{{ old('telefono_natural', $encargado->personaNatural->telefono ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label>Dirección Domicilio</label>
                        <input type="text" name="direccion_domicilio" class="form-control"
                            value="{{ old('direccion_domicilio', $encargado->personaNatural->direccion_domicilio ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label>Relación Parentesco</label>
                        <input type="text" name="relacion_parentesco" class="form-control"
                            value="{{ old('relacion_parentesco', $encargado->personaNatural->relacion_parentesco ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label>Dirección de Trabajo</label>
                        <input type="text" name="direccion_trabajo_nat" class="form-control"
                            value="{{ old('direccion_trabajo_nat', $encargado->personaNatural->direccion_de_trabajo ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label>Ocupación</label>
                        <input type="text" name="ocupacion" class="form-control"
                            value="{{ old('ocupacion', $encargado->personaNatural->ocupacion ?? '') }}">
                    </div>
                </div>

                {{-- Persona Jurídica --}}
                <div id="juridicaFields" style="display: none;">
                    <h5>Datos de Persona Jurídica</h5>

                    <div class="mb-3">
                        <label>Nombre de Institución</label>
                        <input type="text" name="nombre_institucion" class="form-control"
                            value="{{ old('nombre_institucion', $encargado->personaJuridica->nombre_institucion ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label>Dirección</label>
                        <input type="text" name="direccion_juridica" class="form-control"
                            value="{{ old('direccion_juridica', $encargado->personaJuridica->direccion ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label>Teléfono</label>
                        <input type="text" name="telefono_juridica" class="form-control"
                            value="{{ old('telefono_juridica', $encargado->personaJuridica->telefono ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label>Funcionario Responsable</label>
                        <input type="text" name="nombre_funcionario" class="form-control"
                            value="{{ old('nombre_funcionario', $encargado->personaJuridica->nombre_funcionario ?? '') }}">
                    </div>
                </div>
            </div>
            {{-- TAB 3: Denunciado --}}
            <div class="tab-pane fade" id="denunciado" role="tabpanel">
                <h5>Datos del Denunciado (Persona que agrede)</h5>

                <div class="mb-3">
                    <label>Nombres</label>
                    <input type="text" name="nombres_den" class="form-control" value="{{ old('nombres_den') }}">
                </div>

                <div class="mb-3">
                    <label>Primer Apellido</label>
                    <input type="text" name="primer_apellido_den" class="form-control" value="{{ old('primer_apellido_den') }}">
                </div>

                <div class="mb-3">
                    <label>Segundo Apellido</label>
                    <input type="text" name="segundo_apellido_den" class="form-control" value="{{ old('segundo_apellido_den') }}">
                </div>

                <div class="mb-3">
                    <label>Edad</label>
                    <input type="number" name="edad_den" class="form-control" value="{{ old('edad_den') }}">
                </div>

                <div class="mb-3">
                    <label>CI</label>
                    <input type="text" name="ci_den" class="form-control" value="{{ old('ci_den') }}">
                </div>

                <div class="mb-3">
                    <label>Teléfono</label>
                    <input type="text" name="telefono_den" class="form-control" value="{{ old('telefono_den') }}">
                </div>

                <div class="mb-3">
                    <label>Dirección Domicilio</label>
                    <input type="text" name="direccion_domicilio_den" class="form-control" value="{{ old('direccion_domicilio_den') }}">
                </div>

                <div class="mb-3">
                    <label>Dirección Trabajo</label>
                    <input type="text" name="direccion_trabajo_den" class="form-control" value="{{ old('direccion_trabajo_den') }}">
                </div>

                <div class="mb-3">
                    <label>Ocupación</label>
                    <input type="text" name="ocupacion_den" class="form-control" value="{{ old('ocupacion_den') }}">
                </div>

                <div class="mb-3">
                    <label>Sexo</label>
                    <select name="sexo_den" class="form-select">
                        <option value="">Seleccione</option>
                        <option value="M" {{ old('sexo_den') === 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ old('sexo_den') === 'F' ? 'selected' : '' }}>Femenino</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Descripción de los hechos</label>
                    <textarea name="descripcion_hechos" class="form-control" rows="3">{{ old('descripcion_hechos') }}</textarea>
                </div>
            </div>
            {{-- TAB 4: Grupo Familiar --}}
            <div class="tab-pane fade" id="grupo" role="tabpanel">
                <h5>Datos del Familiar</h5>
            <div class="mb-3">
                <label>Apellido Paterno</label>
                <input type="text" name="apellido_paterno" class="form-control" value="{{ old('apellido_paterno') }}">
            </div>

            <div class="mb-3">
                <label>Apellido Materno</label>
                <input type="text" name="apellido_materno" class="form-control" value="{{ old('apellido_materno') }}">
            </div>

            <div class="mb-3">
                <label>Nombres</label>
                <input type="text" name="nombres_fam" class="form-control" value="{{ old('nombres_fam') }}">
            </div>

            <div class="mb-3">
                <label>Parentesco</label>
                <input type="text" name="parentesco" class="form-control" value="{{ old('parentesco') }}">
            </div>

            <div class="mb-3">
                <label>Edad</label>
                <input type="number" name="edad_fam" class="form-control" value="{{ old('edad_fam') }}">
            </div>

            <div class="mb-3">
                <label>Ocupación</label>
                <input type="text" name="ocupacion_fam" class="form-control" value="{{ old('ocupacion_fam') }}">
            </div>

            <div class="mb-3">
                <label>Dirección</label>
                <input type="text" name="direccion_fam" class="form-control" value="{{ old('direccion_fam') }}">
            </div>

            <div class="mb-3">
                <label>Teléfono</label>
                <input type="text" name="telefono_fam" class="form-control" value="{{ old('telefono_fam') }}">
            </div>

            <div class="mt-4">
                    <button type="submit" class="btn {{ $modoEdicion ? 'btn-warning' : 'btn-success' }}">
                        {{ $modoEdicion ? 'Actualizar Caso' : 'Guardar Registro' }}
                    </button>
            </div>
        </div>


        </div>
    </form>
</div>

{{-- Scripts --}}
<script>
    function nextTab() {
        const nextTabLink = document.querySelector('a[href="#encargado"]');
        new bootstrap.Tab(nextTabLink).show();
    }

    function skipTab() {
        nextTab();
    }

    function toggleTipoEncargado(tipo) {
        document.getElementById('naturalFields').style.display = (tipo === 'natural') ? 'block' : 'none';
        document.getElementById('juridicaFields').style.display = (tipo === 'juridica') ? 'block' : 'none';
    }

    // Al cargar la vista, restaurar el formulario correcto
    document.addEventListener('DOMContentLoaded', () => {
        const tipo = document.querySelector('input[name="tipo_encargado"]:checked');
        if (tipo) {
            toggleTipoEncargado(tipo.value);
        }
    });
</script>


@include('footer')