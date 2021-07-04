<?php
$path = explode('/', dirname(__FILE__));
$dirname = array_pop($path);
$username = array_pop($path);
$path = implode('/', $path);
$tasks = array();
$filepath="posts.txt";



//write to file
if (isset($_POST['title']) && isset($_POST['content']))
{
    $title = $_POST['title'];
    $content = $_POST['content'];
    //echo $title.' '.$content;
    if (!empty($title) && !empty($content))
    {
        //$postfile = fopen("posts.txt", 'a');
        //fwrite($postfile, $title . ' ' . $content . ' ' . date("d.m.Y, H:i:s") . ' ' . "127.0.0.1" . "\n");
        //fclose($postfile);
        file_put_contents($filepath, nl2br(bin2hex($title).";" . bin2hex($content) . ";" . bin2hex(date("d.m.Y, H:i:s")) . ";" . bin2hex("127.0.0.1"))."\n", FILE_APPEND|LOCK_EX);
    }
}

//echo json_encode($_POST);

//get posts
$rfile = fopen($filepath, "r");
$posts = [];

while($post=fgets($rfile)){
  //var_dump(hex2bin(trim($post)));
  array_push($posts,
    array_map("hex2bin",
     explode(";",
     trim($post))));
}



foreach (scandir("$path/$username") as $dir) 
    if (is_dir("$path/$username/$dir") and $dir != '.' and $dir != '..') 
        $tasks[] = $dir;


?>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>TWWW - User: <?=$username ?>, <?=$dirname ?></title>

  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" >
  <meta http-equiv="Pragma" content="no-cache" >
  <link rel="stylesheet" type="text/css" href="../style.css"/>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
  <header id="top-header">
    <h1 class="top" id="top-path">
        <?=$dirname ?>
      </h1>
      <h1 class="top" id="top-blog-name">
          Blog
      </h1>
  </header>
  <nav id="navigation">
    <a href="<?="/$username" ?>" title="Strona początkowa serwisu" target="_self" class="navigation-link">
        Strona początkowa serwisu
      </a> 

    <?php foreach ($tasks as $task)
{ ?>
        <a href="<?="/$username/$task" ?>/" title="<?=$task ?>" target="_self" class="navigation-link"><?=$task ?></a> 
    <?php
} ?>

  </nav>
  <section id="posts">
      <?php if($posts!=[]){for ($i = count($posts) - 1;$i >= 0;$i--)
{ ?>
        <article class="post">
          <header>
              <p>
                Temat
              </p>
              <p>
                <?=htmlspecialchars($posts[$i][0]) ?>
              </p>
          </header>    
          <div>
                <p>
                Tresc
              </p>
              <p>
                <?=(nl2br(htmlspecialchars($posts[$i][1])))?>
              </p>
          </div>
          <footer class="post-footer">
              <p>
                 Data 
                <?=htmlspecialchars($posts[$i][2])?>
              </p>
              <p>
                 Adres IP 
                <?=htmlspecialchars($posts[$i][3]) ?>
              </p>
          </footer>
        </article>
      <?php
}} ?>

  </section>
  
<aside>
      <?php
    if($posts==[]){ ?>
      <div>
          <p>
              Brak postow
          </p>
      </div>
    <?php }
    else{?>
      <div>
      <p id="last-post">
          Data ostatniego postu: <?=$posts[count($posts)-1][2]. ' ' . $posts[count($posts)-1][3]?>
      </p>
    </div>    
    <div>
      <p id="post-count">
          Liczba postow: <?=count($posts)?>
      </p>
    </div>  
    <?php } ?>


</aside>
<section id="section-form">
  <form action="index.php" method="POST">
      <div>
        <h2>
          Enter New Post!
        </h2>
      </div>
      <div class="form-input-text">
        <input type="text" placeholder="title" name="title" class="input-text"/>
      </div>
      <div class="form-input-textarea">
        <textarea placeholder="content" name="content" class="input-textarea"></textarea>
      </div>
      <div class="form-input-submit">
        <input type="submit" value="send" class="input-submit"/>
      </div>

</form>
</section>
  <footer id="footer-page" class="footer-bottom">
      <p>
          MR - User: <?=$username ?>
      </p>
  </footer>

  <footer id="footer-document" class="footer-bottom">
    <p>
        TWWW - IR - WIiT PP - <?=date("d.m.Y, H:i:s") ?>
      </p>
  </footer>
  <?php
  foreach($posts as $post)
      echo "<div>".json_encode($post)."</div>";
  
  echo "<br>";  
  echo nl2br($posts[3][1]);
  ?>
</body>

</html>
