var precioTotal = 0;
var ingredientsarray = [];
var editType = 0; //0 new order | 1 edit order
var oid="";

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

function editOrder(id){
  $(':checkbox').prop('checked',false);
  $(':radio').prop('checked',false);
  $(':radio').attr('disabled','disabled');

  $('#collapse2').collapse('hide');
  $('#collapse3').collapse('show');
  $("#top-link-block").show();

  oid = id;

  $.ajax({
      type: "GET",
      url: "../webservices/editOrder.php",
      data:  {oid:id},
      dataType: 'json',
      success: function(data){
        var obj = JSON.parse(data);
        $.each(obj,function(index,element){
            var name = obj[index].name;
            var type = obj[index].type;
            var quantity = obj[index].quantity;
            var checkbox_id = name + type;
            //alert(name + type + quantity);
            if(obj[index].available == '1'){
              $('#'+checkbox_id).prop('checked', true);
              $("." + checkbox_id).find('input').removeAttr("disabled");
              document.getElementById(checkbox_id+quantity).checked = true;
            }
        });
        calcPrecioTotal();
        calcArrayTotal();
      }
    });
}

function sendOrder(){
  var ingredientsSO=[];
  $(".calc:checked").each(function(){
    var text = $(this).attr('text');
    var name = $(this).attr('name');
    ingredientsSO.push(name+","+text);
  });
  if(editType == 0){
    $.ajax({
        type: "POST",
        url: "../webservices/order.php",
        data: {co:'co',cid:$.cookie("userInfo"),ing:ingredientsSO,total:precioTotal},
        success:function(data){
          location.reload();
        }
      });
  }else if(editType == 1){
    editType = 0;
    $.ajax({
        type: "POST",
        url: "../webservices/order.php",
        data: {uo:'uo',oid:oid,ing:ingredientsSO,total:precioTotal},
        success:function(data){
          location.reload();
        }
      });
  }
}

function edit_button(elem){
  alert("El ingrediente " + elem.getAttribute("name") + " el tipo " + elem.getAttribute("value"));
}

function chageimg(ele, val){
  $("#" + ele).attr("src",val);
  //document.getElementById("#" + ele).src=val;
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
				$(ul_name).append('<li class="IngredientsItem"><div class="form-group"><div class="checkbox"><label><input type="checkbox" id="'+element.name+ingredient_type+'"><img class="ingredient_image" src="'+element.image+'"/></label></div><center><strong>'+element.name+'</strong></center><div class="radio '+element.name+ingredient_type+'"><label class="radio"><input class="calc" type="radio" name="'+element.name+ingredient_type+'" id="'+element.name+ingredient_type+'P" text="Poco" value="'+(parseInt(element.price,10))/2+'" disabled>Poco - ₡'+(parseInt(element.price,10))/2+'</label><label class="radio"><input class="calc" type="radio" name="'+element.name+ingredient_type+'" id="'+element.name+ingredient_type+'R" text="Regular" value="'+element.price+'" disabled>Regular - ₡'+element.price+'</label><label class="radio"><input class="calc" type="radio" name="'+element.name+ingredient_type+'" id="'+element.name+ingredient_type+'M" text="Mucho" value="'+(parseInt(element.price,10))*2+'" disabled>Mucho - ₡'+(parseInt(element.price,10))*2+'</label></div></div></li>');
			});
		}
	});

    //GET de los ingredientes admin
	$.ajax({
		type: "GET",
		url: "../webservices/ingredientsAdmin.php",
		dataType: 'json',
		success: function(data){
			var obj = JSON.parse(data);
			$.each(obj,function(index,element){
					var ingredient_type = element.type;
					var ul_name_new = "";
					switch (ingredient_type) {
						case '0':
							ul_name_new = "#list_helados_new";
							break;
						case '1':
							ul_name_new = "#list_topping_new";
							break;
						case '2':
							ul_name_new = "#list_adicional_new";
							break;
					}
				var ava = element.available;
				var disp ="";
				switch (ava) {
					case '0':
						disp = "No disponible";
						break;
					case '1':
						disp = "Disponible";
						break;
				}
				$(ul_name_new).append('<li class="IngredientsItem"><center><div class="form-group"><div class="checkbox"><img class="ingredient_image" src="'+element.image+'"/></center></div><center><strong>'+element.name+'</strong><br><strong>Precio: </strong>'+element.price+'<br>'+disp+'<p><button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal_editIngredient" data-ingredient-id="'+element.id+'" data-ingredient-type="'+element.name+'" data-ingredient-img="'+element.image+'" data-ingredient-available="'+element.available+'" data-ingredient-price="'+element.price+'">Editar</button>&nbsp;<button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal_delIngredient" data-ingredient-id="'+element.id+'" data-ingredient-type="'+element.name+'">Eliminar</button></p></div></center></li>');
			});
		}
	});


	//Get de las ordenes
	$.ajax({
		type: "GET",
		url: "../webservices/history.php",
		data:  {cid:$.cookie("userInfo")},
		dataType: 'json',
		success: function(data){
			var obj = JSON.parse(data);
			var orders = [];
			for(var i = 0; i < obj.length; i++){
				var number = obj[i].order_id;
				var flag = false;
				for(var j = 0; j < orders.length; j++){
					if(number == orders[j]){
					flag = true;
					break;
					}
				}
				if(!flag){
					orders.push(number);
				}
			}

			$.each(orders,function(index,element){

				var order_info = "";
				for(var i = 0; i < obj.length; i++){
					if(element == obj[i].order_id){
						order_info = obj[i];
						break;
					}
				}

				var li = '<li class="OrdersItem"><div id="container">';
				li = li + '<center><h3><strong>'+order_info.order_id+'</strong></h3></center><center><h5><strong>'+order_info.created+'</strong></h5></center><center><h4>₡'+order_info.total+'</h4></center>';
				li = li + '<div class="panel-group">';
				for(var i = 0; i < 3; i++){
					var title = "";
					switch (i) {
						case 0:
							title = "Helados";
							break;
						case 1:
							title = "Toppings";
							break;
						case 2:
							title = "Adicionales";
							break;
					}

					var order_ingredients = [];
					for(var k = 0; k < obj.length;k++){
						if(order_info.order_id == obj[k].order_id && i == obj[k].type){
							order_ingredients.push(obj[k]);
						}
					}

					li = li + '<div class="panel panel-default"><div class="panel-heading">'+title+'</div><div class="panel-body"><div class="content"><ul class="list-inline">';
					for(var j = 0; j < order_ingredients.length; j++){
						li = li + '<li><a href="#" class="prueba_pop" id="'+order_ingredients[j].quantity+'" title="'+order_ingredients[j].name+'" data-toggle="popover" data-trigger="focus" data-content="Cantidad: '+order_ingredients[j].quantity+'"><img width="30" height="31" src="'+order_ingredients[j].image+'"/></a></li>';
					}
					li = li + '</ul></div></div></div>';
				}
				li = li + '</div><center><p><button type="button" data-edit="yes" class="btn btn-default btn-xs editButton" value="'+order_info.order_id+'">Editar Orden</button>&nbsp;<button type="button" class="btn btn-default btn-xs">Pedir Orden</button></p></center></div></li>';
				$("#list_orderhistory").append(li);
			});
		}
    });

    //Get de las current orders
    $.ajax({
        type: "GET",
        url: "../webservices/currentOrders.php",
        data:  {cid:$.cookie("userInfo")},
        dataType: 'json',
        success: function(data){
			if(data != ']'){
				var obj = JSON.parse(data);
				var orders = [];
				for(var i = 0; i < obj.length; i++){
					var number = obj[i].order_id;
					var flag = false;
					for(var j = 0; j < orders.length; j++){
						if(number == orders[j]){
							flag = true;
							break;
						}
					}
					if(!flag){
						orders.push(number);
					}
				}

				$.each(orders,function(index,element){

					var order_info = "";
					for(var i = 0; i < obj.length; i++){
						if(element == obj[i].order_id){
							order_info = obj[i];
							break;
						}
					}

					var li = '<li class="OrdersItem"><div id="container">';
					li = li + '<center><h3><strong>Orden: </strong>'+number+'</h3></center><center><h4>₡'+order_info.total+'</h4></center><div class="progress"><div class="progress-bar progress-bar-striped progress-bar-warning active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%"></div></div><div class="panel panel-default"><div class="panel-heading">Estado Actual</div><div class="panel-body"><center><h4><strong>Ingrediente 1</strong></h4></center></div></div><br></div></li>';

					$("#list_currentOrders").append(li);
				});
			}
		}
    });

  //Get de los combos
      $.ajax({
          type: "GET",
          url: "../webservices/history.php",
          data:  {cid:"0"},
          dataType: 'json',
          success: function(data){
            var obj = JSON.parse(data);
            var orders = [];
            for(var i = 0; i < obj.length; i++){
              var number = obj[i].order_id;
              var flag = false;
              for(var j = 0; j < orders.length; j++){
                if(number == orders[j]){
                  flag = true;
                  break;
                }
              }
              if(!flag){
                orders.push(number);
              }
            }

            $.each(orders,function(index,element){

              var order_info = "";
              for(var i = 0; i < obj.length; i++){
                if(element == obj[i].order_id){
                  order_info = obj[i];
                  break;
                }
              }
              var li = '<li class="OrdersItem"><div id="container">';
              li = li + '<center><h3><strong>Combo '+(parseInt(index)+1)+'</strong></h3></center><center><h4>₡'+order_info.total+'</h4></center>';
              li = li + '<div class="panel-group">';
              for(var i = 0; i < 3; i++){
                 var title = "";
                switch (i) {
                  case 0:
                    title = "Helados";
                    break;
                  case 1:
                    title = "Toppings";
                    break;
                  case 2:
                    title = "Adicionales";
                    break;
                }

                var order_ingredients = [];
                for(var k = 0; k < obj.length;k++){
                  if(order_info.order_id == obj[k].order_id && i == obj[k].type){
                    order_ingredients.push(obj[k]);
                  }
                }

              li = li + '<div class="panel panel-default"><div class="panel-heading">'+title+'</div><div class="panel-body"><div class="content"><ul class="list-inline">';
              for(var j = 0; j < order_ingredients.length; j++){
                li = li + '<li><a href="#" class="prueba_pop" id="'+order_ingredients[j].quantity+'" title="'+order_ingredients[j].name+'" data-toggle="popover" data-trigger="focus" data-content="Cantidad: '+order_ingredients[j].quantity+'"><img width="30" height="31" src="'+order_ingredients[j].image+'"/></a></li>';
              }
               li = li + '</ul></div></div></div>';
             }
            li = li + '</div><center><p><button type="submmit, button" class="btn btn-default btn-xs">Editar Combo</button>&nbsp;<button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal_delOrder" data-order-id="'+order_info.order_id+'">Eliminar Combo</button></p></center></div></li>';
            $("#list_combos").append(li);
          });
          }
        });

	if($.cookie("varEdit") != null){
        editOrder($.cookie("varEdit"));
        $.removeCookie("varEdit", { path: '/' });
        editType = 1;
      }
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

$(document).on('click',"#ordenes_pendientes",function(){
  $( 'button[ data-edit="yes"]' ).addClass( 'editExistingButton' );
  $( 'button[ data-edit="yes"]' ).removeClass( 'editButton' );
});

$(document).on('click',".editExistingButton", function() {
  $.cookie("varEdit", $(this).val(), { path: '/' });
  window.location.href = "ordenes.html";
});

$(document).on('click',".editButton", function() {
  editOrder($(this).val());
});

$('#modal_newIngredient').on('show.bs.modal', function(e) {
    var ingredientName = $(e.relatedTarget).data('ingredient-type');
    var ingredientType = $(e.relatedTarget).data('ingredient-number');
    $("#myModalLabel").text("Agregar " + ingredientName);
    $("#type").val(ingredientType);
});
$('#modal_editIngredient').on('show.bs.modal', function(e) {
    var ingredientName = $(e.relatedTarget).data('ingredient-type');
    var ingredientId = $(e.relatedTarget).data('ingredient-id');
    var ingredientImg = $(e.relatedTarget).data('ingredient-img');
    var ingredientavailable = $(e.relatedTarget).data('ingredient-available');
    var ingredientprice = $(e.relatedTarget).data('ingredient-price');

    $("#myModalLabelEdit").text("Editar " + ingredientName);
    $("#ingid").val(ingredientId);
    $("#name_ingredient").val(ingredientName);
    $("#image_ingredient_up").attr("src",ingredientImg);
    $("#link_ingredient").val(ingredientImg);
    $("#price_ingredient").val(ingredientprice);
    if(ingredientavailable == '0'){
      $("#available_no_ingredient").prop('checked',true);
    }else if(ingredientavailable == '1'){
      $("#available_yes_ingredient").prop('checked',true);
    }
});

$('#modal_delIngredient').on('show.bs.modal', function(e) {
    var ingredientName = $(e.relatedTarget).data('ingredient-type');
    var ingredientId = $(e.relatedTarget).data('ingredient-id');
    $("#ingid1").val(ingredientId);
    $("#p_error").text("¿Seguro que desea borrar " + ingredientName + "?");
});

$('#modal_delOrder').on('show.bs.modal', function(e) {
    var orderId = $(e.relatedTarget).data('order-id');
    $("#orderid1").val(orderId);
    $("#p_error").text("¿Seguro que desea borrar este combo?");
});
