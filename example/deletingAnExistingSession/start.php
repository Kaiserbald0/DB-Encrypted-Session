<?
include("../../mySession.class.php");
include("./mySession.conf.php");

if ($_SERVER["argv"][0] == 'new') {
    //if there is "new" in the agrv i need to delete the session
    $session = mySession::getIstance($_MYSESSION_CONF);
    //i delete session
    session_destroy();
    //reload the page
    header("Location:start.php");
} else {
    $session = mySession::getIstance($_MYSESSION_CONF);
}

echo $_SERVER['HTTP_USER_AGENT']."<br>";

//var_dump($session);
echo "Configuration: <pre>";
print_r($_MYSESSION_CONF);
echo "</pre>";

echo '$_SESSION["nuSessionVars"]:'.$_SESSION["nuSessionVars"]."<br><br>";


echo "Session id: <i>".  session_id()."</i><br>";
echo "Time (now): <i>".time()."</i><br>";
echo "Expires: <i>".(time()+1800)."</i><br>";

echo "All session vars: <pre>";
print_r($_SESSION);
echo "</pre>";


echo '<br> Registering a variable: <b>cane="foo"</b>, <i>$_SESSION["cane"]= "foo";</i><br> ';
$_SESSION["cane"]= "foo";

$myArray["www"] = 9;
$myArray["ww"] = 10;

echo "<pre>";
print_r($myArray);
echo "</pre>";


echo '<br> Registering an array giving the name "fooArray": <i>$_SESSION["fooArray"]= $myArray;;</i><br> ';
$_SESSION["fooArray"]= $myArray;

echo '<br> Registering a class "barClass": <i>$_SESSION["barClass"]= $myClass;</i><br> ';

class foo {

    public $word;

    function sayHello() {
        echo "Hello from ".$this->word."<br>";
    }

}

$myClass = new foo;
$myClass->word = 'the limbo';
//$myClass->sayHello();
var_dump($myClass);

$_SESSION["barClass"]= $myClass;
$_SESSION["barClass"]->sayHello();



echo "<br>All session vars: <pre>";
print_r($_SESSION);
echo "</pre>";

echo '<br>
<br>Only one session vars:<br>
Using the array: $session->VARS["cane"] -> cane = <i>'.$session->VARS["cane"].'</i><br>
Using the new method: $session->getVar("cane") -> cane = <i>'.$session->getVar("cane").'</i><br>
<br>
Using the array: $session->VARS["gatto"] -> gatto = <i>'.$session->VARS["gatto"].'</i><br>
Using the new method: $session->get_var("gatto") -> gatto = <i>'.$session->getVar("gatto").'</i>';




echo '<br><a href="./esempio2.php">Jump to the other page!</a><br>';

