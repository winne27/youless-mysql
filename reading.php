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

    $suffix = '';

    if(isset($argv)) {
        $newline = "\n";
    } else {
        $newline = '<br>';
    }

    $include = __DIR__ . '/config.php';

    if (!is_file($include)) {
        echo 'Configuration file ' . $include . ' not found' . $newline;
        exit;
    }

    include $include;

    $values_tablename = $tablename_prefix . '_readings';
    $daily_tablename = $tablename_prefix . '_readings_daily';

    $connect = 'mysql:host=' . $host. ';port=' . $port . ';dbname=' . $dbname;
    $dbh = new PDO($connect, $dbuser, $password, array( PDO::ATTR_PERSISTENT => false));

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    foreach ($dnr as $index => $key) {
        curl_setopt($ch, CURLOPT_URL, $url[$index] . '/a?f=j');

        $dh = curl_exec($ch);
        $data = json_decode($dh, true);
        $power = round(str_replace(',','.',$data['pwr']) / 1000,3);
        $total_energy = str_replace(',','.',$data['cnt']);

        $dbh->beginTransaction();
        try {
            $sql = 'insert into ' . $values_tablename . '
                          (datetime, dnr, power, total_energy)
                   values (now(), :dnr, :power, :total_energy)';
            $sth = $dbh->prepare($sql);
            $sth->bindValue(':dnr', $dnr[$index], PDO::PARAM_INT);
            $sth->bindValue(':power', $power, PDO::PARAM_STR);
            $sth->bindValue(':total_energy', $total_energy, PDO::PARAM_STR);
            $sth->execute();

            $sql = 'delete from ' . $daily_tablename . ' where dnr =  :dnr and date(date) = curdate()';
            $sth = $dbh->prepare($sql);
            $sth->bindValue(':dnr', $dnr[$index], PDO::PARAM_INT);
            $sth->execute();

            $sql = 'insert into ' . $daily_tablename . '
                        select curdate(), :dnr, tt - ty, tt from
    			            ((select count(total_energy) dummy,
                                     ifnull(total_energy,
                                            (select total_energy tt from ' . $values_tablename . ' where dnr =  :dnr order by datetime asc limit 1)
                                           ) ty
                                from ' . $values_tablename . ' where dnr =  :dnr and date(datetime) < curdate() order by datetime desc limit 1) a,
                            (select total_energy tt from ' . $values_tablename . ' where dnr =  :dnr order by datetime desc limit 1) b)
            ';

            $sth = $dbh->prepare($sql);
            $sth->bindValue(':dnr', $dnr[$index], PDO::PARAM_INT);
            $sth->execute();
            $dbh->commit();
        } catch(Exception $e) {
            $dbh->rollback();
            echo 'Message: ' .$e->getMessage() . $newline;
        }
    }
?>
