<?php

include("../../mySession.class.php");
include("./mySession.conf.php");

class foo {

    public $word;

    function sayHello() {
        echo "Hello from ".$this->word."<br>";
    }

}



$session = mySession::getIstance($_MYSESSION_CONF);

echo "Session id: <i>".  session_id()."</i><br>";

echo "All session vars: <pre>";
print_r($_SESSION);
echo "</pre>";

$_SESSION["barClass"]->sayHello();


echo '<br> Registering a new vars "nuSessionVars": <i>$_SESSION["nuSessionVars"]= \'GO\';</i><br> ';
$_SESSION["nuSessionVars"]= 'GO';

echo '<br><a href="./start.php">Back!</a>';
echo '<br><a href="./start.php?new">Back with a new session</a>';
?>