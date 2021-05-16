<section id="login">

    <form action="<?=$this->baseurl;?>" method="post">
     <a name="login_form"></a>
     <header><h2>Zaloguj się do forum</h2></header>  
     <input type="text" name="userid" placeholder="Nazwa logowania" pattern="[A-Za-z0-9\-]*" autofocus \><br />
     <input type="password" name="pass" placeholder="Hasło" \><br />
     <?="<div class=\"error\">$error1</div>";?>
     <button type="submit" >Zaloguj się</button>
  </form>
  <form action="<?=$this->baseurl;?>" method="post">
     <a name="newuser_form"></a>
     <header><h2>Jesli nie jesteś zarejestrowany, to możesz zapisać się do forum.</h2></header>  
     <input type="text" name="userid" placeholder="Nazwa logowania (dozwolone są tylko: litery, cyfry i znak '-')" pattern="[A-Za-z0-9\-]*" autofocus \><br />
     <input type="text" name="username" placeholder="Imię autora" \><br />
     <input type="password" name="pass1" placeholder="Hasło" \><br />
     <input type="password" name="pass2" placeholder="Powtórz hasło" \><br />
     <?="<div class=\"error\">$error2</div>";?>
     <button type="submit" >Zapisz się do forum</button>
  </form>
</section>    
