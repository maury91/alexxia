<?php
	DB::update('users',array('photo'=>$fname),' WHERE id = ',USER::data('id'));
?>