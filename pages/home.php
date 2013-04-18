Pagina principale!
<?php
$_SESSION['site_data']['pass'] = '123456';
$_SESSION['site_data']['nick'] = 'admin';
$_SESSION['site_data']['email'] = 'maury91@gmail.com';
$pass=CRYPT::BF($_SESSION['site_data']['pass'],6);
$pass=md5($pass).':'.substr($pass,0,29).'|'.CRYPT::BF($_SESSION['site_data']['pass'],7);
DB::insert('users',array('nick'=>$_SESSION['site_data']['nick'],'password'=>$pass,'email'=>$_SESSION['site_data']['email'],'level'=>0,'actived'=>1));
?>