$( document ).ready(function() {
  /*
   *  if view datatable user action index 
   */
    if ($("#id-schema-list").length > 0){
        $('#table-schema').dataTable( {
                "ajax": $('#table-schema').data('source'),
                "processing": true,
                "serverSide": true,
                "columns": [
                    { "data": "id", "visible": false},
                    { "data": "dairy" },
                    { "data": "date" },
                    { "data": "liters_milk" },
                    { "data": "milk_price" },
                    { "data": "desinf_pre_o", "bSortable" : false },
                    { "data": "desinf_post_o", "bSortable" : false },
                    { "data": "tmc_ab_pomo_pc", "bSortable" : false },
                    { "data": "tmc_ab_inyect_pc", "bSortable" : false },
                    { "data": "tmc_ai_inyect_pc", "bSortable" : false },
                    { "data": "ts_pomo_precio", "bSortable" : false },
                    { "data": "machine_control_pd", "bSortable" : false },
                    { "data": "actions", "bSortable" : false, 'width': '20px' },
                ],
                "language": {
                        "url": "//cdn.datatables.net/plug-ins/f2c75b7247b/i18n/Spanish.json"
                },
        });
    }
    if($("#id-form-schema")){
        var schemaJson = {
            fn: {date: 'date',
                desinf_pre_o_producto: 'desinf_pre_o_producto',
                desinf_pre_o_precio: 'desinf_pre_o_precio',
                desinf_pre_o_dias: 'desinf_pre_o_dias',
                desinf_post_o_producto: 'desinf_post_o_producto',
                desinf_post_o_precio: 'desinf_post_o_precio',
                desinf_post_o_dias: 'desinf_post_o_dias',
                tmc_ab_pomo_cantidad: 'tmc_ab_pomo_cantidad',
                tmc_ab_pomo_precio: 'tmc_ab_pomo_precio',
                tmc_ab_inyect_cantidad: 'tmc_ab_inyect_cantidad',
                tmc_ab_inyect_precio: 'tmc_ab_inyect_precio',
                tmc_ai_inyect_cantidad: 'tmc_ai_inyect_cantidad',
                tmc_ai_inyect_precio: 'tmc_ai_inyect_precio',
                ts_pomo_precio: 'ts_pomo_precio',
                machine_control_price: 'machine_control_price',
                machine_control_days: 'machine_control_days',
                liters_milk: 'liters_milk',
                milk_price: 'milk_price'},
        };
        if($("#schema_id").length == 0){
            $("#dairy_id").on("change", function(event){
                var id = $(this).val();
                $.ajax({
                    type: "GET",
                    url: 'last_json',
                    data: {dairy_id: id},
                    dataType:"json"
                    , error: function(obj, textStatus, errorThrown){
                    } 
                    , success: function(data){ 
                        console.log(data);
                        if(data.length != ''){
                            for (var k in data) {
                                console.log(k);
                                if($("#"+k).length > 0)
                                    $("#"+k).val(data[k]);
                            };
                        }
                        else{
                            for (var k in schemaJson.fn) {
                                $("#"+k).val("");
                            };
                        }
                    }
                });
            });
        }
    }
});
