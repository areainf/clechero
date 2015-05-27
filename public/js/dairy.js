$( document ).ready(function() {
  /*
   *  if view datatable user action index 
   */
  if ($("#id-dairies_admin-list").length > 0){
    // si esta en el listado de reservas
    $('#table-dairies_admin').dataTable( {
        "ajax": $('#table-dairies_admin').data('source'),
        "processing": true,
        "serverSide": true,
        "columns": [
            { "data": "id"},
            { "data": "name" },
            { "data": "location" },
            { "data": "industry" },
            { "data": "owner" },
            { "data": "veterinary" },
            { "data": "email" },
            { "data": "phone" },
            { "data": "actions", "bSortable" : false},
        ],
        "fnInitComplete": function() {bind_action_link();},
        "language": {
                "url": "//cdn.datatables.net/plug-ins/f2c75b7247b/i18n/Spanish.json"
        },
    });

  }
  if ($("#id-dairies_own-list").length > 0){
    // si esta en el listado de reservas
    $('#table-dairies_own').dataTable( {
        "ajax": $('#table-dairies_own').data('source'),
        "processing": true,
        "serverSide": true,
        "columns": [
            { "data": "id"},
            { "data": "name" },
            { "data": "location" },
            { "data": "industry" },
            { "data": "owner" },
            { "data": "veterinary" },
            { "data": "cattle", "bSortable" : false },
            { "data": "actions", "bSortable" : false, 'width': '10px' },
        ],
        "fnInitComplete": function() {bind_action_link();},
        "language": {
                "url": "//cdn.datatables.net/plug-ins/f2c75b7247b/i18n/Spanish.json"
        },
    });

  }
  /*
  *   if Edit o new Dairy
  */
  if ($("#id-dairy-form").length > 0){
    var popul_own = eval($("#owner_id").data('populate'));
    $("#owner_id").tokenInput($("#owner_id").data('source'), {
        minChars: 3,
        tokenLimit: 1,
        hintText: "Dueño del Tambo?",
        noResultsText: "No Encontrado",
        searchingText: "Buscando...",
        theme: "facebook",
        propertyToSearch: "fullname",
        prePopulate: popul_own,
        
    });
    var popul_vet = eval($("#veterinary_id").data('populate'));
    $("#veterinary_id").tokenInput($("#veterinary_id").data('source'), {
        minChars: 3,
        tokenLimit: 1,
        hintText: "Veterinario?",
        noResultsText: "No Encontrado",
        searchingText: "Buscando...",
        theme: "facebook",
        propertyToSearch: "fullname",
        prePopulate: popul_vet,
        
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