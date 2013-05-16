<?php
include(dirname(__FILE__).'/'.LANG::short().'.php');
echo '<div class="nc_ditta">
<div class="left">'.$__nation.'*</div>
<div class="right"><select name="nc_nation">';
foreach ($__nations as $k=>$v)
	echo '<option value="'.$k.'">'.$v.'</option>';
echo '</select></div>
<div class="left">'.$__soc.'*</div>
<div class="right"><input type="text" name="nc_soc"/><span class="info"></span></div>
<div class="left">'.$__piva.'*</div>
<div class="right"><input type="text" name="nc_piva"/><span class="info"></span></div>
</div>';
?>