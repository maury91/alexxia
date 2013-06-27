<?php
HTML::add_style('css/profile.css');
if (isset($_CRIPTED)) {
	if (isset($_CRIPTED['pass'])) {
		//Cambia pass
		SECURE::returns(array('r'=>DB::update('users',array('password'=>$_CRIPTED['pass']),'WHERE id = ',USER::data('id'))));
	} elseif (isset($_CRIPTED['changeP'])) {
		include(LANG::path().'reg.php');
		$html = '<div class="left">'.$__pass.'</div>
			<div class="right"><input type="password" id="pass" /><span class="infox"></span></div>
			<div class="left">'.$__pass2.'</div>
			<div class="right"><input type="password" id="pass2" /><span class="infox"></span></div>
			<div class="left"><a id="savepass">Salva</a></div>';
		SECURE::returns(array(
			'content' => array(
				'html'=> $html,
				'js' => array(),
				'css' => array()),
			'lang' => array(
				'__pass_short' => $__pass_short,
				'__pass_equal' => $__pass_equal)));
	}
}
elseif (GET::exists('user')&&(GET::val('user') != 'me')) {

} elseif (USER::logged()) {
	if (!file_exists(__base_path.'users/'.USER::data('id').'/'))
		mkdir(__base_path.'users/'.USER::data('id').'/');
	$id = UPLOAD::make('users/'.USER::data('id').'/','images',false,'core/extra/profile_image.php');
	//Use the secure modal
	SECURE::libs();
	HTML::add_style('css/profile.css');
	HTML::add_script('js/profile.js');
	echo '<div class="secure_status"><div class="points"></div><div class="img unsecure"></div></div>
	<h3>Profilo di '.USER::data('nick').'</h3>
	<script type="text/javascript">
		__upload_id = "'.$id.'";
		__nick = "'.USER::data('nick').'"
	</script>
	<div class="data">
		<div class="photo"'.((USER::data('photo')!='')?'style="background-image:url(\''.__http.'users/'.USER::data('id').'/'.USER::data('photo').'\')"':'').'>
			<span class="img edit"></span>
		</div>
		<div class="info">
			<a id="change_user_info">Modifica</a>
			<span class="infos">'.USER::data('name').' '.USER::data('lastname').'</span>';
		foreach (PLUGINS::in('core','profile','add_fields') as $p) include($p);
		echo '
		</div>
		<a id="change_user_pass" style="inactive">Cambia Password</a>
	</div>';
} else echo 'Non sei loggato';
?>