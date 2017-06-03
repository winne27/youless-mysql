<?php

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
