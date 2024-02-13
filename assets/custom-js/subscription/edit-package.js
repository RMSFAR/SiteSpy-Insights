"use strict";


$(document).ready(function() {
    if($("#price_default").val()=="0") $("#hidden").hide();
    else $("#validity").show();
    $("#all_modules").change(function(){
      if ($(this).is(':checked')) 
      $(".modules:not(.mandatory)").prop("checked",true);
      else
      $(".modules:not(.mandatory)").prop("checked",false);
    });
    $("#price_default").change(function(){
      if($(this).val()=="0") $("#hidden").hide();
      else $("#hidden").show();
    });
  });