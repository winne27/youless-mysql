<?php
/*
           DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
                    Version 2, December 2004

 Copyright (C) 2004 Sam Hocevar <sam@hocevar.net>

 Everyone is permitted to copy and distribute verbatim or modified
 copies of this license document, and changing it is allowed as long
 as the name is changed.

            DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
   TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION

  0. You just DO WHAT THE FUCK YOU WANT TO.
*/

// your serial number of device for this config - allows multiple devices in one database
$dnr[0] = 0;
// name of device
$name[0] = 'MyYouLessMonitor';
// URL of YouLess device
$url[0] = 'http://192.168.0.14';

// for further devices copy this lines above and change index from 0 to i (1,2,3, ...)  and change $dnr[i] (normally to i)

// connection data to MySql database
$host = 'localhost';
$port = '3601'; // 3601 is default port for MySql and MariaDb databases
$dbname = 'youless';
$dbuser = 'youless';
$password = 'yourpassword';

// tablenames in database
$tablename_prefix = 'youless';
