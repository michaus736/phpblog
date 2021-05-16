<?php
include("./maincontainer.php");
session_start();
setcookie("visitorcookie", uniqid());
$container = new Container();
$container->createView($container->addViewData());
?>