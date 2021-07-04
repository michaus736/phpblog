//-----------------------------------------------------------------------------
// zadanie7.js
//-----------------------------------------------------------------------------
//
var modal_width = 450;
// funkcje pomocnicze
function modal_center(item){
   $(item).css({"position":"fixed",
                "width":modal_width.toString()+"px",
                "left":((window.innerWidth-modal_width)/2).toString()+"px",
                "top":((window.innerHeight-$(item).height())/2).toString()+"px"
                });
}
function modal_slideToggle(item){
   modal_center(item);
   $(item).slideToggle();
};
// kod wykonywany po załadowaniu całej strony
$(document).ready(function(){
   $(".modal").hide();
   $(".modal").find("form").prepend("<a href='' class='close_modal'>X</a>");
   $("a.close_modal").click( function(event){
        $(this).parents(".modal").hide();
        event.preventDefault();
   });
   $("nav a[href='#login']").click( function(event){
        modal_center("#login");
        $("nav a[href='#register']").show();
        $("nav a[href='#login']").hide();
        $("#register").hide();
        $("#login").slideDown();
        event.preventDefault();
   });
   $("nav a[href='#register']").click( function(event){
        modal_center("#register");
        $("nav a[href='#register']").hide();
        $("nav a[href='#login']").show();
        $("#login").hide();
        $("#register").slideDown();
        event.preventDefault();
   }).hide();
   $("#addtopic").click( function(event){
        modal_slideToggle("#modal_topic");
        event.preventDefault();
   });
   // zastosowanie pobierania danych z pomocą AJAX
   $("nav a.topicedit").click( function(event){
        // wstawia napis oraz numer tematu do nagłówka form.
        $("#modal_topic h2").html("Edycja tematu ID: <span topicid=\""+$(this).attr("topicid")+"\">"+$(this).attr("topicid")+"</span>");
        // pobiera dane z serwera metodą GET
        $.get("?cmd=gettopic&topicid="+$(this).attr("topicid"),
              // pobrane dane są przekazywane w data fo funkcji,
              // funkcja odpowiada za wykorzystanie pobranych danych
              // oczekiwane są dane w formacie JSON 
              function( data, status){
               if(status=="success"){
                    // tworzy obiekt topic z napisu o formacie JSON
                    var topic=JSON.parse(data);
                    // dane są umieszczane w polach form.
                    $("#modal_topic [name='topic']").val(topic.topic).focus(); 
                    $("#modal_topic [name='topic_body']").val(topic.topic_body);
                    $("#modal_topic [name='topicid']").val(topic.topicid);

               }else{
                    console.log("get connection error")
               }
        });
        modal_slideToggle("#modal_topic");
        event.preventDefault();
   });
// ------------------- do uzupełnienia ----------------------------------------
// kod obsługi dla: dodawania postów, edycji postów, dodawania obrazków,
// edycji podpisu pod obrazkiem, oraz obsługa odpowiednich 'przycisków'
//
//dodawanie postów
   $("#addpost").click(function(event){
     modal_center("#modal_post")
     $("#modal_post").show()
     //console.log("123")
        event.preventDefault()
   })
//edycja postów
   $(".postedit").click(function(event){
     $("#modal_post h2").html("Edycja tematu ID: <span postid=\""+$(this).attr("postid")+"\">"+$(this).attr("postid")+"</span>")
     $.get("?cmd=getpost&postid="+$(this).attr("postid"),
     function(data,status){
          if(status=="success"){
          var post= JSON.parse(data)
          $("#modal_post [name='post']").val(post.post)
          $("#modal_post [name='post_body']").val(post.post_body)
          $("#modal_post [name='postid']").val(post.postid)
          }else{
               console.log("get connection error")
          }
     })
     modal_slideToggle("#modal_post")
     event.preventDefault()
   })
//dodawanie obrazków
   $("nav a.uploadfile").click(function(event){
     $("#modal_file [name='postid']").val($(this).attr("postid"));
     //$("#modal_file h2").html("Edycja pliku ID:<span imgid=\""+$(this).attr("postid")+">"+$(this).attr("postid")+"</span>")
     
     modal_slideToggle("#modal_file");
     event.preventDefault()
   })
//edytowanie obrazków
$("a.imgedit").click( function(event){
     modal_slideToggle("#modal_fileedit");
    
     $.get("?cmd=getimg&imgid="+$(this).attr("imgid"),
           function( data, status){
          if(status=="success"){
               var topic=JSON.parse(data);
               $("#modal_fileedit [name='imagetitle']").val(topic.title).focus(); 
               $("#modal_fileedit [name='imgid']").val(topic.id);

          }else{
               console.log("get connection error")
          }
     });

     event.preventDefault();
});
   


//
// ------------------- do uzupełnienia ----------------------------------------
   
   $("article.topic").mouseenter(function(){
     $(this).find("footer").css("background-color", "#ccc");
   });
   $("article.topic").mouseleave(function(){
     $(this).find("footer").css("background-color", "#ddd");
   });
}); 