$( document ).ready(function() {
  $("#navbar_select_dairies").on('change', function(){
    $.ajax({
      type: "GET",
      url: $("#navbar_select_dairies").data("update"),
      data: {id: $(this).val()},
      dataType:"json"
      , error: function(obj, textStatus, errorThrown){
      } 
      , success: function(data){ 
      }
    });
  });
});