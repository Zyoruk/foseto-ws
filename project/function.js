function logOut(){
  $.removeCookie('userInfo', { path: '/' });
  location.href='index.html';
}

$(document).on('click', '[data-toggle="popover"]', function(event) {
  event.preventDefault();
  $('[data-toggle="popover"]').popover();
});

$(document).ready(function () {

  if($.cookie("userInfo") == null){
    $("#hidden_menu").hide();
    $("#hidden_menu_admin").hide();
    $("#link_Orders").hide();
    $("#link_myOrders").hide();
    $("#link_person").hide();
    $("#comment_block").hide();
    $("#combos_link").hide();
  }else if($.cookie("userInfo") != null){
    var userid = $.cookie("userInfo");
    $("#tabs_login_register").hide();
    $("#link_person").show();
    $("#comment_block").show();
    if(userid == '0'){
      $("#hidden_menu").hide();
      $("#hidden_menu_admin").show();
      $("#combos_link").show();
      $("#link_Orders").hide();
      $("#link_myOrders").hide();
      $("#comment_block").hide();
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

});
