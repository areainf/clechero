$( document ).ready(function() {
  /*
   *  if view datatable user action index 
   */
   var dataSet = [["1","mmarozzi","email","No","ACC"],["11","nnusbaum","","Si","ACC"]];
  if ($("#id-users-list").length > 0){
    // si esta en el listado de reservas
    $('#table-users').dataTable( {
        "ajax": $('#table-users').data('source'),
        "processing": true,
        "serverSide": true,
        "columns": [
            { "data": "id", "visible": false},
            { "data": "username" },
            { "data": "email" },
            { "data": "person" },
            { "data": "role" },
            { "data": "disable" },
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