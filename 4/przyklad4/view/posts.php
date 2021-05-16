<section>
  <nav>
  <table><tr>
  <td style="width: 33.3%;"></td>
  <td  style="width: 33.3%;">
    <a href="<?=$this->baseurl?>?cmd=topics">Lista tematów</a>
  </td>
  <td  style="width: 33.3%;"></td>
  </tr></table>
  </nav>

  <article  class="topic">
    <header>Temat dyskusji: <b><?=htmlentities($topic['topic'])?></b></header>
    <div><?=nl2br(htmlentities($topic['topic_body']))?></div>
    <footer>
    ID: <?=$topic['topicid']?>, Autor: <?=htmlentities($users[$topic['userid']]['username'])?>, Data: <?=$topic['date']?>
    </footer>
  </article>
<?php if( !$posts ){ ?>
  <p>To forum nie zawiera jeszcze żadnych głosów w dyskusji!</p>
<?php }else{ 
  $photos=$this->photos->getAll();
  $topicid=$_SESSION['topicid'];
    if($photos!=false){
      $photosbyid=array();
      foreach($photos as $photo){
        if($photo['topicid']==$topicid)
          array_push($photosbyid, $photo);
    }
  }

  foreach($posts as $k=>$v){ 
?>
  <article class="post">
  <div><?=nl2br(htmlentities($v['post']))?></div>
  <?php if($photos!=false) { ?>
  <div class="photos">
    <?php foreach($photosbyid as $photo){ 
      if($photo['postid']==$v['postid']){
        ?>
      <div class="photo">
      <h3><?=$photo['detail']?></h3>
      <img src="<?=$photo['newname']?>" style="width: 50%;">
      <?php
      if($this->u['userlevel']==10 or $this->u['userid']==$v['userid']){ ?>

        <nav>
          <a href="?cmdphoto=edit&photoid=<?=$photo['photoid']?>">EDYTUJ</a>  
          <a class="danger" href="?execute=true&cmdphoto=delete&photoid=<?=$photo['photoid']?>">KASUJ</a>
        </nav> 

      <?php }
      ?>
      </div>
    <?php } 
    }
    
    ?>
  </div>
  <?php } ?>
  <footer>
  <?php if( $this->u['userlevel']==10 or $this->u['userid']==$v['userid']){ 
    if(isset($_GET['cmdphoto'])&&$_GET['cmdphoto']=='edit'){
      
  ?>
    <form action="<?=$this->baseurl?>" method="GET">
      <input type="hidden" name="photoid" value="<?=$_GET['photoid']?>">
      <input type="hidden" name="cmdphoto" value="edit">
      <input type="hidden" name="execute" value="true">
      <input type="text" name="newdetail" value="">
      <input type="submit" value="edytuj informacje nt. fotografii">
    </form>
    <?php } else { 
    ?>
  
    <form action="<?=$this->baseurl?>" method="POST" enctype="multipart/form-data">
      <input type="file" name="newphoto" required>
      <input type="hidden" name="postId" value="<?=$v['postid']?>" required>
      <input type="text" name="detail" required>
      <input type="submit" value="prześlij zdjęcie">
    </form>
  <?php } 
  ?>
  <nav>
    <a href="?id=<?=$v['postid']?>&cmd=edit">EDYTUJ</a>  
    <a class="danger" href="?&id=<?=$v['postid']?>&cmd=delete">KASUJ</a>
  </nav> 
  <?php } ?>
  ID: <?=$v['postid']?>, Autor: <?=htmlentities($users[$v['userid']]['username'])?>, Utworzono dnia: <?=$v['date']?></footer>
  </article>
<?php } } ?>

  <form action="<?=$this->baseurl?>" method="post" enctype="multipart/form-data">
     <a name="post_form" ></a>
     <header><h2><?php if($post){ ?>Edytuj wypowiedź<?php }else{ ?>Dodaj nowa wypowiedź do dyskusji<?php } ?></h2></header>  
     <textarea name="post" autofocus cols="80" rows="10" placeholder="Wpisz tu swoją wypowiedź." ><?=($post)?$post["post"]:'';?></textarea><br />
     <input type="hidden" name="postId" value="<?=($post)?$post["postid"]:"";?>" />
     <button type="submit" >Zapisz</button>
  </form>

</section>
<?php
//echo json_encode($topics);
//echo json_encode($photos);

?>