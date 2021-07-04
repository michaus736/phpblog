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
$(document).ready(function()
{
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
               // tworzy obiekt topic z napisu o formacie JSON
               var topic=JSON.parse(data);
               // dane są umieszczane w polach form.
               $("#modal_topic [name='topic']").val(topic.topic).focus(); 
               $("#modal_topic [name='topic_body']").val(topic.topic_body);
               $("#modal_topic [name='topicid']").val(topic.topicid);
        });
        modal_slideToggle("#modal_topic");
        event.preventDefault();
   });
// ------------------- do uzupełnienia ----------------------------------------
// kod obsługi dla: dodawania postów, edycji postów, dodawania obrazków,
// edycji podpisu pod obrazkiem, oraz obsługa odpowiednich 'przycisków'
//

//dodawania postów DZIAŁA
     $("#addpost").click( function(event)
     {
          modal_slideToggle("#modal_post");
          event.preventDefault();
     });

//edycja postów DZIAŁA
     $("nav a.postedit").click( function(event){
          // wstawia napis oraz numer tematu do nagłówka form.
          $("#modal_post h2").html("Edycja postu ID: <span topicid=\""+$(this).attr("postid")+"\">"+$(this).attr("postid")+"</span>");
          // pobiera dane z serwera metodą GET
          $.get("?cmd=getpost&postid="+$(this).attr("postid"),
                // pobrane dane są przekazywane w data fo funkcji,
                // funkcja odpowiada za wykorzystanie pobranych danych
                // oczekiwane są dane w formacie JSON 
                function( data, status){
                 // tworzy obiekt topic z napisu o formacie JSON
                 var topic=JSON.parse(data);
                 // dane są umieszczane w polach form.
                 $("#modal_post [name='post']").val(topic.post).focus(); 
                 $("#modal_post [name='postid']").val(topic.postid);
          });
          modal_slideToggle("#modal_post");
          event.preventDefault();
     });

//dodawanie obrazków DZIAŁA
//poprawić błędy podczas wysyłania bez opisu

     $("nav a.uploadfile").click( function(event)
     {
          $("#modal_file [name='postid']").val($(this).attr("postid"));
          $("#modal_file h2").html("Edycja postu ID: <span topicid=\""+$(this).attr("postid")+"\">"+$(this).attr("postid")+"</span>");
          modal_slideToggle("#modal_file");
          event.preventDefault();
     });

//edytowanie opisu obrazka DZIAŁA

$("a.imgedit").click( function(event){
     modal_slideToggle("#modal_fileedit");
    
     // pobiera dane z serwera metodą GET
     $.get("?cmd=getimg&imgid="+$(this).attr("imgid"),
           // pobrane dane są przekazywane w data fo funkcji,
           // funkcja odpowiada za wykorzystanie pobranych danych
           // oczekiwane są dane w formacie JSON 
           function( data, status){
            // tworzy obiekt topic z napisu o formacie JSON
            var topic=JSON.parse(data);
            // dane są umieszczane w polach form.
            $("#modal_fileedit [name='imagetitle']").val(topic.title).focus(); 
            $("#modal_fileedit [name='imgid']").val(topic.id);
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