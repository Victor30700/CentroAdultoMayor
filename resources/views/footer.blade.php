{{--
Ruta: resources/views/footer.blade.php
Responsabilidad: Únicamente mostrar el contenido del pie de página.
--}}
<footer class="footer">
    <div class="container">
        <div class="row align-items-center flex-row-reverse">
            <div class="col-md-12 col-sm-12 text-center">
                Copyright © <span id="year"></span> <a href="javascript:void(0)">Sash</a>. Designed with <span
                    class="fa fa-heart text-danger"></span> by <a href="javascript:void(0)"> Spruko </a> All rights
                reserved.
            </div>
        </div>
    </div>
</footer>
<!-- FOOTER END -->

    </div><!-- Cierre de la etiqueta <div class="page"> del header -->

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- =================================================================== -->
    <!-- GRUPO 1: LIBRERÍAS FUNDAMENTALES (Se cargan primero) -->
    <!-- =================================================================== -->
    <!-- JQUERY JS (Siempre primero) -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <!-- BOOTSTRAP JS (Depende de jQuery) -->
    <script src="{{ asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- =================================================================== -->
    <!-- GRUPO 2: PLUGINS ESENCIALES DE LA PLANTILLA (Dependen de jQuery) -->
    <!-- =================================================================== -->
    <script src="{{ asset('assets/js/sticky.js') }}"></script>
    <script src="{{ asset('assets/js/circle-progress.min.js') }}"></script>
    <!-- PERFECT SCROLLBAR JS (Debe cargarse ANTES de sidemenu) -->
    <script src="{{ asset('assets/plugins/p-scroll/perfect-scrollbar.js') }}"></script>
    <!-- SIDEMENU JS (Depende de PerfectScrollbar) -->
    <script src="{{ asset('assets/plugins/sidemenu/sidemenu.js') }}"></script>
    <script src="{{ asset('assets/plugins/sidebar/sidebar.js') }}"></script>

    <!-- =================================================================== -->
    <!-- GRUPO 3: OTROS PLUGINS (Se cargan después de los esenciales) -->
    <!-- =================================================================== -->
    <!-- Peity Charts -->
    <script src="{{ asset('assets/plugins/peitychart/jquery.peity.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/peitychart/peitychart.init.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.js') }}"></script>
    <!-- Sweet Alert -->
    <script src="{{ asset('assets/plugins/sweet-alert/sweetalert2.all.min.js') }}"></script>
    <!-- Typeahead -->
    <script src="{{ asset('assets/plugins/bootstrap5-typehead/autocomplete.js') }}"></script>

    <!-- =================================================================== -->
    <!-- GRUPO 4: SCRIPTS DE INICIALIZACIÓN (Se cargan al final) -->
    <!-- =================================================================== -->
    <!-- Script para inicializar DataTables y Select2 -->
    <script src="{{ asset('assets/js/table-data.js') }}"></script> 
    <!-- Script para inicializar Typeahead -->
    <script src="{{ asset('assets/js/typehead.js') }}"></script>
    <!-- Scripts que buscan elementos específicos del dashboard como gráficos -->
    <script src="{{ asset('assets/js/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/js/index1.js') }}"></script>
    
    <!-- Scripts de Tema y personalización -->
    <script src="{{ asset('assets/js/themeColors.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/switcher/js/switcher.js') }}"></script>

</body>
</html>
