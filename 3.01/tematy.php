<!DOCTYPE html>
<html>
<head>
    <title>Demo - Zadanie 1 - WWW i jzyki skryptowe</title>
    <meta charset="utf-8">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
      <h1>
          Demo - Zadanie 2
      </h1>
      <h2>
          Proste forum
      </h2>
    </header>
    <?php 
    if($currentUser[3]=='admin'){?>
    <nav>
      <a href="./index.php?cmd=showusers">
        użytkownicy
      </a>
    </nav> 
    <?php }
    ?>
    <nav>
            <a href="../">Home</a>
            <?php for($n=1;$n<=10;$n++) { if( is_dir("../zadanie".$n) ) { ?>
            <a href="../zadanie<?=$n?>">Zadanie <?=$n?></a>
            <?php } } ?>
            <div>
              <p id="logout">
                zalogowano jako <?=$currentUser[0]?>(<?=$currentUser[1]?>)
               <a href="./index.php?cmd=logout">
                 Wyloguj się
               </a>
              </p>
            </div>
    </nav>
    <?php
    if(isset($_GET['cmd'])&&$_GET['cmd']=='showusers'){
      $users=readFileDetails($users_file, ",", "\n");
      //foreach($users as $user)echo "<div>".json_encode($user)."</div>";
      ?>
      <table>
          <tr>
            <th>
              Login
            </th>
            <th>
              Autor
            </th>
            <th>
              Poziom
            </th>
            <th>
              
            </th>
            
          </tr>

      <?php foreach($users as $user){?>
          <tr>
            <td>
              <?=$user[0]?>
            </td>
            <td>
              <?=$user[1]?>
            </td>
            <td>
              <?=$user[3]?>
            </td>
            <td>
              <?php if($user[0]!='admin'){?>
              <a href="./index.php?cmd=showusers&changeuserlevel=<?=$user[0]?>">
                Zmień
              </a>
              <a href="./index.php?cmd=showusers&deleteuser=<?=$user[0]?>" class="danger">
                Usuń
              </a>
              <?php }?>
            </td>
            
          </tr>
          <?php }
      ?>
      </table>
      
    <?php }
    ?>
<section>
<?php if( !$topics ){ ?>
  <p>To forum nie zawiera jeszcze żadnych tematów!</p>
<?php }else{ ?>
  <p>Możesz dodac nowy temart za pomocą <a href="#topic_form">formularza</a>.</p>
<?php foreach($topics as $k=>$v){ ?>
  <article class="topic">
    <header> </header>
    <div><a href="?topic=<?=$k?>"><?=htmlentities($v['topic'])?></a></div>
    <?php
    if($currentUser[3]=='admin'){?>
      <nav>
        <a href="?topictoedit=<?=$v['topicid']?>&cmd=edit">EDYTUJ</a>  
        <a class="danger" href="?topictodelete=<?=$v['topicid']?>&cmd=delete">KASUJ</a>
      </nav> 
    <?php }
    
    ?>
    <footer>ID: <?=$v['topicid']?>, Autor: <?=htmlentities($v['username'])?>, 
        Utworzono: <?=$v['date']?>, Liczba wpisów: <?=isset($posts_count[$v['topicid']])?$posts_count[$v['topicid']]:0;?>
    </footer>
  </article>
<?php } } ?>
  <?php
  if(!isset($_GET['cmd'])||$_GET['cmd']!='edit'){
  ?>
  <form action="index.php" method="post">
     <a name="topic_form"></a>
     <header><h2>Dodaj nowy temat do dyskusji</h2></header>  
     <input required type="text" name="topic" placeholder="Nowy temat" autofocus \><br />
     <textarea required name="topic_body" cols="80" rows="10" placeholder="Opis nowego tematu" ></textarea><br />
     <button type="submit" >Zapisz</button>
  </form>
    <?php }else{
      $posts=get_topics($topic_file);
      
      foreach($posts as $post){
        if($post['topicid']==$_GET['topictoedit']){
          $toEdit=$post;
          break;
        }
      }
      //echo json_encode($toEdit);
      ?>
      <form action="index.php?topictoedit=<?=$_GET['topictoedit']?>&cmd=edit&execute=true" method="post">
     <a name="topic_form"></a>
     <header><h2>Edytuj temat</h2></header>  
     <input required type="edittext" name="topic" placeholder="Edytuj temat" autofocus  value=<?=$toEdit['topic']?> \><br />
     <textarea required name="edittopic_body" cols="80" rows="10" placeholder="Opis tematu" ><?=$toEdit['topic_body']?></textarea><br />
     <button type="submit" >Zapisz</button>
    <?php }?>



</section>

<footer>
Ostatni wpis na formu powstał dnia: <?=get_last_post_date($posts_file, $separator);?>
</footer>
</body>
</html>    