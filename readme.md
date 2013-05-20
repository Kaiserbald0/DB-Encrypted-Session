mySession ReadMe   
================================

Read this file before use mySession class.

Where to find:
 * main class file: mySession.class.php
 * configuration file: mySession.conf.php
 * Sql database structure: mysql.sql
 * Change Log: at the end of this document
 * How to: howto.htm
 * License:  gpl-license.html
 * Example: in the "example/" directory

C9 Workspace: https://c9.io/kaiserbaldo/db-encrypted-session

The lastest version is: MySession 2.1

Before using mysessionClass check the md5sum.

88e16a572ac89e1ebc4336f0a390587b  mySession.class.php
7d40e2a2e21b07da2c047231c10c0461  mysql.sql
2c71248345e58b49596461b1d07e3c68  mySession.conf.php

If some file do not pass the md5sum test, ask for the
file writing an email to: kaiserbaldo[]gmail.com

Thanks for reading


My Session: Change Log 
================================

Version: 2.1 
Release Date: 12/05/2013
Release Note: C9 Space
Change Log:
* New: Created a public workspace @C9 so anyone can collaborate: http://c9.io/kaiserbaldo/db-encrypted-session
* Fixed: Fixed several documentation issue and some naming issue



Version: 2.0 
Release Date: 11/11/2011
Release Note: Bug Fixing
Change Log:
* Fixed: Undefined index on line 847 (posted by twwy) 

Version: 2.0 Beta3
Release Date: 14/01/2011
Release Note: Overwrite PHP Function
Change Log:
* You can now choose to overwrite php function. You can now use mySession without any change in your script

Version: 2.0 Beta2
Release Date: 05/01/2011
Release Note: Improved security 
Change Log:
* Added optional UserAgent control to prevent "Session Hijacking"
* If a sessionId found in the cookie or in the request is not saved in the database an E_USER_ERROR error was triggered.

Version: 2.0 Beta1
Release Date: 29/12/2010
Release Note: Rewriting code. 
Change Log:
* Cleaned the code
* Added Singleton design pattern
* Added severals comments wrote according phpDoc style
* Added severals new method
* Changed database structure
* Switched to PDO
* If a sessionId found in the cookie or in the request is not saved in the database an E_USER_WARNING error was triggered and the session will be destroyed

Version: 1.1
Release Date: 03/09/2004
Release Note: Added Features. 
Change Log:
* Cleaned the code
* Added PHP5/PHP4 full compatibility 
* Added a new method to retrive variables value ("get_var") 

Version: 1.0.0RC1
Release Date: 20/08/2004
Release Note: First Pubblic Release 
Change Log: - 
