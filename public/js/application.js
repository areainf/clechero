GlobalDairy = {
  jqid:     "#navbar_select_dairies",
  jqdisable:     "#navbar_disable_select_dairies",
  jqupdate: "#url_if_change_dairy",
  bindChange: function(){
    var el = this;
    $(el.jqid).on('change', function(){
      var id = $(this).val();
      $.ajax({
        type: "GET",
        url: $(el.jqid).data("update"),
        data: {id: id},
        dataType:"json"
        , error: function(obj, textStatus, errorThrown){
        } 
        , success: function(data){ 
          if($(el.jqupdate).length > 0){          
            var url = $(el.jqupdate).val()
            if(id.length > 0)
              url = url.replace("dairy_id","dairy_id="+id);
            window.location = url;
          }
        }
      });
    });  
  },
  fire_change:function (dairy_id){
    var el = this;
    $(el.jqid).val(dairy_id);
    $(el.jqid).trigger('change');
  },
  _disable: function(){
    $(this.jqid).prop('disabled', 
      $(this.jqdisable).length > 0
    );
  },
  init: function(){
    this.bindChange();
    this._disable();
  }
}
$( document ).ready(function() {
  GlobalDairy.init();
  // $("#navbar_select_dairies").on('change', function(){
  //   var id = $(this).val();
  //   $.ajax({
  //     type: "GET",
  //     url: $("#navbar_select_dairies").data("update"),
  //     data: {id: id},
  //     dataType:"json"
  //     , error: function(obj, textStatus, errorThrown){
  //     } 
  //     , success: function(data){ 
  //       if($("#url_if_change_dairy").length > 0){          
  //         var url = $("#url_if_change_dairy").val()
  //         if(id.length > 0)
  //           url = url.replace("dairy_id","dairy_id="+id);
  //         window.location = url;
  //       }
  //     }
  //   });
  // });
  // function changeNavBarSelectDairy(dairy_id){
  //   $("#navbar_select_dairies").val(dairy_id);
  //   $("#navbar_select_dairies").trigger('change');
  // }
  //inicializa tooltips para analisis
  $('[data-toggle="tooltip"]').tooltip();
});