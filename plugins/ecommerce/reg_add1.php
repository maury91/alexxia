<?php
/**
*	Aggiunti di campi alla registrazione
**/
include(dirname(__FILE__).'/'.LANG::short().'.php');
HTML::add_script('plugins/ecommerce/reg.js','plugins/ecommerce/jsvat.js');
echo '<div class="left">'.$__u_type.'*</div>
<div class="right"><select name="nc_type">
<option value="0">'.$__normal.'</option>
<option value="99">'.$__ditta.'</option>
</select></div>
<script type="text/javascript">
__nc_soc_short = "'.$__nc_soc_short.'";
__nc_piva_err = "'.$__nc_piva_err.'";
</script>';
?>