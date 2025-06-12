<h5>Datos del Adulto Mayor</h5>
<ul>
    <li><strong>Nombre:</strong> {{ $adulto->persona->nombres }} {{ $adulto->persona->primer_apellido }}</li>
    <li><strong>CI:</strong> {{ $adulto->persona->ci }}</li>
    <li><strong>Sexo:</strong> {{ $adulto->persona->sexo }}</li>
    <li><strong>Edad:</strong> {{ $adulto->persona->edad }}</li>
    <li><strong>Domicilio:</strong> {{ $adulto->persona->domicilio }}</li>
</ul>
