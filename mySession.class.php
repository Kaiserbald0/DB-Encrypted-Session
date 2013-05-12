<?php
/**
 *
 * My Session Class By Marco Baldini (kaiserbaldo@gmail.com)
 *
 * My session is a class that stores session data in a database rather
 * than files.
 *
 * This class has been created and released under the GNU GPL and is
 * free to use and redistribute only if this whole header comments and
 * copyright statement are not removed. Author gives no warranties. Use
 * at your own risk. Read the copyright, change log, howto and license.
 *
 * @author Marco Baldini
 *
 * @copyright 2013 Marco Baldini
 *
 * @license GNU General Public License, version 2 (GPLv2)
 *
 * @version MySession 2.1
 *
 * @example example/cookieBasedSessionUsingClassMethod/start.php
 *          <i>An example of how using this class and passing SessionId using cookie</i>
 *
 * @example example/cookieBasedSessionUsingOverWritingPHPFunction/start.php
 *          <i>An example of how using this class over writing the default php method</i>
 *
 */
class mySession
{
    /*
     * STATIC FIELD
     */
    
    /**
     * Store che class istance (according Singleton desing pattern)
     *
     * @var mySession
     */
    private static $instance;


    /*PUBLIC FIELD */

    /**
     * Store session variables
     *
     * @access public
     * @var array
     */
    public $VARS = array();

    /* PRIVATE FIELDS
     * All fields are private, accesible from outside only by get method
     */

    /**
     * Store the sessionId
     *
     * @access private
     * @var string
     */
    private $sessionId;

    /**
     * PDO connection
     *
     * @access private
     * @var resource connesione
     */
    private $connessione;

    /**
     * DBMS Types (only MySql supported)
     *
     * @access private
     * @var string
     */
    private $db_type = '';

    /**
     * Database Name
     *
     * @access private
     * @var string
     */

    private $db_name = '';

    /**
     * Database password
     *
     * @access private
     * @var string
     */

    private $db_pass = '';

    /**
     * Database Server
     *
     * @access private
     * @var string
     */
    private $db_server = '';

    /**
     * Database Username
     *
     * @access private
     * @var string
     */
    private $db_username = '';

    /**
     * Name of the database table where the sessions are stored
     *
     * @access private
     * @var string
     */
    private $table_name_session = '';

    /**
     * Name of the database table where the variables are stored
     *
     * @access private
     * @var string
     */
    private $table_name_variable = '';

    /**
     * Name of the table field that store session_ids
     *
     * @access private
     * @var string
     */
    private $table_column_sid = '';

    /**
     * Name of the table field that store session var names
     *
     * @access private
     * @var string
     */
    private $table_column_name = '';

    /**
     * Name of the table field that store session value names
     *
     * @access private
     * @var string
     */
    private $table_column_value = '';

    /**
     * Name of the table field that store session expire times
     *
     * @access private
     * @var string
     */
    private $table_column_exp = '';

    /**
     * Name of the table field that store session forced expire times
     *
     * @access private
     * @var string
     */
    private $table_column_fexp = '';

    /**
     * Name of the table field that store ua information
     *
     * @access private
     * @var string
     */
    private $table_column_ua = '';

    /**
     * Session variable name
     * You will use this name to propagate session (like PHPSESSID)
     *
     * @access private
     * @var string
     */
    private $sid_name = 'PHPSESSID';

    /**
     * Overwrite php session function, you can use default php function and array
     * like $_SESSION, and you do not need to change you actual script
     *
     * @access private
     * @var boolean
     */
    private $overwrite = true;

    /**
     * Session_id chars length
     * The length of the session id
     *
     * @access private
     * @var int
     */
    private $sid_len = 32;

    /**
     * Session duration in seconds
     * Session will expires if no reload was made in this period
     *
     * @access private
     * @var int
     */
    private $session_duration = 3600;

    /**
     * Max session duration in seconds
     * Session will expires after this time interval
     * Set to 0 o FALSE if no forced expired needed
     *
     * @access private
     * @var int
     */
    private $session_max_duration = 0;

    /**
     * Use cookie to propagate session.
     * If yes you do not need to put the session vars in the URL or POST.
     *
     * @access private
     * @var boolean
     */
    private $use_cookie = TRUE;
    /**
     * Use AES cryptography to store session vars in the database
     *
     * @access private
     * @var boolean
     */
    private $encrypt_data = FALSE;

    /**
     * Encrypt Key: The strongest that your mind can think.
     * You do not need to remember it!!!
     *
     * A valid password is at least 20 char long and contain
     * both alphanumeric and special chars.
     *
     * The class autoerase unfrendly chars, but is not a real problem for you
     *
     * @access private
     * @var string
     */
    private $encrypt_key = '';

    /**
     * Use hijack session blocker.
     * The class check for a change in the User Agent
     *
     * @var boolean
     * @access private
     */
    private $hijackBlock = TRUE;

    /**
     * Used in the hijack session blocker procedure.
     * This string is added to the User Agent giving more
     * security to the system
     *
     * @var string
     * @access private
     */
    private $hijackSalt = '';

    /**
     * Actual class version
     * @access private
     * @var string version
     */
    private $versione="2.0";

    /**
     * The SQL Statement used for retriving the number of SID's
     *
     * @uses $SQLStatement_CountSid->bindParam(':sid', $sid, PDO::PARAM_STR, $this->sid_len);
     * @var PDO Statement
     */
    private $SQLStatement_CountSid;

    /**
     * The SQL Statement used for retriving the number of SID's
     *
     * @uses $SQLStatement_InsertSession->bindParam(':sid', $sid, PDO::PARAM_STR, $this->sid_len);
     *       $SQLStatement_InsertSession->bindParam(':expires', $expires, PDO::PARAM_INT);
     *       $SQLStatement_InsertSession->bindParam(':forcedExpires', $forcedExpires, PDO::PARAM_INT);
     *
     * @var PDO Statement
     */
    private $SQLStatement_InsertSession;


    /**
     * The SQL Statement used for deleting a session (all session vars will be deleted)
     *
     * @uses $SQLStatement_DeleteSessionVars->bindParam(':sid', $sid, PDO::PARAM_STR, $this->sid_len);
     *
     * @var PDO Statement
     */
    private $SQLStatement_DeleteSession;

    /**
     * The SQL Statement used for deleting expired session (used by the garbage collector)
     *
     * @uses $SQLStatement_DeleteSessionVars->bindParam(':time', $time, PDO::PARAM_INT);
     *
     * @var PDO Statement
     */
    private $SQLStatement_DeleteExpiredSession;

    /**
     * The SQL Statement used for retriving the number of SID's
     *
     * @uses $SQLStatement_UpdateSessionExpires->bindParam(':sid', $sid, PDO::PARAM_STR, $this->sid_len);
     *       $SQLStatement_UpdateSessionExpires->bindParam(':expires', $expires, PDO::PARAM_INT);
     *
     * @var PDO Statement
     */
    private $SQLStatement_UpdateSessionExpires;

    /**
     * The SQL Statement used for retriving session info's
     *
     * @uses $SQLStatement_GetSessionInfos->bindParam(':sid', $sid, PDO::PARAM_STR, $this->sid_len);     
     *
     * @var PDO Statement
     */
    private $SQLStatement_GetSessionInfos;

    /**
     * The SQL Statement used for retriving session vars are not encrypted
     *
     * @uses $SQLStatement_GetSessionVars->bindParam(':sid', $sid, PDO::PARAM_STR, $this->sid_len);
     *
     * @var PDO Statement
     */
    private $SQLStatement_GetSessionVars;

    /**
     * The SQL Statement used for retriving session vars if the vars are encrypted
     *
     * @uses $SQLStatement_GetSessionVars->bindParam(':sid', $sid, PDO::PARAM_STR, $this->sid_len);
     *
     * @var PDO Statement
     */
    private $SQLStatement_GetEncryptedSessionVars;


    /**
     * The SQL Statement used for deleting a session vars if the vars are encrypted
     *
     * @uses $SQLStatement_DeleteSessionVars->bindParam(':sid', $sid, PDO::PARAM_STR, $this->sid_len);
     *       $SQLStatement_DeleteSessionVars->bindParam(':name', $name, PDO::PARAM_STR);
     *
     * @var PDO Statement
     */
    private $SQLStatement_DeleteSessionVars;

    /**
     * The SQL Statement used for deleting a session vars if the vars are encrypted
     *
     * @uses $SQLStatement_DeleteSessionVars->bindParam(':sid', $sid, PDO::PARAM_STR, $this->sid_len);
     *       $SQLStatement_DeleteSessionVars->bindParam(':name', $name, PDO::PARAM_STR);
     *
     * @var PDO Statement
     */
    private $SQLStatement_DeleteEncryptedSessionVars;

    /**
     * The SQL Statement used for insert a session vars if the vars are not encrypted
     *
     * @uses $SQLStatement_InsertSessionVars->bindParam(':sid', $sid, PDO::PARAM_STR, $this->sid_len);
     *       $SQLStatement_InsertSessionVars->bindParam(':name', $name, PDO::PARAM_STR);
     *       $SQLStatement_InsertSessionVars->bindParam(':value', $value, PDO::PARAM_STR);
     *
     * @var PDO Statement
     */
    private $SQLStatement_InsertSessionVars;

    /**
     * The SQL Statement used for insert a session vars if the vars are not encrypted
     *
     * @uses $SQLStatement_InsertEncryptedSessionVars->bindParam(':sid', $sid, PDO::PARAM_STR, $this->sid_len);
     *       $SQLStatement_InsertEncryptedSessionVars->bindParam(':name', $name, PDO::PARAM_STR);
     *       $SQLStatement_InsertEncryptedSessionVars->bindParam(':value', $value, PDO::PARAM_STR);
     *
     * @var PDO Statement
     */
    private $SQLStatement_InsertEncryptedSessionVars;

    /* PUBLIC METHOD */

    /**
     * Get Class version
     *
     * @return string Class Version
     */
    public function getVersion() {
            return $this->versione;
    }

    /**
     * Get a stored var
     *
     * @param string $name Variable name
     * @return object Store variable
     */
    public function getVar($name) {
            return $this->VARS[$name];
    }

    /**
     * Get all stored vars
     *
     * @return object All stored vars
     */
    public function getVars() {
            return $this->VARS;
    }

    /**
     * Get the session id
     *
     * @access public
     * @return string SessionId
     */
    public function getSessionId() {
        return $this->sessionId;
    }

    /**
     * According to singleton design patter
     * getIstance return an istance of the class
     * or create one istance
     *
     * @access public
     * @return mySession
     */
    public static function getIstance($_MYSESSION_CONF)
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
            self::$instance->setUp($_MYSESSION_CONF);
        }

        return self::$instance;
    }


    /**
     * Save a variable into the session
     *
     * @access public
     *
     * @param string $name The name of the session variable
     * @param string $value The value of the session variable.
     */
    public function save($name,$value) {                   
                    
                    $this->finalizeSaving($name, $value);
    }

    /**
     * Register a variable into the session
     *
     * @access public
     *
     * @param object $name The variable to save. This variable is saved into the
     *                     session array with the name of the saved variable
     *
     * @example $myVars = "fooo";<br>
     *          $this->register($myVars);<br>
     *          <br>
     *          The vars array will be: $this->VARS["myVars"] = "fooo";
     */
    public function register(&$name) {
                    
                    $this->finalizeSaving($this->varName($name), $name);

    }

    /**
     * Execute the real saving procedure, insert or update the session value
     *
     * @access private
     * @param string $finalName The name of the variable
     * @param string $finalValue The value of the saved variable
     */
    private function finalizeSaving($finalName,$finalValue) {

                    $finalValue = serialize($finalValue);

                    $this->del($finalName);
                    $this->insert($finalName,$finalValue);

                    $this->loadSesionVars();

    }

    /**
     * Delete a variable from the session
     *
     * @access public
     *
     * @param string $name Variable name
     */
    public function delete($name) {

                    $this->del($name);
                    $this->loadSesionVars();
    }


    /* PRIVATE METHOD */


    /**
     * Check if session must expire due to "MAX_DURATA"
     *
     * @access private

     * @return boolean: true if session must expire | false if session can continue
     */
    private function expiredSession() {

            if (time()>$this->forcedExpire) return true;
                    else return false;
    }

    /**
     * Load session vars to the VARS array
     *
     * @access private
     */
    private function loadSesionVars() {

            $this->VARS = array();           

            $this->updateSessionExpireTime();

            $dati = $this->selectSessionVars();

            foreach($dati as $infos) {            
                    $this->VARS[$infos["name"]]=unserialize($infos["value"]);
            }

    }



    /**
     * Read actual sessionId or create a new one
     *
     * @access private
     */
    private function readSessionId() {
        
            if ($this->use_cookie==true) { //cookie enabled
        
                    if (isset($_COOKIE[$this->sid_name])) { //there some jam in the cookie

                            $this->sessionId=$_COOKIE[$this->sid_name];
                            //check if the jam can be eated
                            if ($this->checkSessionId()) {


                                $num = $this->getSidCount($this->sessionId);
                                if ($num != 1) {
                                    //there is a sessiod in the cookie and no sessid in the DB
                                    //the only thing to do is to generate a new Sid
                                    if (!$this->newSid()) {
                                            trigger_error("Unable to load session.",E_USER_ERROR);
                                        } else {
                                            if (!$this->overwrite) setcookie ($this->sid_name, $this->sessionId,time()+$this->session_duration,"/",'',false,true);
                                        }

                                } else {
                                    //ok the jam is good!
                                    if (!$this->overwrite) {
                                        setcookie ($this->sid_name, $this->sessionId,time()+$this->session_duration,"/",'',false,true);                                        
                                    }
                                    $this->loadSesionVars();
                                }
                                
                            } else {
                                //bad bad bad think ... something goes wrong with the
                                //jam .. maybe uncle tom eated it before ..                                
                                $this->destroySession(FALSE);
                                trigger_error("Unable to load session.",E_USER_ERROR);
                            }

                    } else { 

                            //Damn, no id ... i create some jam!
                                    
                                        if (!$this->newSid()) {
                                            trigger_error("Unable to load session.",E_USER_ERROR);
                                        } else {
                                            if (!$this->overwrite) setcookie ($this->sid_name, $this->sessionId,time()+$this->session_duration,"/",'',false,true);
                                        }
                            

                    }

            } else { //no cookie allowed.. bad thing! search elsewhere

                            if (isset($_REQUEST[$this->sid_name])) {//bingo!

                                    $this->sessionId = $_REQUEST[$this->sid_name];
                                    if ($this->checkSessionId()) {
                                        $this->loadSesionVars();
                                    }else {
                                        //bad bad bad think ... something goes wrong with the
                                        //jam .. maybe uncle tom eated it before ..
                                        $this->destroySession(FALSE);
                                        trigger_error("Unable to load session.",E_USER_ERROR);
                                    }

                            } else { // create a new SID

                                    if (!$this->newSid()) die("Unable to save session");

                            }

            }
        }

    

    /**
     * Check if the session id found is able ti be used
     *
     * @return boolean True if the session Id is ok, False if not
     */
    private function checkSessionId() {

        $hijackTest = FALSE;
       
        if ($this->hijackBlock) {
            $this->SQLStatement_GetSessionInfos->bindParam(':sid', $this->sessionId, PDO::PARAM_STR, $this->sid_len);
            $this->SQLStatement_GetSessionInfos->execute();
            $val = $this->SQLStatement_GetSessionInfos->fetchAll(PDO::FETCH_ASSOC);
            //var_dump($val);
            //echo "<br> UA:".$this->getUa()."<br>";
            if ($val[0]["ua"] ==$this->getUa()) {
                $hijackTest = TRUE;
            } else {
                $hijackTest = FALSE;
            }
        } else {
            $hijackTest = TRUE;
        }

        if ($hijackTest==TRUE) return true;
            else return false;
        
    }

    /**
     * Generate a new unique session id
     *
     * @access private
     * @return bool True if session insert, false elsewhere
     */
    private function newSid() {

            $this->sessionId=$this->generateString($this->sid_len);
            
            while ( $this->getSidCount($this->sessionId) > 0 || is_int($this->sessionId) ) {

                    $this->sessionId=$this->generateString($this->sid_len);

            }

            $this->forcedExpire = time()+ $this->session_max_duration;
            $expireTime = time() + $this->session_duration;

            $this->SQLStatement_InsertSession->bindParam(':expires', $expireTime, PDO::PARAM_INT);
            $this->SQLStatement_InsertSession->bindParam(':forcedExpires', $this->forcedExpire, PDO::PARAM_INT);
            $this->SQLStatement_InsertSession->bindParam(':sid', $this->sessionId, PDO::PARAM_STR, $this->sid_len);
            $this->SQLStatement_InsertSession->bindParam(':ua', $this->getUa(), PDO::PARAM_STR, 40);

            return $this->SQLStatement_InsertSession->execute();

    }

    /**
     * Generate user agent string based on
     * User Agent and Salt.
     * The return string is the SHA1 hash.
     * 
     * @return string
     */
    private function getUa() {
        return sha1($_SERVER['HTTP_USER_AGENT'].$this->hijackSalt);
    }


    /**
     * Generate a string of "$length" random chars
     *
     * @access private
     * @param number $length
     * @return string
     */
    private function generateString($length)
    {

            $alphabet="qazxswedcvfrtgbnhyujmklpoi0987654321";
            $ris='';

            for ($i=0; $i < $length; $i++) {
                    srand($this->makeSeed());
                    $ris .= $alphabet[rand(0,(strlen($alphabet)-1))];
            }

            return($ris);
    }

    /**
     * Setting up random seed random generator
     *
     * @access private
     * @return float
     */
    private function makeSeed()
    {
       list($usec, $sec) = explode(' ', microtime());
       return (float) $sec + ((float) $usec * 100000);
    }


    /**
     * Retrive the name of a variable, giving the varaible as argument
     *
     * @access private
     * @param mixed type $var The variable
     * @param boolean $scope The Scope
     * @return string The variable name
     *
     * @example $this->varName($mySuperVariable);<br>
     *          return: "mySuperVariable"
     */
    private function varName(&$var, $scope=0)
    {
        $old = $var;
        if (($key = array_search($var = 'unique'.rand().'value', !$scope ? $GLOBALS : $scope)) && $var = $old) return $key;
    }

    /**
     * Create a database connection
     *
     * @access private
     */
    private function dbConnection() {
        //if (!is_resource($this->connessione))
        //    $this->connessione = mysql_connect($this->db_server,$this->db_username,$this->db_pass) or die("Error connectin to the DBMS: " . mysql_error());
        if (!is_resource($this->connessione))
            try {
                    $this->connessione = new PDO($this->db_type.":dbname=".$this->db_name.";host=".$this->db_server, $this->db_username, $this->db_pass );
                    //echo "PDO connection object created";
                    $this->setupSQLStatement();
                }
            catch(PDOException $e)
                {
                    echo $e->getMessage();
                    die();
                }


    }

    /**
     * Destroy session
     * Delete variable from database and free resources
     *
     * @access private
     * @param boolean $sql True if delete both resource and database rows. False to keep database rows
     * @return boolen true if all is ok, false elsewhere
     */
    private function destroySession($sql) {

            $check = true;

            if ($sql) {
                $this->SQLStatement_DeleteSession->bindParam('sid', $this->sessionId, PDO::PARAM_STR, $this->sid_len);
                if ($this->SQLStatement_DeleteSession->execute()===FALSE) $check = FALSE;
            }

            if ($this->use_cookie==true) {
                if (setcookie ($this->sid_name, $this->sessionId,time()-3600,"/",'',false,true) === FALSE)  $check = FALSE;
            }

            unset ($_REQUEST[$this->sid_name]);
            unset ($_POST[$this->sid_name]);
            unset ($_GET[$this->sid_name]);

            return $check;
    }

    /**
     * Setting up the class, reading
     * an existing session, check if a session
     * is expired.
     *
     * @access private
     * @param Array $config Config Array
     */
    private function setUp($config) {
        //print_r($config);
        //$this->_MYSESSION_CONF=$config;

        $this->db_type = $config["DATABASE_TYPE"];
        $this->db_name = $config["DB_DATABASE"];
        $this->db_pass = $config["DB_PASSWORD"];
        $this->db_server = $config["DB_SERVER"];
        $this->db_username = $config["DB_USERNAME"];

        $this->table_name_session = $config["TB_NAME_SESSION"];
        $this->table_name_variable = $config["TB_NAME_VALUE"];
        $this->table_column_sid = $config["SID"];
        $this->table_column_name = $config["NAME"];
        $this->table_column_value = $config["VALUE"];
        $this->table_column_fexp = $config["FEXP"];
        $this->table_column_ua = $config["UA"];
        $this->table_column_exp = $config["EXP"];

        $this->sid_name = $config["SESSION_VAR_NAME"];
        $this->overwrite = ($config["OVERWRITE_PHP_FUNCTION"]=='1')?true:false;
        $this->sid_len = intval($config["SID_LEN"]);
        $this->session_duration = intval($config["DURATION"]);
        $this->session_max_duration = intval($config["MAX_DURATION"]);
        $this->use_cookie = ($config["USE_COOKIE"]=='1')?true:false;
        $this->encrypt_data = ($config["CRIPT"]=='1')?true:false;
        $this->encrypt_key = $config["CRIPT_KEY"];

        $this->hijackBlock = ($config["ENABLE_ANTI_HIJACKING"]=='1')?true:false;
        $this->hijackSalt = $config["ANTI_HIJACKING_SALT"];

        $this->dbConnection();
        $this->readSessionId();
        //check if i have to overwrite php
        if ($this->overwrite) {
            //yes.. i'm the best so i overwrite php function
            //Make sure session cookies expire when we want it to expires
            ini_set('session.cookie_lifetime', $this->session_duration);            
            //set the value of the garbage collector
            ini_set('session.gc_maxlifetime', $this->session_max_duration);
            // set the session name to our fantastic name
            ini_set('session.name', $this->sid_name);                        

            // register the new handler
            session_set_save_handler(
                array(&$this, 'open'),
                array(&$this, 'close'),
                array(&$this, 'read'),
                array(&$this, 'write'),
                array(&$this, 'destroy'),
                array(&$this, 'gc')
            );


            // start the session and cross finger
            session_id($this->getSessionId());
            session_start();

        }        

        
        //echo "<hr>".$this->sessionId."<hr>";
        //if ($this->expireSession()) $this->destroy();
        //$_REQUEST[$this->_MYSESSION_CONF['SESSION_VAR_NAME']]=$this->sessionId;

    }

//--------------------OVERWRITED FUNCTION

    /**
     *  Our open() function
     *
     *  @access private
     */
    function open($save_path, $session_name)
    {        
        return true;

    }

    /**
     *  Our close() function
     *
     *  @access private
     */
    function close()
    {

        return true;

    }

    /**
     *  Our read() function
     *
     *  @access private
     */
    function read($session_id)
    {        
        return (string) $this->serializePhpSession($this->getVars());
    }

    /**
     *  Our write() function
     *
     *  @access private
     */
    function write($session_id, $session_data)
    {       
        
        $myData = $this->unserializePhpSession($session_data);
        foreach ($myData as $name => $value) {
            $this->save($name, $value);
        }
        return true;
    }

    /**
     * Helper function that serialize an object to a string
     * in the Php session format
     * 
     * @param mixed object $data Session data (or any object)
     * @return string serialied object
     * @access private
     */
    private function serializePhpSession($data) {

        $serialized = '';

        foreach ($this->getVars() as $key => $value) {
            $serialized .= $key . "|" . serialize($value);
        }

        return (string) $serialized;
    }

    /**
     * Helper function that unserialize PHP Session Data string
     *
     * @param string $data Sessione serialized data
     * @return object
     * @access private
     */
    private function unserializePhpSession( $data )
    {
        if(  strlen( $data) == 0)
        {
            return array();
        }

        // match all the session keys and offsets
        preg_match_all('/(^|;|\})([a-zA-Z0-9_]+)\|/i', $data, $matchesarray, PREG_OFFSET_CAPTURE);

        $returnArray = array();

        $lastOffset = null;
        $currentKey = '';
        foreach ( $matchesarray[2] as $value )
        {
            $offset = $value[1];
            if(!is_null( $lastOffset))
            {
                $valueText = substr($data, $lastOffset, $offset - $lastOffset );
                $returnArray[$currentKey] = unserialize($valueText);
            }
            $currentKey = $value[0];

            $lastOffset = $offset + strlen( $currentKey )+1;
        }

        $valueText = substr($data, $lastOffset );
        $returnArray[$currentKey] = unserialize($valueText);

        return $returnArray;
    }

    /**
     *  Our destroy() function
     *
     *  @access private
     */
    function destroy($session_id)
    {        
        return $this->destroySession(true);
    }

    /**
     *  Our gc() function (garbage collector)
     *
     *  @access private
     */
    function gc($maxlifetime)
    {
        $this->SQLStatement_DeleteExpiredSession->bindParam('time', time() - $this->session_max_duration, PDO::PARAM_INT);
        if ($this->SQLStatement_DeleteExpiredSession->execute()===FALSE) {
            trigger_error("Somenthing goes wrong with the garbace collector", E_USER_ERROR);
        } else {
            return true;
        }
    }

//--------------------SQL FUNCTION

    /**
     * Create the SQL Statement for all the query
     * needed by the class
     *
     * @access private
     */
    private function setupSQLStatement() {

        $tabella_sessioni = $this->db_name.".".$this->table_name_session;
        $tabella_variabili = $this->db_name.".".$this->table_name_variable;
        /*** SQL statement: count SID ***/
        $this->SQLStatement_CountSid = $this->connessione->prepare("SELECT count(*) FROM ".$tabella_sessioni." WHERE ".$this->table_column_sid." = :sid");

        /*** SQL statement: Insert Session ***/
        $this->SQLStatement_InsertSession = $this->connessione->prepare("INSERT INTO ".$tabella_sessioni."(".$this->table_column_sid.",".$this->table_column_exp.",".$this->table_column_fexp.",".$this->table_column_ua.") VALUES (:sid,:expires,:forcedExpires,:ua)");

        /*** SQL statement: Update Session Expires ***/
        $this->SQLStatement_UpdateSessionExpires = $this->connessione->prepare("UPDATE ".$tabella_sessioni." SET ".$this->table_column_exp." = :expires WHERE ".$this->table_column_sid." = :sid");

        /*** SQL statement: Get Session Infos ***/
        $this->SQLStatement_GetSessionInfos = $this->connessione->prepare("SELECT * FROM ".$tabella_sessioni." WHERE ".$this->table_column_sid." = :sid");

        /*** SQL statement: Get Session Vars ***/
        $this->SQLStatement_GetSessionVars = $this->connessione->prepare("SELECT ".$this->table_column_value." as value, ".$this->table_column_name." as name FROM ".$tabella_variabili." WHERE ".$this->table_column_sid." = :sid");
        
        /*** SQL statement: Get Encrypted Session Vars ***/
        $this->SQLStatement_GetEncryptedSessionVars = $this->connessione->prepare("SELECT AES_DECRYPT(".$this->table_column_value.",'".$this->encrypt_key."') as value,AES_DECRYPT(".$this->table_column_name.",'".$this->encrypt_key."') as name FROM ".$tabella_variabili." WHERE ".$this->table_column_sid." = :sid");

        /*** SQL statement: Delete Session Vars ***/
        $this->SQLStatement_DeleteSessionVars = $this->connessione->prepare("DELETE FROM ".$tabella_variabili."  WHERE ".$this->table_column_sid." = :sid AND ".$this->table_column_name."= :name ");

        /*** SQL statement: Delete Encrypted Session Vars ***/
        $this->SQLStatement_DeleteEncryptedSessionVars = $this->connessione->prepare("DELETE FROM ".$tabella_variabili."  WHERE ".$this->table_column_sid." = :sid AND ".$this->table_column_name."= AES_ENCRYPT(:name,'".$this->encrypt_key."') ");

        /*** SQL statement: Insert Session Vars ***/
        $this->SQLStatement_InsertSessionVars = $this->connessione->prepare("INSERT INTO ".$tabella_variabili."(".$this->table_column_sid.",".$this->table_column_name.",".$this->table_column_value.") VALUE(:sid,:name,:value)");

        /*** SQL statement: Insert Encrypted Session Vars ***/
        $this->SQLStatement_InsertEncryptedSessionVars = $this->connessione->prepare("INSERT INTO ".$tabella_variabili."(".$this->table_column_sid.",".$this->table_column_name.",".$this->table_column_value.") VALUE(:sid,AES_ENCRYPT(:name,'".$this->encrypt_key."'),AES_ENCRYPT(:value,'".$this->encrypt_key."'))");

        /*** SQL statement: Delete Session ***/
        $this->SQLStatement_DeleteSession = $this->connessione->prepare("DELETE FROM ".$tabella_sessioni."  WHERE ".$this->table_column_sid." = :sid ");

        /*** SQL statement: Delete Expired Session ***/
        $this->SQLStatement_DeleteExpiredSession = $this->connessione->prepare("DELETE FROM ".$tabella_sessioni."  WHERE ".$this->table_column_fexp." < :time ");

    }

    /**
     * Count how many "$sid" are in the Session_Vars table
     *
     * @access private
     * @param string $sid: sid searched for
     * @return int: The number of records
     */
    private function getSidCount($sid) {
        
        
        $this->SQLStatement_CountSid->bindParam(':sid', $sid, PDO::PARAM_STR, $this->sid_len);
        $this->SQLStatement_CountSid->execute();        

        $val=$this->SQLStatement_CountSid->fetchColumn();
        
        return $val;

    }

    /**
     * Select all session vars
     *
     * @access private
     * @return array Fetched Vars
     */
    private function selectSessionVars() {

       //prelevo le variabili e le metto nell'array VARS
        if ($this->encrypt_data==1) {
                $this->SQLStatement_GetEncryptedSessionVars->bindParam('sid', $this->sessionId, PDO::PARAM_STR, $this->sid_len);
                $this->SQLStatement_GetEncryptedSessionVars->execute();
                $val = $this->SQLStatement_GetEncryptedSessionVars->fetchAll(PDO::FETCH_ASSOC);

        } else {
                $this->SQLStatement_GetSessionVars->bindParam('sid', $this->sessionId, PDO::PARAM_STR, $this->sid_len);
                $this->SQLStatement_GetSessionVars->execute();
                $val = $this->SQLStatement_GetSessionVars->fetchAll(PDO::FETCH_ASSOC);
        }
        //var_dump($val);
        return $val;

    }

    /**
     * Update current session expires
     *
     * @access private     
     * @return boolean - True if the query succesfully done. False in any other case
     */
    private function updateSessionExpireTime() {
        
            $newExprireTime = time()+$this->session_duration;
            $this->SQLStatement_UpdateSessionExpires->bindParam(':expires', $newExprireTime, PDO::PARAM_INT);
            $this->SQLStatement_UpdateSessionExpires->bindParam(':sid', $this->sessionId, PDO::PARAM_STR, $this->sid_len);
            return $this->SQLStatement_UpdateSessionExpires->execute();
            

    }

    /**
     * Send a delete query to the DBMS
     *
     * Query will be created according to this prototype:
     * DELETE FROM $db.$table WHERE $cond
     *
     * @access private
     * @param string $name: name of the variable to delete from session
     * @return boolean - True if the query succesfully done. False in any other case
     */
    private function del($name) {

        if ($this->encrypt_data==1) {
             $this->SQLStatement_DeleteEncryptedSessionVars->bindParam('sid', $this->sessionId, PDO::PARAM_STR, $this->sid_len);
             $this->SQLStatement_DeleteEncryptedSessionVars->bindParam('name', $name, PDO::PARAM_STR);
             $result = $this->SQLStatement_DeleteEncryptedSessionVars->execute();
        } else {
             $this->SQLStatement_DeleteSessionVars->bindParam('sid', $this->sessionId, PDO::PARAM_STR, $this->sid_len);
             $this->SQLStatement_DeleteSessionVars->bindParam('name', $name, PDO::PARAM_STR);
             $result = $this->SQLStatement_DeleteSessionVars->execute();
        }

        return $result;

    }

    /**
     * Send an insert query to the DBMS
     *
     * Query will be created according to this prototype:
     * INSERT INTO $db.$tabelle SET $name = $value
     *
     * @access private
     * @param string $name: variable name
     * @param string $val: variable value
     * @return boolean - True if the query succesfully done. False in any other case
     */
    private function insert($name,$val) {

       if ($this->encrypt_data==1) {

             $this->SQLStatement_InsertEncryptedSessionVars->bindParam('sid', $this->sessionId, PDO::PARAM_STR, $this->sid_len);
             $this->SQLStatement_InsertEncryptedSessionVars->bindParam('name', $name, PDO::PARAM_STR);
             $this->SQLStatement_InsertEncryptedSessionVars->bindParam('value', $val, PDO::PARAM_STR);
             $result = $this->SQLStatement_InsertEncryptedSessionVars->execute();

       } else {

             $this->SQLStatement_InsertSessionVars->bindParam('sid', $this->sessionId, PDO::PARAM_STR, $this->sid_len);
             $this->SQLStatement_InsertSessionVars->bindParam('name', $name, PDO::PARAM_STR);
             $this->SQLStatement_InsertSessionVars->bindParam('value', $val, PDO::PARAM_STR);
             $result = $this->SQLStatement_InsertSessionVars->execute();
 
       }

        return $result;

    }

    /**
     * According Singelton design pattern the
     * constructor is privare
     *
     */
    private function __construct() {

    }

    /**
     * Accorting to Singleton desing pattern cloning not allowed
     */
    private function  __clone() {
        trigger_error("Clonig not allowed");
    }
}
