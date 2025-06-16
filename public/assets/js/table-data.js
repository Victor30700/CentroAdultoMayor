$(function(e) {
    "use strict";

    //______Basic Data Table
    if ($('#basic-datatable').length) {
        $('#basic-datatable').DataTable({
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
            }
        });
    }

    //______Responsive Data Table
    if ($('#responsive-datatable').length) {
        $('#responsive-datatable').DataTable({
            language: {
                searchPlaceholder: 'Search...',
                scrollX: "100%",
                sSearch: '',
            }
        });
    }

    //______File-Export Data Table
    if ($('#file-datatable').length) {
        var table = $('#file-datatable').DataTable({
            buttons: ['copy', 'excel', 'pdf', 'colvis'],
            language: {
                searchPlaceholder: 'Search...',
                scrollX: "100%",
                sSearch: '',
            }
        });
        table.buttons().container()
            .appendTo('#file-datatable_wrapper .col-md-6:eq(0)');
    }


    //______Delete Data Table
    if ($('#delete-datatable').length) {
        var table_delete = $('#delete-datatable').DataTable({
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
            }
        });
        $('#delete-datatable tbody').on('click', 'tr', function() {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                table_delete.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });
        $('#button').on('click', function() {
            if (table_delete.row('.selected').any()) {
                table_delete.row('.selected').remove().draw(false);
            }
        });
    }

    //______Modal Data Table
    if ($('#example3').length) {
        $('#example3').DataTable({
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function(row) {
                            var data = row.data();
                            return 'Details for ' + data[0] + ' ' + data[1];
                        }
                    }),
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                        tableClass: 'table'
                    })
                }
            }
        });
    }

    //______Another Responsive Table
    if ($('#example2').length) {
        $('#example2').DataTable({
            responsive: true,
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ items/page',
            }
        });
    }

    //______Select2 
    if ($('.select2').length) {
        $('.select2').select2({
            minimumResultsForSearch: Infinity
        });
    }
});