var precioTotal = 0;
var ingredientsarray = [];

//Suma todos los valores que están seleccionados
//Es para el precio final
function calcPrecioTotal(){
  precioTotal = 0;
  
  $(".calc:checked").each(function(){
    precioTotal += parseInt($(this).val(),10);
  });
  writePrice();
}

//Resta en caso de que un ingrediente ya no sea seleccionado
//Es para el precio final
function restPrecioTotal(price){
  precioTotal = precioTotal - parseInt(price,10);
  writePrice();
}

//Añade los ingredientes que se han escogido
//Menú derecho con los ingredientes
function calcArrayTotal(){
  ingredientsarray = [];

  $(".calc:checked").each(function(){
    var name = $(this).attr('name');

    ingredientsarray.push(name);
  });
  clasifyArray();
}

//Elimina un ingrediente en caso de que se quite la selección
//Menú derecho con los ingredientes
function restArrayTotal(ingredient){
  var index = ingredientsarray.indexOf(ingredient);

  if (index > -1) {
    ingredientsarray.splice(index, 1);
  }
  clasifyArray();
}

//Escribir el precio en la clase "text_precio"
function writePrice(){
    $(".text_precio").text("₡"+precioTotal);
}

//Escribir los ingredientes en el menú de la derecha
function writeIngredients(helados, toppings, adicionales){
  $("#ul_ingredients").empty();
  for (var i = 0; i < 3; i++) {
    var title = "";
    var index_array = [];

    switch (i) {
      case 0:
        title = "Helados";
        index_array = helados;
        break;
      case 1:
        title = "Toppings";
        index_array = toppings;
        break;
      case 2:
        title = "Adicionales";
        index_array = adicionales;
        break;
    }
    if(index_array.length > 0){
      if(i != 0){
        $("#ul_ingredients").append('<li role="separator" class="divider"></li>');
      }
      $("#ul_ingredients").append('<strong><li>'+title+'</li></strong>');
      for (var j = 0; j < index_array.length; j++) {
        $("#ul_ingredients").append('<li><a>'+index_array[j]+'</a></li>');
      }
    }
  }
}

//Separa los ingredientes por tipo
function clasifyArray(){
  var helados_array = [];
  var toppings_array = [];
  var adicionales_array = [];

  for (var i = 0; i < ingredientsarray.length; i++) {
    var ingredientType = ingredientsarray[i].slice(-1);

    var ingredientLoop = ingredientsarray[i].slice(0,-1);

    switch (ingredientType) {
      case '0': //Helado
        helados_array.push(ingredientLoop);
        break;
      case '1': //Topping
        toppings_array.push(ingredientLoop);
        break;
      case '2': //Adicionales
        adicionales_array.push(ingredientLoop);
        break;
    }
  }
  writeIngredients(helados_array,toppings_array,adicionales_array);
}

//Esconder/Mostrar menú derecha (la animación)
function arrow_link(){
  var isVisible = $( "#panel_price" ).is( ":visible" );

  if(isVisible){
    $('#trapezoid').animate({right: '-=95px'}, 'slow');
    $('#panel_price').animate({right: '-=95px'}, 'slow');
    $("#arrow_menu").attr('class', 'glyphicon glyphicon-chevron-left');
    $('#panel_price').hide();

  }else{
    $('#panel_price').show();
    $('#trapezoid').animate({right: '+=95px'}, 'slow');
    $("#arrow_menu").attr('class', 'glyphicon glyphicon-chevron-right');
    $('#panel_price').animate({right: '+=95px'}, 'slow');
  }

}


$(document).ready(function () {

  $("#top-link-block").hide();

  //Esconder/Mostrar menú derecho cuando se toca crear nueva orden
  $('.accordion_menu').click(function(e){
    if(e.target.id != ""){
      var isVisible = $( "#top-link-block" ).is( ":visible" );

      if(isVisible){
        $("#top-link-block").hide();
      }else{
        $("#top-link-block").show();
      }
    }else{
      $("#top-link-block").hide();
    }
  });

  //GET de los ingredientes
  $.ajax({
      type: "GET",
      url: "../webservices/newOrder.php",
      dataType: 'json',
      success: function(data){
        var obj = JSON.parse(data);
        $.each(obj,function(index,element){
          var ingredient_type = element.type;
          var ul_name = "";
          switch (ingredient_type) {
            case '0':
              ul_name = "#list_helados";
              break;
            case '1':
              ul_name = "#list_topping";
              break;
            case '2':
              ul_name = "#list_adicional";
              break;
          }

          $(ul_name).append('<li class="IngredientsItem"><div class="form-group"><div class="checkbox"><label><input type="checkbox" id="'+element.name+ingredient_type+'"><img src="helado1.png"/></label></div><center><strong>'+element.name+'</strong></center><div class="radio '+element.name+ingredient_type+'"><label class="radio"><input class="calc" type="radio" name="'+element.name+ingredient_type+'" value="'+(parseInt(element.price,10))/2+'" disabled>Poco - ₡'+(parseInt(element.price,10))/2+'</label><label class="radio"><input class="calc" type="radio" name="'+element.name+ingredient_type+'" value="'+element.price+'" disabled>Regular - ₡'+element.price+'</label><label class="radio"><input class="calc" type="radio" name="'+element.name+ingredient_type+'" value="'+(parseInt(element.price,10))*2+'" disabled>Mucho - ₡'+(parseInt(element.price,10))*2+'</label></div></div></li>');

        });
      }
    });

  });

//Evento para detectar cambios en un combobox
$(document).on('click', 'input:checkbox', function(event) {
  var checkbox_id = event.target.id;
  var checkbox_checked = $("#" + checkbox_id).prop('checked');

  if(checkbox_checked){
    $("." + checkbox_id).find('input').removeAttr("disabled");
  }else{
    $("." + checkbox_id).find('input').attr('disabled','disabled');
    var myRadio = $('input[name='+checkbox_id+']');
    var checkedValue = myRadio.filter(':checked').val();
    restPrecioTotal(checkedValue);
    restArrayTotal(checkbox_id);
    $("." + checkbox_id).find('input').attr('checked',false);
  }
});

//Evento para detectar actividad en un radiobutton
$(document).on('click', 'input:radio', function(event) {
  var radio_name = event.target.name;
  var radio_value = event.target.value;

  calcPrecioTotal();
  calcArrayTotal();
});
