function sendComment(){
    var stars = $("#write-comment").val();
    var id = $.cookie("userInfo");
    var comment = $("#textbox_comment").val();

    $.ajax({
        type: "POST",
        url: "../webservices/user.php",
        data: {pc:'pc',uid:id,tx:comment,rt:stars},
        success:function(data){
          location.reload();
        }
      });
}

$(document).ready(function () {
  //Get comments
  $.ajax({
      type: "GET",
      url: "../webservices/comment.php",
      dataType: 'json',
      success: function(data){
        var obj = JSON.parse(data);
        for (var i = 0; i < obj.length; i++) {
          $("#load_comments").append('<div class="panel panel-default"><div class="panel-body"><blockquote><div class="container"><form><input type="text" class="rating-read rating-loading" data-size="0" value="'+obj[i].rating+'"></form></div><p>'+obj[i].text+'</p><footer>'+obj[i].name+'</footer></blockquote></div></div>');
        };
        $('.rating-read').rating('refresh',{showCaption: false,displayOnly: true});

      }
    });
  });
