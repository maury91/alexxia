<?php
include_once(__base_path.'levels/0/db_mysql.php');
include_once(__base_path.'levels/0/db_SQLite3.php');
DB::set_DB(new ALEmysql('localhost','root','','ale','ale__'));
DB2::set_DB(new ALESQLite3('','','','ale','ale__'));
?>