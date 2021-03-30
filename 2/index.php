<?php
include("./dane.php");
$path = explode('/', dirname(__FILE__));
$dirname = array_pop($path);
$username = array_pop($path);
$path = implode('/', $path);
$tasks = array();
$topics=getTopics("./tematy.txt");
$posts=GetPosts("./wypowiedzi.txt");
if(isset($_GET['topic'])){
    $topicid=$_GET['topic'];
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
</head>
<body>
  <header id="top-header">
    <h1 class="top" id="top-path">
        <?=$dirname ?>
      </h1>
      <h1 class="top" id="top-blog-name">
          Forum
      </h1>
  </header>
  <nav id="navigation">
   
        <?php 
        if(!isset($_GET['topic'])){?>
            <a href="<?="/$username" ?>" title="Strona początkowa serwisu" target="_self"  class="navigation-link">
            Strona początkowa serwisu
          </a> 
            <?php foreach ($tasks as $task){?>

                <a href="<?="/$username/$task" ?>/" title="<?=$task ?>" target="_self"  class="navigation-link"><?=$task ?></a>


        <?php 
            }
        } 
        else{?>
            <?php
            if($topicid>1){
            ?>
            <div><a class="navigation-link" href="./index.php?topic=<?=$topicid-1?>"><h3>poprzedni temat</h3></a></div>
            <?php } ?>
            <div><a class="navigation-link" href="./index.php"><h3>strona glowna</h3></a></div>
            <?php
            if($topicid<count($topics)){
            ?>
            <div><a class="navigation-link" href="./index.php?topic=<?=$topicid+1?>"><h3>nastepny temat</h3></a></div>
            <?php } ?>

        <?php
        }
        ?>

    

  </nav>
  <?php //echo json_encode($posts) ?>
  <article>
    <?php 
            if(!isset($_GET['topic'])){
                
                if(!empty($topics)){
                    foreach($topics as $topic){
                ?>
                <section>
                    <div><a class="navigation-link" href="./?topic=<?=$topic[4]?>"><?=$topic[0]?></a></div>
                    <div></div>
                    <div>
                    id=<?=$topic[4]?>, autor: <?=$topic[1]?>
                    data utworzenia: <?=$topic[3]?>
                    , ilosc wpisow: <?=countPostsById($topic[4],$posts)?>
                    </div>
                </section>


            <?php 
                
            }}} 
            else{?>
            <? echo json_encode($topics);?>
                <section>
                    <div>temat:<?=$topics[$topicid-1][0]?></div>
                    <div>wdrożenie do tematu:<?=$topics[$topicid-1][2]?></div>
                    <div>autor:<?=$topics[$topicid-1][1]?></div>
                </section>
                <?php 
                if(isset($posts[$topicid-1])){
                    foreach($posts as $post){
                        if($post[0]==$topicid){?>
                            <section>
                                <div><?=$post[2]?></div>
                                <div>
                                    <a href="./index.php?topic=<?=$topicid?>&id=<?=$post[3]?>&cmd=edit"><h3>Edit</h3></a>
                                    <a href="./index.php?topic=<?=$topicid?>&id=<?=$post[3]?>&cmd=del"><h3>Delete</h3></a>
                                </div>
                                <div>
                                    Id: <?=$post[3]?>
                                    , autor: <?=$post[1]?>
                                    , utworzono dnia: <?=$post[4]?>
                                </div>
                            </section>

                        <?php
                        }
                    }
                }
                else{
                    echo "brak postow, dodaj nowy post za pomoca formularza.";
                }
                 ?>


            <?php
            }
            ?>



  </article>
  <article>
        <?php 
            if(!isset($_GET['topic'])){?>
                <form action="./index.php" method="POST">
                    <h2>Dodaj nowy temat</h2>
                    <input type="text" placeholder="nowy temat" name="subject"/>
                    <textarea  placeholder="zawartosc" name="detail"></textarea>
                    <input type="text" placeholder="autor" name="author"/>
                    <input type="submit" value="dodaj"/>



                </form>


            <?php 
                
            } 
            else{?>

                <?php
                if(!isset($_GET['cmd'])){                ?>
                    <form action="./index.php?topic=<?=$topicid?>" method="POST">
                    <?php }
                    else{
                    ?>
                    <form action="./index.php?topic=<?=$topicid?>&id=<?=$post[3]?>&cmd=edit" method="POST">
                    <?php } ?>



                
                <?php
                if(!isset($_GET['cmd'])){                ?>
                    <h2>Dodaj nową wypowiedź</h2>
                    <?php }
                    else{
                    ?>
                    <h2>Edytuj wypowiedź</h2>
                    <?php } ?>


                    <?php if(!isset($_GET['cmd'])){                ?>
                    <textarea placeholder="wypowiedz" name="post"></textarea>
                    <?php }
                    else{
                    ?>
                    <textarea placeholder="wypowiedz" name="postedit"><?=$posts[$_GET['id']-1][2]?></textarea>
                    <?php } ?>


                    <?php if(!isset($_GET['cmd'])){                ?>
                        <input type="text" placeholder="autor" name="author2"/>
                    <?php }
                    else{
                    ?>
                    <input type="text" placeholder="autor" name="author2edit" value="<?=$posts[$_GET['id']-1][1]?>"/>
                    <?php } ?>


                    <?php if(!isset($_GET['cmd'])){                ?>
                        <input type="submit" value="dodaj"/>
                    <?php }
                    else{
                    ?>
                    <input type="submit" value="edytuj"/>
                    <?php } ?>

                    
                    
                    
                </form>



            <?php
            }
            ?>
    
            

  </article>
  <footer id="footer-page" class="footer-bottom">
      <p>
          Data ostatniego postu: 
          <?php 
            if(count($posts)==0){
                echo "brak postow";
            }
            else{
                echo $posts[count($posts)-1][4];
            }
          ?>
      </p>
  </footer>

</body>

    <?php 
            if(!isset($_GET['topic'])){?>
                <?php //echo json_encode($_GET);?>
                <?php
                    if(isset($_POST['subject'])&&isset($_POST['detail'])&&isset($_POST['author'])){
                        $subject=$_POST['subject'];
                        $detail=$_POST['detail'];
                        $author=$_POST['author'];
                        //echo "123";
                        if(!empty($subject)&&!empty($detail)&&!empty($author)){
                            $topicfile=fopen("tematy.txt",'a');
                            fwrite($topicfile, nl2br($subject
                            .':-:'. $author
                            .':-:'.htmlspecialchars($detail)
                            .':-:'.date("d.m.Y, H:i:s")). '-:-:-' );
                            fclose($topicfile);
                        }


                    }
                ?>

            <?php 
                
            } 
            else{?>
                <?php //echo json_encode($_GET);?>
                <?php
                    if(isset($_POST['post'])&&isset($_POST['author2'])){
                        $post=$_POST['post'];
                        $author2=$_POST['author2'];
                        //echo "123";
                        if(!empty($post)&&!empty($author2)){
                            $postfile=fopen("./wypowiedzi.txt",'a');
                            fwrite($postfile, nl2br(
                                $topicid
                            .":-:".$author2
                            .":-:".$post
                            .":-:".(count($posts)+1)
                            .":-:".date("d.m.Y, H:i:s")
                            ).'-:-:-' );
                            fclose($postfile);
                        }


                    }
                ?>


            <?php
            }
            ?>


<?php
if(isset($_GET['cmd'])){
    $cmd=$_GET['cmd'];
    if($cmd=="del"){
        $newcontent="";
        $idtodel=$_GET['id'];
        echo $idtodel;
        $index=1;
        foreach($posts as $post){
            //echo json_encode($post[3]);
            if($post[3]!=$idtodel){
                $newcontent.=nl2br($post[0].":-:".$post[1].":-:".$post[2].":-:".$index.":-:".$post[4]."-:-:-");
                $index++;
            }
            
        }
        //echo $newcontent;
        file_put_contents("./wypowiedzi.txt",$newcontent);
        //echo file_get_contents("./wypowiedzi.txt");
        //header("Location: index.php?topic=$topicid");
        echo "<script>window.location = 'index.php?topic=$topicid'</script>";
    }
    if($cmd="edit"){
        if(isset($_POST['author2edit'])&&isset($_POST['postedit'])){
            $authoredit=$_POST['author2edit'];
            $postedit=$_POST['postedit'];
            if(!empty($authoredit)&&!empty($postedit)){
                $idtoedit=$_GET['id'];
                $newcontent="";
                $index=1;
                foreach($posts as $post){
                    //echo json_encode($post[3]);
                    if($post[3]!=$idtoedit){
                        $newcontent.=nl2br($post[0].":-:".$post[1].":-:".$post[2].":-:".$index.":-:".$post[4]."-:-:-");
                        
                    }
                    else{
                        $newcontent.=nl2br($topicid.":-:".$authoredit.":-:".$postedit.":-:".$index.":-:".date("d.m.Y, H:i:s")."-:-:-");
                    }
                    $index++;
                }
                //echo $newcontent;
                file_put_contents("./wypowiedzi.txt",$newcontent);
                //header("Location: index.php?topic=$topicid");
                echo "<script>window.location = 'index.php?topic=$topicid'</script>";
            }


        }
    }
}

?>
</html>
