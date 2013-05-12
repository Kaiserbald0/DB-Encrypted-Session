<?php

#Section 1: DATABASE
$_MYSESSION_CONF['DATABASE_TYPE']   =   'mysql';            //PDO supported DBMS Types (for now only MySql supported)

#Section 1.1: Connection data
$_MYSESSION_CONF['DB_DATABASE']     =   'sql28591';                         //DB Name
$_MYSESSION_CONF['DB_PASSWORD']     =   'pL8!iY8!';                         //MySql password
$_MYSESSION_CONF['DB_SERVER']       =   'sql2.freesqldatabase.com';         //MySql server
$_MYSESSION_CONF['DB_USERNAME']     =   'sql28591';                         //MySql user

#Section 1.2: Table and columns name
$_MYSESSION_CONF['TB_NAME_SESSION'] =   'mySession_Sessions';       //Table name
$_MYSESSION_CONF['SID']             =   'sid';
$_MYSESSION_CONF['EXP']             =   'expires';
$_MYSESSION_CONF['FEXP']            =   'forced_expires';
$_MYSESSION_CONF['UA']              =   'ua';
$_MYSESSION_CONF['TB_NAME_VALUE']   =   'mySession_Vars';       //Table name
$_MYSESSION_CONF['NAME']            =   'name';
$_MYSESSION_CONF['VALUE']           =   'value';


#Section 2: SCRIPT CONFIGURATION
#Section 2.1: General configuration
$_MYSESSION_CONF['OVERWRITE_PHP_FUNCTION']   =   1;         //the class overwrite php session function
$_MYSESSION_CONF['SID_LEN']         =   32;                 //session_id chars length
$_MYSESSION_CONF['DURATION']        =   1800;               //Session duration (seconds) - Session will expires if no reload was made in this period
$_MYSESSION_CONF['MAX_DURATION']    =   3600;               //Max session duration (seconds) - Session will expires after this time interval - 0 if no forced expired needed
$_MYSESSION_CONF['SESSION_VAR_NAME']=   'MYPHPSESSID';      //Session variable name - You will use this name to propagate session (like PHPSESSID)

#Section 2.2: Cookie
$_MYSESSION_CONF['USE_COOKIE']      =   1;                  //Use cookie to propagate session. If yes you do not need to put the session vars in the URL or POST.
                                                            //1 to use cookie, 0 do not use cookie
#Section 2.3: Cripto
$_MYSESSION_CONF['CRIPT']           =   1;                  //Use AES cryptography to store session vars - 1 to encrypt data - 0 to use plain data
$_MYSESSION_CONF['CRIPT_KEY']       =   "god";              //Encrypt Key: The strongest key that your mind can think. You do not need to remember it!!!

#Section 2.4: Hijacking Prevention
$_MYSESSION_CONF['ENABLE_ANTI_HIJACKING'] = 1;              //Use UserAgent check to prevent Hijacking
$_MYSESSION_CONF['ANTI_HIJACKING_SALT']   = "antiHijack";   //The salt used to adding more security to the UserAgent check

