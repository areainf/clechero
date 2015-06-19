$( document ).ready(function() {
  /*
   *  if view datatable user action index 
   */
  if ($("#id-dairycontrol-list").length > 0){
    // si esta en el listado de reservas
    $('#table-dairycontrol').dataTable( {
        "ajax": $('#table-dairycontrol').data('source'),
        "processing": true,
        "serverSide": true,
        "columns": [
            { "data": "id", "visible": false},
            { "data": "caravana" },
            { "data": "nop" },
            { "data": "dl" },
            { "data": "date_dl" },
            { "data": "rcs" },
            { "data": "mc" },
            { "data": "liters_milk" },
            { "data": "baja" },
            { "data": "fecha_baja" },
            { "data": "perdida" },
            { "data": "dml" },
            // { "data": "actions", "bSortable" : false, 'width': '10px' },
        ],
        "language": {
                "url": "//cdn.datatables.net/plug-ins/f2c75b7247b/i18n/Spanish.json"
        },
    });

  }
  if ($("#id-dairycontrol-form").length > 0){
    var popul_cow = eval($("#cow_id").data('populate'));
    $("#cow_id").tokenInput($("#cow_id").data('source'), {
        minChars: 1,
        tokenLimit: 1,
        hintText: "N° Caravana?",
        noResultsText: "No Encontrado",
        searchingText: "Buscando...",
        theme: "facebook",
        propertyToSearch: "caravana",
        prePopulate: popul_cow,
    });
  }

  //cambio rapido de esquema
  if($("#dairycontrol_schemas #select_schema").length > 0){
    //Cambio de esquema en analisis
    $("#dairycontrol-analisis #dairycontrol_schemas #select_schema").on('change', function(){
      var id = $("#dairycontrol_schemas #select_schema").val();
      window.location = "dairycontrol/analisis?schema_id="+id;
    });
  }


});