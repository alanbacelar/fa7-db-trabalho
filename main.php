<?php

require_once('./time_helper.php');
require_once('./connection.php');
require_once('./test.php');
require_once('./mongo_db_test.php');
require_once('./sqlite_db_test.php');
require_once('./postgresql_db_test.php');
require_once('./config.php');

$timer = new TimeHelper('Total');
$timer->start(null);

echo "\n\n ========================   MONGODB ======================== \n\n";

$mongodb = new MongoDBTest(new Connection('mongodb'), new TimeHelper('MongoDBTest'));
$mongodb->run();

echo "\n\n ========================   SQLITE  ======================== \n\n";

$sqlite = new SQLiteDBTest(new Connection('sqlite'), new TimeHelper('SQLiteDBTest'));
$sqlite->run();

echo "\n\n ========================   POSTGRES ======================== \n\n";

$connection = new Connection('postgresql');
$connection->setPostgreSQLConfig($CONFIG['postgresql']);

$postgresql = new PostgreSQLDBTest($connection, new TimeHelper('PostgreSQLDBTest'));
$postgresql->run();

echo "\n\n ========================   FIM ======================== \n\n";
$timer->stop();

?>