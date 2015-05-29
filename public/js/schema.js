$( document ).ready(function() {
  /*
   *  if view datatable user action index 
   */
    if ($("#id-schema-list").length > 0){
        $('#table-schema').dataTable( {
                "ajax": $('#table-schema').data('source'),
                "processing": true,
                "serverSide": true,
                "order": [ 1, "desc" ],
                "columns": [
                    { "data": "id", "visible": false},
                    { "data": "dairy", "bSortable" : false  },
                    { "data": "date", "bSortable" : false  },
                    { "data": "liters_milk", "bSortable" : false  },
                    { "data": "milk_price", "bSortable" : false  },
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
        // var schemaJson = {
        //     fn: {date: 'date',
        //         desinf_pre_o_producto: 'desinf_pre_o_producto',
        //         desinf_pre_o_precio: 'desinf_pre_o_precio',
        //         desinf_pre_o_dias: 'desinf_pre_o_dias',
        //         desinf_post_o_producto: 'desinf_post_o_producto',
        //         desinf_post_o_precio: 'desinf_post_o_precio',
        //         desinf_post_o_dias: 'desinf_post_o_dias',
        //         tmc_ab_pomo_cantidad: 'tmc_ab_pomo_cantidad',
        //         tmc_ab_pomo_precio: 'tmc_ab_pomo_precio',
        //         tmc_ab_inyect_cantidad: 'tmc_ab_inyect_cantidad',
        //         tmc_ab_inyect_precio: 'tmc_ab_inyect_precio',
        //         tmc_ai_inyect_cantidad: 'tmc_ai_inyect_cantidad',
        //         tmc_ai_inyect_precio: 'tmc_ai_inyect_precio',
        //         ts_pomo_precio: 'ts_pomo_precio',
        //         machine_control_price: 'machine_control_price',
        //         machine_control_days: 'machine_control_days',
        //         liters_milk: 'liters_milk',
        //         milk_price: 'milk_price'},
        // };
        Esquema = {
            schemas: [],
            container: {
                root: "#id-form-schema",
            },
            f_sel_dairy: "#dairy_id",
            f_sel_schema: "#copy_from_schema",
            schemaJson:{
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
            },
            bind_change_dairy: function(){
                var el = this;
                $(el.container.root + " " + el.f_sel_dairy).on("change", function(event){
                    var id = $(this).val();
                    $.ajax({
                        type: "GET",
                        url: 'dairy/getSchemasJson',
                        data: {id: id},
                        dataType:"json",
                        success: function(data){
                            el.schemas = data;
                            var select = $(el.f_sel_schema);
                            select.find('option').remove();
                            select.append('<option value="">Nuevo Esquema de Control</option>');
                            if(data.length > 0){
                                for (var i = 0; i < data.length; i++) {
                                    var s = data[i];
                                    select.append('<option value="'+s['id']+'">'+ s['date'] +'</option>');
                                }
                            }
                        }
                    });
                    GlobalDairy.fire_change(id);
                });
            },
            bind_change_schema: function(){
                var el = this;
                $(el.container.root + " " + el.f_sel_schema).on("change", function(event){
                    var sel = $(this).val();
                    if(sel == ""){
                      for (var k in el.schemaJson.fn) {
                        $("#"+k).val("");
                      };
                    }
                    else{
                      var data = el.schemas[$(this).prop('selectedIndex')-1]; 
                      for (var k in data) {
                        if($("#"+k).length > 0)
                          $("#"+k).val(data[k]);
                      };
                    }
                });
                // if(data.length != ''){
            //     //             for (var k in data) {
            //     //                 if($("#"+k).length > 0)
            //     //                     $("#"+k).val(data[k]);
            //     //             };
            //     //         }
            //     //         else{
            //     //             for (var k in schemaJson.fn) {
            //     //                 $("#"+k).val("");
            //     //             };
            //     //         }
            },
            init: function(){
                this.bind_change_dairy();
                this.bind_change_schema();
                //cuando se carga la pagina llena el select 
                // de esquemas con el tambo seleccionado
                $(this.container.root + " " + this.f_sel_dairy).trigger("change");
            },
        };
        //si es new
        if($("#schema_id").length == 0){
            // $("#id-form-schema #dairy_id").on("change", function(event){
            //     var id = $(this).val();
            //     // $.ajax({
            //     //     type: "GET",
            //     //     url: 'last_json',
            //     //     data: {dairy_id: id},
            //     //     dataType:"json"
            //     //     , error: function(obj, textStatus, errorThrown){
            //     //     } 
            //     //     , success: function(data){ 
            //     //         if(data.length != ''){
            //     //             for (var k in data) {
            //     //                 if($("#"+k).length > 0)
            //     //                     $("#"+k).val(data[k]);
            //     //             };
            //     //         }
            //     //         else{
            //     //             for (var k in schemaJson.fn) {
            //     //                 $("#"+k).val("");
            //     //             };
            //     //         }
            //     //     }
            //     // });
            //     $.ajax({
            //         type: "GET",
            //         url: 'dairy/getSchemasJson',
            //         data: {id: id},
            //         dataType:"json"
            //         , error: function(obj, textStatus, errorThrown){
            //         } 
            //         , success: function(data){
            //             schemas = data;
            //             $("#copy_from_schema").find('option').remove();
            //             var obj = $("#copy_from_schema");
            //             obj.append('<option value="">Crear uno nuevo</option>');
            //             if(data.length > 0){
            //                 for (var i = 0; i < data.length; i++) {
            //                     var s = data[i];
            //                     obj.append('<option value="'+s['id']+'">'+ s['date'] +'</option>');
            //                 }
            //             }
            //         }
            //     });
            //     GlobalDairy.fire_change(id);
            // });
            //cuando se carga la pagina llena el select 
            // de esquemas con el tambo seleccionado
            // $("#id-form-schema #dairy_id").trigger("change");
            Esquema.init();
        }
    }
});
