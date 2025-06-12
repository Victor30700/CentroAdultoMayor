<h5>Encargado ({{ ucfirst($encargado->tipo_encargado ?? 'N/A') }})</h5>

@if($encargado)
    @if($encargado->tipo_encargado === 'natural' && $encargado->personaNatural)
        <ul>
            <li><strong>Nombre:</strong> {{ $encargado->personaNatural->nombres }} {{ $encargado->personaNatural->primer_apellido }}</li>
            <li><strong>Edad:</strong> {{ $encargado->personaNatural->edad }}</li>
            <li><strong>CI:</strong> {{ $encargado->personaNatural->ci }}</li>
            <li><strong>Teléfono:</strong> {{ $encargado->personaNatural->telefono }}</li>
            <li><strong>Domicilio:</strong> {{ $encargado->personaNatural->direccion_domicilio }}</li>
            <li><strong>Parentesco:</strong> {{ $encargado->personaNatural->relacion_parentesco }}</li>
            <li><strong>Trabajo:</strong> {{ $encargado->personaNatural->direccion_de_trabajo }}</li>
            <li><strong>Ocupación:</strong> {{ $encargado->personaNatural->ocupacion }}</li>
        </ul>
    @elseif($encargado->tipo_encargado === 'juridica' && $encargado->personaJuridica)
        <ul>
            <li><strong>Institución:</strong> {{ $encargado->personaJuridica->nombre_institucion }}</li>
            <li><strong>Dirección:</strong> {{ $encargado->personaJuridica->direccion }}</li>
            <li><strong>Teléfono:</strong> {{ $encargado->personaJuridica->telefono }}</li>
            <li><strong>Funcionario:</strong> {{ $encargado->personaJuridica->nombre_funcionario }}</li>
        </ul>
    @else
        <p class="text-muted">No hay datos del encargado disponibles.</p>
    @endif
@else
    <p class="text-muted">No se registró un encargado.</p>
@endif
