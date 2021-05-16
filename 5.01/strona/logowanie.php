<!DOCTYPE html>
<html>
<head>
    <title>Zadanie 3 - WWW i jzyki skryptowe</title>
    <meta charset="utf-8">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="styl.css">
</head>
<body>
    <header id="glowny">
		<h1 id="naglowek_glowny">Zadanie 4 Bartosz Lisiecki - WWW i Języki skryptowe</h1>
		<h2 id="blog">Blog</h2>
	</header>
    <nav>
            <a href="../">Home</a>
            <?php for($n=1;$n<=10;$n++) { if( is_dir("../../zadanie".$n) ) { ?>
            <a href="../zadanie<?=$n?>">Zadanie <?=$n?></a>
            <?php } } 
			?>
			 
    </nav>
	
<section id="login">
    <form class='formularz' action="index.php" method="post">
     <a style='visibility: hidden'name="loginform"></a>
     <h2>Zaloguj się do forum</h2> 
     <input class='form'type="text" name="userid" placeholder="Nazwa logowania" pattern="[A-Za-z0-9\-]*" ><br />
     <input class='form'type="password" name="pass" placeholder="Hasło" \><br /> 
     <button class='guzik'type="submit" >Zaloguj się</button>
  </form>
  <form class='formularz'style="margin-bottom:30px" action="index.php" method="post">
     <a style='visibility: hidden' name="newuserform"></a>
     <h2>Zarejestruj się do forum</h2>
     <input class='form'type="text" name="userid" placeholder="Nazwa logowania (dozwolone są tylko: litery, cyfry i znak '-')" pattern="[A-Za-z0-9\-]*" autofocus \><br />
     <input class='form'type="text" name="username" placeholder="Imię autora" ><br />
     <input class='form'type="password" name="pass1" placeholder="Hasło" ><br />
     <input class='form'type="password" name="pass2" placeholder="Powtórz hasło" ><br />
    
     <button class='guzik'type="submit" >Zapisz się do forum</button>
  </form>
</section>  
<footer style="clear:both;">
		<p style="text-align:center;">WWW i Języki Skryptowe - Poznań 2021  &copy Bartosz Lisiecki teleinformatyka grupa i3.2</p>
</footer> 
</body>
</htmls>


