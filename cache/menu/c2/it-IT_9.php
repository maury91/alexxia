<?php
$menu = array (
  'top' => '<li  ><a href=\'http://localhost/alexxia/offerte.htm\'>Offerte</a></li><li  ><a href=\'http://localhost/alexxia/ricette.htm\'>Le Nostre Ricette</a></li><li  ><a href=\'http://localhost/alexxia/prodotti.htm\'>Categorie Prodotti</a></li><li  class="cart "  id="cart_button"><a href=\'http://localhost/alexxia/\'>Carrello</a></li><li  class="cart "  ><a href=\'http://localhost/alexxia/zone_profile.html\'>Il mio profilo</a></li>',
  'right' => '<div  class="list-title" >Categorie</div><nav><ul>'.self::mod('pr_cats').'</ul></nav>',
  'slider' => ''.self::mod('nivoslider').'',
  'header' => ''.self::mod('search').''.self::mod('langs').''.self::mod('pr_cart').'',
  'footer' => ''.self::mod('infomauro').'',
);
?>