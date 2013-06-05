<?php
/**
 *	Ecommerce Component for ALExxia
 *	This component is only for didactical use
 *	You can't use this component for commercial purpuose without authorization from the authors
 *	
 *	Copyright (c) 2013 Maurizio Carboni. All rights reserved.
 *
 *	This file is part of ALExxia.
 *	
 *	ALExxia is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *	
 *	ALExxia is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with ALExxia.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     alexxia
 * @author      Maurizio Carboni <maury91@gmail.com>
 * @copyright   2013 Maurizio Carboni
 * @license     http://www.gnu.org/licenses/  GNU General Public License
**/
include(__base_path.'com/ecommerce/config/lang/'.LANG::short().'.php');
if (isset($external['pay_config'])) {
	$__pay = DB::assoc(DB::select(array(array('nc__payments','*'),array('nc__translatesP','name')),array('nc__payments','nc__translatesP'),' WHERE lang = ',LANG::short(),' AND nc__payments_ref = '.DB::$pre.'nc__payments.id AND '.DB::$pre.'nc__payments.id = ',$external['pay_config']));
	include(__base_path.'com/ecommerce/payments/'.$__pay['UNI_ID'].'/config.php');
} else {
	if (isset($external['del'])) {
		//Eliminazione metodo di pagamento
		
	}
	SCRIPT::add('js/pay.js');
	STYLE::add('css/style.css','ecommerce/');
	STYLE::add('css/icons.css','ecommerce/');
	echo '<a class="com config_link abutton" href="ecommerce/config/payments.php?add">'.$__pay_add.'</a><script type="text/javascript">$(".abutton").button();</script><br/><br/><table width="100%" cellpadding="0" cellspacing="0"><thead><tr><td>#</td><td>'.$__name.'</td><td></td></tr></thead>';
	$pay_methods = DB::select(array(array('nc__payments','*'),array('nc__translatesP','name')),array('nc__payments','nc__translatesP'),' WHERE lang = ',LANG::short(),' AND nc__payments_ref = '.DB::$pre.'nc__payments.id');
	while ($pay_method = DB::assoc($pay_methods)) 
		echo '<tr><td>'.$pay_method['id'].'</td><td>'.$pay_method['name'].'</td><td><a title="'.$__pay_edit.'" class="img pay edit"></a> <a title="'.$__pay_del.'" class="img pay del"></a></tr>';
	echo '</table>';
}
?>