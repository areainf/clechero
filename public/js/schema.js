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
                    { "data": "dairy", "bSortable" : true  },
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
      $('form').off('submit.Parsley');
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
                      Erogaciones.clear();
                    }
                    else{
                      var data = el.schemas[$(this).prop('selectedIndex')-1];
                      Erogaciones.clear();
                      for (var k in data) {
                        if($("#"+k).length > 0)
                          $("#"+k).val(data[k]);
                      };
                      for(var key in data['erogaciones']){
                        var ero = data['erogaciones'][key]
                        var er = new Erogacion(undefined,
                                     ero['name'],
                                     ero['description'],
                                     ero['price'],
                                     ero['days'],
                                     ero['apply_to']);
                        Erogaciones.add(er);
                      }
                    }
                    $("#date").focus();
                });
                // if(data.length != ''){
            },
            init: function(){
                this.bind_change_dairy();
                this.bind_change_schema();
                //cuando se carga la pagina llena el select
                // de esquemas con el tambo seleccionado
                $(this.container.root + " " + this.f_sel_dairy).trigger("change");
            },
        };
        Erogaciones = {
          container: {root: '#table-erogaciones', row_new: '#tr-new-erogacion' },
          button: {add: '#erogaciones_add'},
          f:{id: '#erogaciones_id', name: '#erogaciones_name', description: '#erogaciones_description',
             price: '#erogaciones_price', days: '#erogaciones_days', apply_to: '#erogaciones_apply_to'},
          list: [],
          temp_id: -1,
          prefix_row: 'tr-erogacion_',
          init: function(){
            this._bindAdd();
          },
          _bindAdd: function(){
            var el = this;
            $(this.button.add).on('click', function(evento){
              evento.preventDefault();
              if($(form).parsley().validate('block_erogacion')){
                var er = new Erogacion(el.temp_id--,
                                       $(el.f.name).val(),
                                       $(el.f.description).val(),
                                       $(el.f.price).val(),
                                       $(el.f.days).val(),
                                       $(el.f.apply_to).val());
                // el.list[er.id] = er;
                // el._draw(er);
                el.add(er);
                el.clear_form();
              }
              return false;

            });
          },
          add: function(er){
            if (er.id == undefined)
                er.id = this.temp_id--;
            this.list[er.id] = er;
            this._draw(er);
          },
          _draw: function(er){
            var fname = "erogaciones["+er.id+"][";
            var tr = '<tr id="'+this.prefix_row+er.id+'">';
            tr +='<input type="hidden" name="'+fname+'name]" value="'+er.name+'">'+
                  '<input type="hidden" name="'+fname+'description]" value="'+er.description+'">'+
                  '<input type="hidden" name="'+fname+'price]" value="'+er.price+'">'+
                  '<input type="hidden" name="'+fname+'days]" value="'+er.days+'">'+
                  '<input type="hidden" name="'+fname+'apply_to]" value="'+er.apply_to+'">';

            tr +='<td>'+er.name+'</td>';
            tr +='<td>'+er.description+'</td>';
            tr +='<td>$ '+er.price+'</td>';
            tr +='<td>'+er.days+'</td>';
            tr +='<td>'+$(this.f.apply_to + ' option[value="' + er.apply_to + '"]').html()+'</td>';
            tr +='<td><a href="" class="btn btn-danger" onClick="return Erogaciones.delete('+er.id+',event);"><span class="glyphicon glyphicon-remove-circle"></span></a></td>';
            tr +='</tr>';
            $(tr).insertBefore($(this.container.row_new));
          },
          clear_form: function(){
            $(this.f.name).val("");
            $(this.f.description).val("");
            $(this.f.price).val("");
            $(this.f.days).val("");
            $(this.f.name).focus();
          },
          clear: function(){
            var el = this;
            this.clear_form();
            $(this.container.root+' tbody tr').each(function(i, tr){
                if("#"+tr.id != el.container.row_new)
                    tr.remove();
            });
          },
          delete: function(id, evento){
            var evento = evento || windows.event;
            evento.preventDefault();
            delete this.list[id];
            $("#"+this.prefix_row+id).remove();
            return false;
          },
        };

        var Erogacion = function(id, name, description, price, days, apply_to){
          this.id = id;
          this.name = name;
          this.description = description;
          this.price = price;
          this.days = days;
          if(apply_to == undefined)
            apply_to = 0;
          this.apply_to = apply_to;
        }
        //si es new
        if($("#schema_id").length == 0){
            Esquema.init();
            Erogaciones.init();
        }

    }
    if ($("#schema-result_compare").length > 0){
      $(".modal-show-cronicas").on("mouseover", function(){
        $("#id-modal-cronicas").modal("show");
      });
      $(".modal-show-infectadas").on("mouseover", function(){
        $("#id-modal-infectadas").modal("show");
      });
      $(".modal-show-curadas").on("mouseover", function(){
        $("#id-modal-curadas").modal("show");
      });
    }
});
