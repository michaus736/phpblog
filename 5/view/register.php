<section id="login">
<nav><a id='guziczek'href="?register=false">Logowanie</a></nav>
    <?php 
       if( !isset($error1) ) $error1 = "";
       if( !isset($error2) ) $error2 = ""; 
    ?>
    
  <form action="<?=$this->baseurl;?>" method="post">
     <a name="newuser_form"></a>
     <header><h2>Jesli nie jesteś zarejestrowany, to możesz zapisać się do forum.</h2></header>  
     <input type="text" name="userid" placeholder="Nazwa logowania (dozwolone są tylko: litery, cyfry i znak '-')" pattern="[A-Za-z0-9\-]*" autofocus \><br />
     <input type="text" name="username" placeholder="Imię autora" \><br />
     <input type="password" name="pass1" placeholder="Hasło" \><br />
     <input type="password" name="pass2" placeholder="Powtórz hasło" \><br />
	 <div>
      <img src="<?=$photoimg?>" onclick="location.href='?register=true&reloadimg=<?=$photoimg?>'"	> <br/>
	  </div>
     <input type="text" name="captcha" placeholder="wpisz kod z obrazka" \><br />
     <input type="hidden" name="captcha2" value="<?=implode('', $imgkod)?>">
     <?="<div class=\"error\">$error2</div>";?>
     <button type="submit" >Zapisz się do forum</button>
  </form>
</section> 
<?php
echo json_encode($photoimg);
echo json_encode($test12321);
?>