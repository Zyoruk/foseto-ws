function logOut(){
  $.removeCookie('userInfo', { path: '/' });
  location.href='index.html';
}

$(document).ready(function () {

  if($.cookie("userInfo") == null){
    var currentLocation = window.location.pathname;
    if(currentLocation != "/Foseto/project/index.html" && currentLocation != "/Foseto/project/comentarios.html"){
      location.href='index.html';
    }
    $("#hidden_menu").hide();
    $("#hidden_menu_admin").hide();
    $("#link_Orders").hide();
    $("#link_myOrders").hide();
    $("#link_person").hide();
  }else if($.cookie("userInfo") != null){
    var userid = $.cookie("userInfo");
    $("#tabs_login_register").hide();
    $("#link_person").show();
    if(userid == '0'){
      $("#hidden_menu").hide();
      $("#hidden_menu_admin").show();
      $("#link_Orders").hide();
      $("#link_myOrders").hide();
    }else{
      $("#hidden_menu_admin").hide();
      $("#hidden_menu").show();
      $("#link_Orders").show();
      $("#link_myOrders").show();
    }

    $.ajax({
      type: "GET",
      url: "../webservices/profile.php",
      success: function(data){
      var obj = $.parseJSON(data);
      //alert(obj.name);
      $('#link_person_a').html("Hola, " + obj.name + " " + '<span class="caret"></span>');
      }
    });
  }

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
