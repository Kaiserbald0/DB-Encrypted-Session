<?php

include("../../mySession.class.php");
include("../../mySession.conf.php");

//force the class to not overwrite php function
$_MYSESSION_CONF['OVERWRITE_PHP_FUNCTION']   =   0;
$session = mySession::getIstance($_MYSESSION_CONF);

class foo {

    public $word;

    function sayHello() {
        echo "Hello from ".$this->word."<br>";
    }

}

echo "All session vars: <pre>";
print_r($session->getVars());
echo "</pre>";

echo '<br> Changing a variables: cane="bar", $session->save("cane","bar"); <br>';
$session->save("cane","bar");
echo '<br> Registering a new variables: gatto="fufi", $session->save("gatto","fufi"); <br>';
$session->save("gatto","fufi");

echo '<br> Deleting a variable: $session->delete("SessionArray"); <br>';
$session->delete("SessionArray");

echo '<br> Using the class method from the session stored vars: $session->getVar("MyClass")->sayHello(); <br>';
$session->getVar("MyClass")->sayHello();

echo '<br>
<br>Only one session vars:<br>
Using the method: $session->getVar("cane") -> cane = '.$session->getVar("cane").'<br>';

echo '<br><br>Session_ID:'.$session->getSessionId().'<br>';

echo '<br><a href="./start.php">Back!</a>';
?>