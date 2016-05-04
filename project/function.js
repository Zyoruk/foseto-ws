function calcscore(){
  var score = 0;
  $(".calc:checked").each(function(){
    score+=parseInt($(this).val(),10);
  });
  $("#precio-subtotal").text(score)
}

function restscore(value_before){
  var score = parseInt($("#precio-subtotal").text(),10);
  //alert("score " + score + " valor " + value_before);
  $("#precio-subtotal").text(score - value_before);
}

$(document).ready(function () {

  var $class_name, $class_id;
  $("#precio-radio_1").hide();
  $("#precio-radio_2").hide();
  $("#precio-radio_3").hide();

  $("input:checkbox").change(function(event) {
    /*alert(event.target.id+" and "+$(event.target).attr('class'));*/
    $class_name = $(event.target).attr('class');
    $class_id = event.target.id;

    if($("."+ $class_name).is(":checked")){
      $("." + $class_id).find('input').removeAttr("disabled");
      /*$("#precio-"+$class_id).show();*/
    }else{
      $("." + $class_id).find('input').attr('disabled','disabled');
      $("." + $class_id).find('input').attr('checked',false);
      /*var value_before = parseInt($("#precio-"+$class_id).text(),10);
				restscore(value_before);
				$("#precio-"+$class_id).text('0');
				$("#precio-"+$class_id).hide();*/
    }
  });

  $('input:radio').on('change', function(event){
    /* alert(event.target.name + " and " + event.target.value); */
    var $precio_name = event.target.name;
    var $precio_value = event.target.value;
    $("#precio-" + $precio_name).text($precio_value);
    $("#precio-" + $precio_name).show();
  });

  $(".calc").change(function(){
    calcscore()
  });

  $('[data-toggle="popover"]').click(function(e) {
    e.preventDefault();
  });

  $('[data-toggle="popover"]').popover({
    placement : 'bottom',
    html : true,
    content: function () {
      var nombre = jQuery(this).attr("id");
      return '<center><h5><strong>Cantidad:</strong></h5></center><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;width:100%;">' + nombre + '</div></br>';
    }
  });
});
