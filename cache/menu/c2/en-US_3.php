<?php
$menu = array (
  'top' => '<li  ><a href=\'http://localhost/alexxia/offers.htm\'>Offers</a></li><li  ><a href=\'http://localhost/alexxia/recipes.htm\'>Food recipes</a></li><li  ><a href=\'http://localhost/alexxia/products.htm\'>Product Categories</a></li><li  class="cart "  id="cart_button"><a href=\'http://localhost/alexxia/\'>Cart</a></li><li  class="cart "  ><a href=\'http://localhost/alexxia/zone_profile.html\'>My Account</a></li>',
  'right' => '<div  class="list-title" >Categories</div><nav><ul>'.self::mod('pr_cats').'</ul></nav>',
  'slider' => ''.self::mod('nivoslider').'',
  'header' => ''.self::mod('search').''.self::mod('langs').''.self::mod('pr_cart').'',
  'footer' => ''.self::mod('infomauro').'',
);
?>