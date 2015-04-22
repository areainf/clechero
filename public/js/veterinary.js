$( document ).ready(function() {
  /*
   *  if view datatable user action index 
   */
  if ($("#id-veterinarian-list").length > 0){
    // si esta en el listado de reservas
    $('#table-veterinarian').dataTable( {
        "ajax": $('#table-veterinarian').data('source'),
        "processing": true,
        "serverSide": true,
        "columns": [
            { "data": "id", "visible": false},
            { "data": "first_name" },
            { "data": "last_name" },
            { "data": "email" },
            { "data": "phone" },
            { "data": "actions", "bSortable" : false, 'width': '10px' },
        ],
        "fnInitComplete": function() {bind_action_link();},
        "language": {
                "url": "//cdn.datatables.net/plug-ins/f2c75b7247b/i18n/Spanish.json"
        },

    });

  }
  function bind_action_link(){
    // $(".id_reservas_action_change").on("click", function(){
    //   console.log($(this).attr('value'));
    //   alert("Entro");
    //   return false;
    // });
  }
});