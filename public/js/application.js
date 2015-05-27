$( document ).ready(function() {
  $("#navbar_select_dairies").on('change', function(){
    var id = $(this).val();
    $.ajax({
      type: "GET",
      url: $("#navbar_select_dairies").data("update"),
      data: {id: id},
      dataType:"json"
      , error: function(obj, textStatus, errorThrown){
      } 
      , success: function(data){ 
        if($("#url_if_change_dairy").length > 0){          
          var url = $("#url_if_change_dairy").val()
          if(id.length > 0)
            url = url.replace("dairy_id","dairy_id="+id);
          window.location = url;
        }
      }
    });
  });
  
  //inicializa tooltips para analisis
  $('[data-toggle="tooltip"]').tooltip()

});