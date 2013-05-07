<?php
define('__base_path',dirname(__FILE__).'/');
require(__base_path.'levels/3/loader.php');
include(__base_path.'config/infoserver.php');
if (isset($_GET['id'])) {
	$captcha = new CAPTCHA($_GET['id']);
	$captcha->show();
} elseif (isset($_GET['new'])) {
	$captcha = new CAPTCHA($_GET['new']);
	echo json_encode(array('txt'=>$captcha->text(true)));
}
?>