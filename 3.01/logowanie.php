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
          Zadanie 3
      </h1>
      <h2>
          Proste forum
      </h2>
    </header>
    <nav>
            <a href="../">Home</a>
            <?php for($n=1;$n<=10;$n++) { if( is_dir("../zadanie".$n) ) { ?>
            <a href="../zadanie<?=$n?>">Zadanie <?=$n?></a>
            <?php } } ?>
    </nav>

  <form action="./index.php" method="post">
     <header><h2>Zaloguj się</h2></header>  
     <?php
     if(isset($wronglogin)){?>
        <h4 class="danger">zły login lub hasło</h4>
     <?php }
     ?>
     <input type="text" name="LOGIN" placeholder="login" autofocus required \><br />
     <input type="password" name="PASSWORD" placeholder="hasło" required \><br />
     
     <button type="submit" >Zaloguj się</button>
  </form>


  <form action="./index.php" method="post">
     <a name="topic_form"></a>
     <header><h2>Zarejestruj się</h2></header>
     <?php
     if(isset($badloginregister)){?>
        <h4 class="danger">login składa się z niedozwolonych znaków</h4>
     <?php }
     else if(isset($notequalpasswordregister)){?>
        <h4 class="danger">hasła nie są takie same</h4>
     <?php } else if(isset($userexistalreadyregister)){?>
        <h4 class="danger">użytkonik o danym loginie już istnieje</h4>
    <?php }?>
     
     
     
     <input type="text" name="NEWLOGIN" placeholder="login" required \><br />
     <input type="text" name="NEWAUTHOR" placeholder="autor" required \><br />
     <input type="password" name="NEWPASSWORD" placeholder="hasło" required \><br />
     <input type="password" name="NEWPASSWORD2" placeholder="powtórz hasło" required \><br />
     
     <button type="submit" >Zarejestruj się</button>
  </form>

<footer>

</footer>
</body>
</html>    