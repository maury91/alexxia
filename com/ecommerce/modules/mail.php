<?php
switch ($mail_to_send) {
	case 'order' :
		$prod_list='';
		foreach($_SESSION['nc_cart'] as $k => $v){
			DB::insert('NxN__quantity_nc__ordersxnc__products',array('nc__orders'=>$invoice,'quantity'=>$v['tot'],'nc__products' => $k));
			$prod_list.= '<li style="display:block;border-left:3px solid #ccc;padding-left:10px;margin-bottom:5px;">'.str_replace(array('%image%','%name%','%price%','%quantity%','%name_style%','%price_style%','%quantity_style%'), array($v['img'],$v['name'],$v['price'],$v['tot'],'style="font-size:1.1em;font-weight:bolder;"','style="color: #C55;"',''), $__order_conf_prod_html).'</li>';
		}
		MAIL::send(USER::data('email'),$__order_conf_sub,str_replace(array('%sitename%','%fname%','%pay_link%','%prod_list%','%button_style%'), array(GLOBALS::val('sitename'),$ship_data['fname'],__http.'com/ecommerce/pay/'.$invoice.'.html','<ul style="padding: 0 0 0 5px;">'.$prod_list.'</ul>','style="padding: 5px 20px;background: rgb(255,48,25);  background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);  background: -moz-linear-gradient(top, rgba(255,48,25,1) 0%, rgba(207,4,4,1) 100%);  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,48,25,1)), color-stop(100%,rgba(207,4,4,1)));  background: -webkit-linear-gradient(top, rgba(255,48,25,1) 0%,rgba(207,4,4,1) 100%);  background: -o-linear-gradient(top, rgba(255,48,25,1) 0%,rgba(207,4,4,1) 100%);  background: -ms-linear-gradient(top, rgba(255,48,25,1) 0%,rgba(207,4,4,1) 100%);  background: linear-gradient(to bottom, rgba(255,48,25,1) 0%,rgba(207,4,4,1) 100%);  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#ff3019\', endColorstr=\'#cf0404\',GradientType=0 );  color: #EBA;  font-weight: bolder;  border-color: #D52;border-radius: 3px;text-decoration: none;"'), $__order_conf_html));
	break;
}
?>