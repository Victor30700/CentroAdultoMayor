<!-- {{--
Ruta: resources/views/footer.blade.php
Responsabilidad: Únicamente mostrar el contenido del pie de página y scripts necesarios.
--}} -->
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
    <script>
        // Verificar si existe PerfectScrollbar antes de cargar sidemenu
        if (typeof PerfectScrollbar !== 'undefined') {
            // Cargar el script de sidemenu solo si PerfectScrollbar está disponible
            var sideMenuScript = document.createElement('script');
            sideMenuScript.src = "{{ asset('assets/plugins/sidemenu/sidemenu.js') }}";
            sideMenuScript.onload = function() {
                // Cargar sidebar después de sidemenu
                var sidebarScript = document.createElement('script');
                sidebarScript.src = "{{ asset('assets/plugins/sidebar/sidebar.js') }}";
                document.head.appendChild(sidebarScript);
            };
            document.head.appendChild(sideMenuScript);
        }
    </script>

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
    
    <!-- Typeahead con validación -->
    <script>
        // Solo cargar autocomplete si existe el elemento necesario
        if (document.querySelector('.typeahead') || document.querySelector('[data-provide="typeahead"]')) {
            var autocompleteScript = document.createElement('script');
            autocompleteScript.src = "{{ asset('assets/plugins/bootstrap5-typehead/autocomplete.js') }}";
            autocompleteScript.onload = function() {
                // Cargar typehead.js después de autocomplete.js
                var typeheadScript = document.createElement('script');
                typeheadScript.src = "{{ asset('assets/js/typehead.js') }}";
                document.head.appendChild(typeheadScript);
            };
            document.head.appendChild(autocompleteScript);
        }
    </script>

    <!-- =================================================================== -->
    <!-- GRUPO 4: SCRIPTS DE INICIALIZACIÓN (Se cargan al final) -->
    <!-- =================================================================== -->
    <!-- Script para inicializar DataTables y Select2 -->
    <script src="{{ asset('assets/js/table-data.js') }}"></script> 
    
    <!-- Scripts que buscan elementos específicos del dashboard como gráficos -->
    <script>
        // Solo cargar scripts de gráficos si estamos en el dashboard
        if (window.APP_CONTEXT && window.APP_CONTEXT.isDashboard) {
            // Cargar ApexCharts
            var apexScript = document.createElement('script');
            apexScript.src = "{{ asset('assets/js/apexcharts.js') }}";
            document.head.appendChild(apexScript);
            
            // Cargar index1.js con validaciones
            var index1Script = document.createElement('script');
            index1Script.src = "{{ asset('assets/js/index1.js') }}";
            index1Script.onerror = function() {
                console.warn('Error al cargar index1.js - Elementos del dashboard no disponibles');
            };
            document.head.appendChild(index1Script);
        }
    </script>
    
    <!-- Scripts de Tema y personalización con validaciones para evitar duplicados -->
    <script>
        // Evitar cargar múltiples veces los scripts de tema
        if (!window.themeColorsLoaded) {
            window.themeColorsLoaded = true;
            var themeScript = document.createElement('script');
            themeScript.src = "{{ asset('assets/js/themeColors.js') }}";
            document.head.appendChild(themeScript);
        }
        
        if (!window.customScriptLoaded) {
            window.customScriptLoaded = true;
            var customScript = document.createElement('script');
            customScript.src = "{{ asset('assets/js/custom.js') }}";
            document.head.appendChild(customScript);
        }
    </script>
    
    <script src="{{ asset('assets/switcher/js/switcher.js') }}"></script>

    <!-- Script para inicializar el año en el footer -->
    <script>
        $(document).ready(function() {
            $('#year').text(new Date().getFullYear());
        });
    </script>

</body>
</html>