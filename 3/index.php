<?php
session_start();
include("./funkcje.php");
$path = explode('/',dirname(__FILE__));
$dirname = array_pop($path);
$username = array_pop($path);
$path = implode('/',$path);
$tasks = array();

foreach(scandir( "$path/$username" ) as $dir)
    if( is_dir("$path/$username/$dir") and $dir!='.' and $dir!='..')
        $tasks[] = $dir;

//logowanie
if(isset($_POST['LOGIN'])&&isset($_POST['PASSWORD'])){
    
    if(isUser($_POST['LOGIN'])){
        $currentUser=getUserInfo($_POST['LOGIN']);
        if(md5($_POST['PASSWORD'])==$currentUser[2]){
            $_SESSION['LOGIN']=$_POST['LOGIN'];

        }
        else{
            $wronglogin=true;
        }
    }
    else{
        $wronglogin=true;
    }
}
//podtrzymanie zmienych w czasie sesji
if(isset($_SESSION['LOGIN'])){
    $currentUser=getUserInfo($_SESSION['LOGIN']);
}


if(isset($_GET['cmd'])){//komendy
    if($_GET['cmd']=="logout"){
        unset($_SESSION['LOGIN']);
        echo "<script>window.location = 'index.php'</script>";

    }

}
//rejestracja
if(isset($_POST['NEWLOGIN'])&&isset($_POST['NEWAUTHOR'])&&isset($_POST['NEWPASSWORD'])&&isset($_POST['NEWPASSWORD2'])){
    if($_POST['NEWPASSWORD'] == $_POST['NEWPASSWORD2']){
        if(!isUser($_POST['NEWLOGIN'])){
            addUser($_POST['NEWLOGIN'], $_POST['NEWAUTHOR'], $_POST['NEWPASSWORD']);

        }
        else{
            $userAlreadyExist=true;
        }

    }
}
$users=readFileDetails("./users.txt");
//dodawanie nowego postu
if(isset($_POST['POSTTITLE'])&&isset($_POST['POSTDETAIL'])){
    addNewPost($_POST['POSTTITLE'], $_POST['POSTDETAIL'], $_SESSION['LOGIN']);
}

?>
<html>
<head>
  <title>TWWW - User: <?=$username?>, <?=$dirname?></title>
  <meta charset="UTF-8">
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" >
  <meta http-equiv="Pragma" content="no-cache" >
  <link rel="stylesheet" type="text/css" href="../style.css"/>
</head>

<body>
<header>
    <h1>
        <?=$dirname?>
    </h1>
</header>
<nav>
    <a href="<?="/$username"?>" title="Strona początkowa serwisu" target="_self" class="navigation-link">
        Strona początkowa serwisu
    </a> 
<?php 
foreach($tasks as $task){ 
?>

    <a href="<?="/$username/$task"?>/" title="<?=$task?>" target="_self" class="navigation-link">
        <?=$task?>
    </a> 

<?php } ?>
</nav>
<?php if($_SESSION!=[]){?>
<nav class="logout">
    Zalogowano jako <?=$currentUser[0]?>
    <a href="./index.php?cmd=logout" class="navigation-link">
        Wyloguj
    </a>
</nav>


<?php }?>


<article>
    <?php if($_SESSION==[]){?>
        <form action="./index.php" method="post">
            <h3>
                Zaloguj się
            </h3>
            <?php
                if(isset($_POST['LOGIN'])&&isset($_POST['PASSWORD'])){
                    if(isset($wronglogin)){?>
                        <h4 class="error">
                            niepoprawny login lub hasło
                        </h4>
                    <?php }
                }
            ?>
            <input type="text" placeholder="login" name="LOGIN" required>
            <input type="password" placeholder="hasło" name="PASSWORD" required>
            <input type="submit" value="zaloguj się">
        </form>

        <form action="./index.php" method="post">
            <h3>
                Zarejestruj się
            </h3>
            <?php
            if(isset($_POST['NEWLOGIN'])&&isset($_POST['NEWAUTHOR'])&&isset($_POST['NEWPASSWORD'])&&isset($_POST['NEWPASSWORD2'])){
                if($_POST['NEWPASSWORD'] != $_POST['NEWPASSWORD2']){?>
                    <h4 class="error">
                        Hasła są takie same
                    </h4>
                <?php }
                if(isset($userAlreadyExist)){
                    if($userAlreadyExist==true){?>
                        <h4 class="error">
                            Użytkownik już istnieje
                        </h4>

                    <?php }
                }
            }
            ?>
            <input type="text" placeholder="login" name="NEWLOGIN" required>
            <input type="text" placeholder="autor" name="NEWAUTHOR" required>
           
            <input type="password" placeholder="hasło" name="NEWPASSWORD" required>
            <input type="password" placeholder="powtórz hasło" name="NEWPASSWORD2" required>
            <input type="submit" value="zarejestruj się">
        </form>


    <?php } else {
        if(isset($_GET['topic'])){//wypowiedzi
            $topic=getTopicOfOpinions($_GET['topic']);
        ?>
            <section id="topTopic">
                <div>
                    Temat dyskusji: <?=$topic[1]?>
                </div>
                <div>
                    ID: <?=$topic[3]?>, Autor: <?=$topic[2]?>, Data: <?=$topic[4]?>
                </div>


            </section>


            <section>
                
            </section>


            <form action="./index.php?topic=<?=$_GET['topic']?>" method="post">
                <textarea type="text" placeholder="temat" name="OPINION"></textarea>
                <input type="submit" value="dodaj">
            </form>
            
        <?php } else{//posty
            ?>
            <section>
                <?php
                $posts=readFileDetails("./posty.txt");
                foreach($posts as $post){?>
                    <article class="post">
                        <a href="./index.php?topic=<?=$post[3]?>" class="navigation-link">
                            <div class="postTitle">
                                <?=$post[0]?>
                            </div>
                        </a>
                        <div>
                            ID: <?=$post[3]?>, Autor: <?=$post[2]?>, Utworzono: <?=$post[4]?>, liczba wpisów: <?=5?>
                        </div>
                        
                    </article>
                <?php }
                
                ?>
            </section>


            <form action="./index.php" method="post">
                <input type="text" placeholder="nowy temat" name="POSTTITLE">
                <textarea placeholder="opis tematu" name="POSTDETAIL"></textarea>
                <input type="submit" value="dodaj nowy post">
            </form>

        <?php }




        ?>

    <?php }?>


</article>

<?php 
echo json_encode($currentUser);
echo json_encode($_SESSION);

?>



<footer>
    <p>
        Stopka dokumentu: TWWW - IR - WIiT PP - <?=date("Y")?>
    </p>
</footer>
</body>
</html>