<h5>Actividad Laboral</h5>
@if($adulto->actividadLaboral)
<ul>
    <li><strong>Nombre Actividad:</strong> {{ $adulto->actividadLaboral->nombre_actividad }}</li>
    <li><strong>Dirección Trabajo:</strong> {{ $adulto->actividadLaboral->direccion_trabajo }}</li>
    <li><strong>Teléfono Trabajo:</strong> {{ $adulto->actividadLaboral->telefono_trabajo }}</li>
    <li><strong>Horas por Día:</strong> {{ $adulto->actividadLaboral->horas_x_dia }}</li>
    <li><strong>Ingreso Mensual Aproximado:</strong> {{ $adulto->actividadLaboral->ingreso_men_aprox }}</li>
</ul>
@else
<p class="text-muted">No se registró actividad laboral.</p>
@endif
