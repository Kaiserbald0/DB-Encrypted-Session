<?php

include("../../mySession.class.php");
include("../../mySession.conf.php");

//force the class to not overwrite php function
$_MYSESSION_CONF['OVERWRITE_PHP_FUNCTION']   =   0;
$session = mySession::getIstance($_MYSESSION_CONF);

echo $_SERVER['HTTP_USER_AGENT']."<br>";

//var_dump($session);
echo "Configuration: <pre>";
print_r($_MYSESSION_CONF);
echo "</pre>";



echo "Session id: <i>".$session->getSessionId()."</i><br>";
echo "Time (now): <i>".time()."</i><br>";
echo "Expires: <i>".(time()+1800)."</i><br>";

echo "All session vars: <pre>";
print_r($session->getVars());
echo "</pre>";


echo '<br> Registering a variable: <b>cane="foo"</b>, <i>$session->save("cane","foo");</i><br> ';
$session->save("cane","foo");

echo '<br> Registering an array: $session->register($myArray);<br> ';
$myArray["www"] = 9;
$myArray["ww"] = 10;

$session->register($myArray);
echo "<pre>";
print_r($myArray);
echo "</pre>";


echo '<br> Registering an array giving the name "SessionArray": <i>$session->save("SessionArray",$myArray);</i><br> ';
$session->save("SessionArray",$myArray);

echo '<br> Registering a class "barClass": <i>$_SESSION["barClass"]= $myClass;</i><br> ';

class foo {

    public $word;

    function sayHello() {
        echo "Hello from ".$this->word."<br>";
    }

}

$myClass = new foo;
$myClass->word = 'the hell';
//$myClass->sayHello();
var_dump($myClass);
$session->save("MyClass",$myClass);


echo "<br>All session vars: <pre>";
print_r($session->getVars());
echo "</pre>";

echo '<br>
<br>Only one session vars:<br>
Using the array: $session->VARS["cane"] -> cane = <i>'.$session->VARS["cane"].'</i><br>
Using the new method: $session->getVar("cane") -> cane = <i>'.$session->getVar("cane").'</i><br>
<br>
Using the array: $session->VARS["gatto"] -> gatto = <i>'.$session->VARS["gatto"].'</i><br>
Using the new method: $session->get_var("gatto") -> gatto = <i>'.$session->getVar("gatto").'</i>';




echo '<br><a href="./esempio2.php">Jump!</a>';
