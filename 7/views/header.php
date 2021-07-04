<html>
<head>
    <title>strona</title>
    <link rel="stylesheet" href="../style.css"\>
</head>
<nav>
    <?php if(isset($_SESSION['user'])){?>
        <a href="index.php?view=login">Log out</a>
    <?php }?>
    <?php if(isset($_SESSION['user'])&&$_SESSION['user']=="admin"){?>
        <a href="index.php?view=administration">administration</a>
    <?php }?>
    
</nav>