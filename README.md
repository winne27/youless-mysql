# youless_mysql

This program writes data from a YouLess Energy Monitor in a MySql (MariaDB) database. For more details about this monitor see http://www.youless.nl

# Installation

* Copy the three php files to a directory of your choice. 

* Create a database and a database user called youless in your MySql or MariaDB database.

* Edit config.php and follow the comments inside. (set IP of your YouLess unit, set DB credits, name your device)

* It is possible to maintain multible devices. See config.php

* Create the tables in DB by starting tables.php.

* Create a cron job for starting 'php reading.php' in an interval of your choice.

# Example

At https://fehngarten.de/wetter you see, amongst other things, a sun indicator. This is realized by a YouLess Energy Monitor which is measuring the power of a photovoltaics unit.