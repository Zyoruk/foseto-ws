function logOut(){
  $.removeCookie('userInfo', { path: '/' });
  location.href='index.html';
}

$(document).ready(function () {

  if($.cookie("userInfo") == null){
    $("#hidden_menu").hide();
    $("#link_Orders").hide();
    $("#link_myOrders").hide();
    $("#link_person").hide();
  }else if($.cookie("userInfo") != null){
    $("#tabs_login_register").hide();
    $("#hidden_menu").show();
    $("#link_Orders").show();
    $("#link_myOrders").show();
    $("#link_person").show();

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
