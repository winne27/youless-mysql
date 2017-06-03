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

    $include = __DIR__ . '/config.php';

    if (!is_file($include)) {
        echo 'Configuration file ' . $include . ' not found' . $newline;
        exit;
    }

    include $include;

    $values_tablename = $tablename_prefix . '_readings';
    $daily_tablename = $tablename_prefix . '_readings_daily';
    $devices_tablename = $tablename_prefix . '_devices';

    $connect = 'mysql:host=' . $host. ';port=' . $port . ';dbname=' . $dbname;
    $dbh = new PDO($connect, $dbuser, $password, array( PDO::ATTR_PERSISTENT => false));

    $sql = '
        CREATE TABLE ' . $devices_tablename. ' (
            dnr  TINYINT(4) NULL DEFAULT NULL,
            `desc` VARCHAR(20) NULL DEFAULT NULL,
            PRIMARY KEY (dnr)
        )
        ENGINE=InnoDB';
    $sth = $dbh->prepare($sql);
    $sth->execute();

    $sql = '
        CREATE TABLE ' . $values_tablename . ' (
            datetime DATETIME NOT NULL,
            dnr TINYINT(4) NOT NULL,
            power DECIMAL(5,3),
            total_energy DECIMAL(10,3),
            PRIMARY KEY (dnr, datetime)
        )
        ENGINE=InnoDB';
    $sth = $dbh->prepare($sql);
    $sth->execute();

    $sql = '
        CREATE TABLE ' . $daily_tablename . ' (
            date DATE NOT NULL,
            dnr TINYINT(4) NOT NULL,
            delta_energy DECIMAL(7,3),
            total_energy DECIMAL(10,3),
            PRIMARY KEY (date, dnr)
        )
        ENGINE=InnoDB';
    $sth = $dbh->prepare($sql);
    $sth->execute();

    foreach ($dnr as $index => $key) {
        $sql = 'insert into ' . $devices_tablename . ' values (:dnr, :desc)';
        $sth = $dbh->prepare($sql);
        $sth->bindValue(':dnr', $dnr[$index], PDO::PARAM_INT);
        $sth->bindValue(':desc', $name[$index], PDO::PARAM_STR);
        $sth->execute();
    }
