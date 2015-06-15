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
            { "data": "control_1", "bSortable" : false },
            { "data": "control_2", "bSortable" : false },
            { "data": "control_3", "bSortable" : false },
            { "data": "control_4", "bSortable" : false },
            { "data": "control_5", "bSortable" : false },
            { "data": "actions", "bSortable" : false, 'width': '10px' },
        ],
        "fnInitComplete": function(oSettings, json) {
          for(var i = 1; i <=  5; i++){
            if(json["control_"+i])
              $("#title_control_"+i).html(json["control_"+i]);
          }
        },
        "language": {
                "url": "//cdn.datatables.net/plug-ins/f2c75b7247b/i18n/Spanish.json"
        },
      });
  }
});