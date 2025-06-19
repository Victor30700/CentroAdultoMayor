{{-- resources/views/admin/registerLegal.blade.php --}}
@extends('layouts.main')

@section('content')
<div class="page">
    <div class="page-main">
        <div class="main-content app-content mt-0">
            <div class="side-app">
                <div class="main-container container-fluid">
                    <div class="page-header">
                        <h1 class="page-title">Registrar Usuario Legal</h1>
                        <div>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Registrar Usuario Legal</li>
                            </ol>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                            <div class="card overflow-hidden">
                                <div class="card-header bg-primary text-white">
                                    <h3 class="card-title text-white">Formulario de Registro de Usuario Legal</h3>
                                </div>
                                <div class="card-body">
                                    {{-- Manejo de errores de validación del servidor --}}
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form action="{{ route('admin.store-legal') }}" method="POST" id="registerLegalForm" novalidate>
                                        @csrf

                                        {{-- Navegación de Pestañas --}}
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="datos-personales-tab" data-bs-toggle="tab" data-bs-target="#datosPersonales" type="button" role="tab" aria-controls="datosPersonales" aria-selected="true">
                                                    1. Datos Personales
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="datos-usuario-tab" data-bs-toggle="tab" data-bs-target="#datosUsuario" type="button" role="tab" aria-controls="datosUsuario" aria-selected="false">
                                                    2. Datos de Usuario
                                                </button>
                                            </li>
                                        </ul>

                                        {{-- Contenido de las Pestañas --}}
                                        <div class="tab-content mt-3" id="myTabContent">
                                            {{-- Pestaña 1: Datos Personales --}}
                                            <div class="tab-pane fade show active" id="datosPersonales" role="tabpanel" aria-labelledby="datos-personales-tab">
                                                <h5 class="mb-3">Información Personal</h5>
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="nombres" name="nombres" value="{{ old('nombres') }}" required>
                                                        <div class="invalid-feedback">Por favor, ingrese los nombres.</div>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="primer_apellido" class="form-label">Primer Apellido <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" value="{{ old('primer_apellido') }}" required>
                                                        <div class="invalid-feedback">Por favor, ingrese el primer apellido.</div>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                                                        <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido" value="{{ old('segundo_apellido') }}">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="ci" class="form-label">CI (Cédula de Identidad) <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="ci" name="ci" value="{{ old('ci') }}" required pattern="\d+">
                                                        <div class="invalid-feedback" id="ci_error_message">Por favor, ingrese el CI (solo números).</div>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required>
                                                        <div class="invalid-feedback">Por favor, ingrese la fecha de nacimiento.</div>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="sexo" class="form-label">Sexo <span class="text-danger">*</span></label>
                                                        <select class="form-select" id="sexo" name="sexo" required>
                                                            <option value="" disabled {{ old('sexo') ? '' : 'selected' }}>Seleccione...</option>
                                                            <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                                                            <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                                            <option value="O" {{ old('sexo') == 'O' ? 'selected' : '' }}>Otro</option>
                                                        </select>
                                                        <div class="invalid-feedback">Por favor, seleccione el sexo.</div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="estado_civil" class="form-label">Estado Civil <span class="text-danger">*</span></label>
                                                        <select class="form-select" id="estado_civil" name="estado_civil" required>
                                                            <option value="" disabled {{ old('estado_civil') ? '' : 'selected' }}>Seleccione...</option>
                                                            <option value="casado" {{ old('estado_civil') == 'casado' ? 'selected' : '' }}>Casado(a)</option>                                  <option value="divorciado" {{ old('estado_civil') == 'divorciado' ? 'selected' : '' }}>Divorciado(a)</option>
                                                            <option value="soltero" {{ old('estado_civil') == 'soltero' ? 'selected' : '' }}>Soltero(a)</option>
                                                            <option value="otro" {{ old('estado_civil') == 'otro' ? 'selected' : '' }}>Otro</option>
                                                        </select>
                                                        <div class="invalid-feedback">Por favor, seleccione el estado civil.</div>
                                                    </div>
                                                    <div class="col-md-8 mb-3">
                                                        <label for="domicilio" class="form-label">Domicilio <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="domicilio" name="domicilio" value="{{ old('domicilio') }}" required>
                                                        <div class="invalid-feedback">Por favor, ingrese el domicilio.</div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="telefono" class="form-label">Teléfono/Celular <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}" required pattern="\d+">
                                                        <div class="invalid-feedback">Por favor, ingrese el teléfono (solo números).</div>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="zona_comunidad" class="form-label">Zona/Comunidad</label>
                                                        <input type="text" class="form-control" id="zona_comunidad" name="zona_comunidad" value="{{ old('zona_comunidad') }}">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="area_especialidad_legal" class="form-label">Área de Especialidad <span class="text-danger">*</span></label>
                                                        <select class="form-select" id="area_especialidad_legal" name="area_especialidad_legal" required>
                                                            <option value="" disabled {{ old('area_especialidad_legal') ? '' : 'selected' }}>Seleccione...</option>
                                                            <option value="Asistente Social" {{ old('area_especialidad_legal') == 'Asistente Social' ? 'selected' : '' }}>Asistente Social</option>
                                                            <option value="Psicologia" {{ old('area_especialidad_legal') == 'Psicologia' ? 'selected' : '' }}>Psicología</option>
                                                            <option value="Derecho" {{ old('area_especialidad_legal') == 'Derecho' ? 'selected' : '' }}>Derecho</option>
                                                        </select>
                                                        <div class="invalid-feedback">Por favor, seleccione el área de especialidad.</div>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-end mt-3">
                                                    <button type="button" class="btn btn-primary" id="nextButton">
                                                        Siguiente <i class="fe fe-arrow-right"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- Pestaña 2: Datos de Usuario --}}
                                            <div class="tab-pane fade" id="datosUsuario" role="tabpanel" aria-labelledby="datos-usuario-tab">
                                                <h5 class="mb-3">Credenciales de Usuario</h5>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="ci_usuario" class="form-label">Usuario (CI)</label>
                                                        <input type="text" class="form-control" id="ci_usuario" name="ci_usuario" readonly>
                                                        <small class="form-text text-muted">El CI se copiará automáticamente de los datos personales.</small>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="id_rol" class="form-label">Rol <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" value="Usuario Legal" readonly>
                                                        <input type="hidden" name="id_rol" value="3"> {{-- Asumiendo que 3 es el ID para Legal --}}
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                                                        <input type="password" class="form-control" id="password" name="password" required minlength="8">
                                                        <div class="invalid-feedback" id="password_error_message">La contraseña es requerida y debe tener al menos 8 caracteres.</div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="password_confirmation" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                                        <div class="invalid-feedback" id="password_confirmation_error_message">Por favor, confirme la contraseña. Las contraseñas no coinciden.</div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="terms_acceptance" name="terms_acceptance" required>
                                                            <label class="form-check-label" for="terms_acceptance">
                                                                Acepto los términos y condiciones del sistema <span class="text-danger">*</span>
                                                            </label>
                                                            <div class="invalid-feedback">Debe aceptar los términos y condiciones.</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between mt-4">
                                                    <button type="button" class="btn btn-outline-primary" id="prevButton">
                                                        <i class="fe fe-arrow-left"></i> Anterior
                                                    </button>
                                                    <div>
                                                        <button type="submit" class="btn btn-success" id="submitButton">
                                                            <i class="fe fe-check-circle"></i> Finalizar Registro
                                                        </button>
                                                        <a href="{{ route('admin.dashboard') }}" class="btn btn-danger ms-2">
                                                            <i class="fe fe-x"></i> Cancelar
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</div>

@endsection
{{-- Estilos generales para validación y pestañas --}}
<style>
    .form-control.is-invalid,
    .form-select.is-invalid,
    .form-check-input.is-invalid {
        border-color: #dc3545 !important; /* Rojo para inválido */
    }
    .form-control.is-valid,
    .form-select.is-valid {
        border-color: #198754 !important; /* Verde para válido */
    }
    .invalid-feedback {
        display: block !important; /* Asegurar que el mensaje de feedback se muestre */
        width: 100%;
        margin-top: .25rem;
        font-size: .875em;
        color: #dc3545;
    }
    .form-check-input.is-invalid ~ .form-check-label {
        color: #dc3545 !important;
    }
    .nav-tabs .nav-link {
        border: 1px solid #ddd;
        border-bottom-color: transparent;
        border-radius: .25rem .25rem 0 0;
        margin-right: 2px;
        color: #495057;
    }
    .nav-tabs .nav-link.active {
        color: #007bff;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
    }
    .tab-content {
        border: 1px solid #dee2e6;
        border-top: none;
        padding: 15px;
        border-radius: 0 0 .25rem .25rem;
    }
    /* Alerta de validación en Pestaña 1 (opcional si se desea resaltar) */
    .validation-alert {
        border-color: #ffc107;
        background-color: #fff3cd;
        color: #856404;
    }
    /* Estilo para el campo de CI en la pestaña 2 */
    #ci_usuario[readonly] {
        background-color: #f8f9fa;
        color: #212529;
        font-weight: bold;
        font-size: 1.1rem;
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Selección de Elementos DOM ---
    const form = document.getElementById('registerLegalForm');
    const nextButton = document.getElementById('nextButton');
    const prevButton = document.getElementById('prevButton');
    const datosPersonalesTabEl = document.getElementById('datos-personales-tab');
    const datosUsuarioTabEl = document.getElementById('datos-usuario-tab');
    const ciInput = document.getElementById('ci');
    const ciUsuarioInput = document.getElementById('ci_usuario');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');

    // Instanciación de pestañas (Bootstrap.Tab)
    let bsTabDatosPersonales = null;
    let bsTabDatosUsuario = null;
    if (datosPersonalesTabEl && typeof bootstrap !== 'undefined' && bootstrap.Tab) {
        bsTabDatosPersonales = new bootstrap.Tab(datosPersonalesTabEl);
    }
    if (datosUsuarioTabEl && typeof bootstrap !== 'undefined' && bootstrap.Tab) {
        bsTabDatosUsuario = new bootstrap.Tab(datosUsuarioTabEl);
    }

    // --- Copiar CI al campo Usuario (CI) ---
    function updateCiUsuario() {
        if (ciInput && ciUsuarioInput) {
            ciUsuarioInput.value = ciInput.value;
        }
    }
    if (ciInput) {
        ciInput.addEventListener('input', updateCiUsuario);
        updateCiUsuario(); // Copiar al cargar si old('ci') existe
    }

    // --- Función de validación individual ---
    function validateField(input) {
        input.classList.remove('is-invalid', 'is-valid');
        const feedback = input.parentElement.querySelector('.invalid-feedback');
        let isValid = true;
        let message = '';

        if (input.required) {
            if (input.type === 'checkbox') {
                if (!input.checked) {
                    isValid = false;
                    message = feedback ? feedback.textContent : 'Este campo es obligatorio.';
                }
            } else if (!input.value.trim()) {
                isValid = false;
                message = feedback ? feedback.textContent : 'Este campo es obligatorio.';
            }
        }
        if (isValid && input.pattern) {
            const regex = new RegExp(input.pattern);
            if (!regex.test(input.value)) {
                isValid = false;
                message = input.id === 'ci'
                    ? 'El CI debe contener solo números.'
                    : (input.id === 'telefono'
                        ? 'El teléfono debe contener solo números.'
                        : 'Formato incorrecto.');
                if (feedback) feedback.textContent = message;
            } else {
                if (feedback && input.id === 'ci') feedback.textContent = 'Por favor, ingrese el CI (solo números).';
                if (feedback && input.id === 'telefono') feedback.textContent = 'Por favor, ingrese el teléfono (solo números).';
            }
        }
        if (isValid && input.id === 'password' && input.value.length > 0 && input.value.length < input.minLength) {
            isValid = false;
            message = `Debe tener al menos ${input.minLength} caracteres.`;
            if (feedback) feedback.textContent = message;
        }
        if (isValid && input.id === 'password_confirmation' && passwordInput && passwordInput.value !== input.value) {
            isValid = false;
            message = 'Las contraseñas no coinciden.';
            if (feedback) feedback.textContent = message;
        }

        if (isValid) {
            input.classList.add('is-valid');
        } else {
            input.classList.add('is-invalid');
        }
        return isValid;
    }

    // Añadir listeners de validación en tiempo real
    form.querySelectorAll('input[required], select[required]').forEach(input => {
        input.addEventListener('input', () => validateField(input));
        input.addEventListener('change', () => validateField(input));
    });
    if (passwordInput) {
        passwordInput.addEventListener('input', () => {
            validateField(passwordInput);
            if (confirmPasswordInput && confirmPasswordInput.value) validateField(confirmPasswordInput);
        });
    }
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', () => validateField(confirmPasswordInput));
    }

    // Validar todos los campos en una pestaña
    function validateTab(tabPaneId) {
        const tabPane = document.getElementById(tabPaneId);
        if (!tabPane) return { isValid: true, firstInvalidElement: null };
        let allFieldsValid = true;
        let firstInvalid = null;
        const fields = tabPane.querySelectorAll('input[required], select[required]');
        fields.forEach(field => {
            if (!validateField(field)) {
                allFieldsValid = false;
                if (!firstInvalid) {
                    firstInvalid = field;
                }
            }
        });
        return { isValid: allFieldsValid, firstInvalidElement: firstInvalid };
    }

    // --- Botón “Siguiente” ---
    if (nextButton) {
        nextButton.addEventListener('click', function() {
            const validationResult = validateTab('datosPersonales');
            if (validationResult.isValid) {
                if (bsTabDatosUsuario) {
                    bsTabDatosUsuario.show();
                } else {
                    document.getElementById('datosPersonales').classList.remove('show', 'active');
                    document.getElementById('datosUsuario').classList.add('show', 'active');
                    datosPersonalesTabEl.classList.remove('active');
                    datosUsuarioTabEl.classList.add('active');
                    updateCiUsuario();
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos Incompletos o Inválidos',
                        html: 'Por favor, revise los campos marcados en la pestaña "Datos Personales".',
                        confirmButtonText: 'Entendido'
                    }).then(() => {
                        if (validationResult.firstInvalidElement) {
                            validationResult.firstInvalidElement.focus();
                            validationResult.firstInvalidElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    });
                }
            }
        });
    }

    // --- Botón “Anterior” ---
    if (prevButton) {
        prevButton.addEventListener('click', function() {
            if (bsTabDatosPersonales) {
                bsTabDatosPersonales.show();
            } else {
                document.getElementById('datosUsuario').classList.remove('show', 'active');
                document.getElementById('datosPersonales').classList.add('show', 'active');
                datosUsuarioTabEl.classList.remove('active');
                datosPersonalesTabEl.classList.add('active');
            }
        });
    }

    // Al mostrarse la pestaña “Datos de Usuario”, actualizar CI
    if (datosUsuarioTabEl) {
        datosUsuarioTabEl.addEventListener('shown.bs.tab', function() {
            updateCiUsuario();
        });
    }

    // --- Envío del Formulario ---
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        let allValid = true;
        let firstInvalidElementOverall = null;
        let tabIdOfFirstError = null;
        let errorMessages = [];

        // Validar Pestaña 1
        const personalValidation = validateTab('datosPersonales');
        if (!personalValidation.isValid) {
            allValid = false;
            if (!firstInvalidElementOverall) {
                firstInvalidElementOverall = personalValidation.firstInvalidElement;
                tabIdOfFirstError = 'datos-personales-tab';
            }
            document.getElementById('datosPersonales').querySelectorAll('.is-invalid').forEach(el => {
                const labelEl = form.querySelector(`label[for="${el.id}"]`);
                const label = labelEl ? labelEl.textContent.replace('*','').trim() : (el.name || el.id);
                const feedbackMsg = el.parentElement.querySelector('.invalid-feedback')?.textContent || 'Error desconocido.';
                errorMessages.push(`<b>${label}:</b> ${feedbackMsg}`);
            });
        }

        // Validar Pestaña 2
        const userValidation = validateTab('datosUsuario');
        if (!userValidation.isValid) {
            allValid = false;
            if (!firstInvalidElementOverall) {
                firstInvalidElementOverall = userValidation.firstInvalidElement;
                tabIdOfFirstError = 'datos-usuario-tab';
            }
            document.getElementById('datosUsuario').querySelectorAll('.is-invalid').forEach(el => {
                const labelEl = form.querySelector(`label[for="${el.id}"]`);
                const label = labelEl ? labelEl.textContent.replace('*','').trim() : (el.name || el.id);
                const feedbackMsg = el.parentElement.querySelector('.invalid-feedback')?.textContent || 'Error desconocido.';
                if (!errorMessages.some(msg => msg.startsWith(`<b>${label}:`))) {
                    errorMessages.push(`<b>${label}:</b> ${feedbackMsg}`);
                }
            });
        }

        // Eliminar mensajes duplicados
        errorMessages = [...new Set(errorMessages)];

        if (!allValid) {
            if (typeof Swal !== 'undefined') {
                let htmlErrorMessages = 'Por favor, corrija los siguientes errores:<br><ul style="text-align: left; margin-left: 20px; padding-left:20px; list-style-type: disc;">';
                errorMessages.forEach(msg => {
                    htmlErrorMessages += `<li>${msg}</li>`;
                });
                htmlErrorMessages += '</ul>';

                Swal.fire({
                    icon: 'error',
                    title: 'Formulario Incompleto o Inválido',
                    html: htmlErrorMessages,
                    confirmButtonText: 'Entendido',
                    customClass: {
                        htmlContainer: 'text-start'
                    }
                }).then(() => {
                    if (tabIdOfFirstError && firstInvalidElementOverall) {
                        const tabButton = document.getElementById(tabIdOfFirstError);
                        if (tabButton && typeof bootstrap !== 'undefined' && bootstrap.Tab) {
                            const bsTabInstance = bootstrap.Tab.getInstance(tabButton) || new bootstrap.Tab(tabButton);
                            bsTabInstance.show();
                            setTimeout(() => {
                                firstInvalidElementOverall.focus();
                                firstInvalidElementOverall.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }, 250);
                        } else {
                            firstInvalidElementOverall.focus();
                            firstInvalidElementOverall.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                });
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Enviando su registro.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                // Simulación de envío
                setTimeout(() => {
                    Swal.fire('¡Registrado!', 'Su información ha sido registrada con éxito.', 'success')
                        .then(() => {
                            form.submit();
                        });
                }, 1500);
            } else {
                form.submit();
            }
        }
    });
});
</script>
@endpush