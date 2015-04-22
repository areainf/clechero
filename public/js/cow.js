$( document ).ready(function() {
  /*
   *  if view datatable user action index 
   */
  if ($("#id-cow-list").length > 0){
    // si esta en el listado de reservas
    $('#table-cow').dataTable({
        "ajax": $('#table-cow').data('source'),
        "processing": true,
        "serverSide": true,
        "columns": [
            { "data": "id", "visible": false},
            { "data": "caravana" },
            { "data": "actions", "bSortable" : false, 'width': '10px' },
        ],
        "fnInitComplete": function() {bind_action_link();},
        "language": {
                "url": "//cdn.datatables.net/plug-ins/f2c75b7247b/i18n/Spanish.json"
        },
      });

    function bind_action_link(){
    }
  }
});