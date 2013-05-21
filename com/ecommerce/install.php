<?php
/**
 *	Ecommerce Component for ALExxia
 *	This component is only for didactical use
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
$users = DB::read('users');
$categories = DB::create('nc__categories');
$traduzioniC = DB::create('nc__translatesC');
$products = DB::create('nc__products');
$sconti = DB::create('nc__sales');
$traduzioni = DB::create('nc__translates');
$reviews = DB::create('nc__reviews');
$prices = DB::create('nc__prices');
$categoriesU = DB::create('nc__categoriesU');
$images = DB::create('nc__images');
$coupons = DB::create('nc__coupons');
$orders = DB::create('nc__orders');
$payments = DB::create('nc__payments');
$traduzioniP = DB::create('nc__translatesP');
$geographic = DB::create('nc__geographic');
$traduzioniG = DB::create('nc__translatesG');
$shipments = DB::create('nc__shipments');
$traduzioniS = DB::create('nc__translatesS');
$weights = DB::create('nc__weights');
$users
	->property('founds')->type('float')->not_null()->end()
	->property('nc_cat')->type('int')->unsigned()->not_null()->end()
	->property('nc_nation')->dimension(3)->not_null()->set_default('IT')->end()
	->property('nc_soc')->dimension(60)->not_null()->end()
	->property('nc_piva')->dimension(20)->not_null()->end();
$categories
	->belongs_to($categories);
$traduzioniC
	->property('name')->dimension(80)->end()
	->property('lang')->dimension(5)->not_null()->end()
	->belongs_to($categories);
$products
	->property('stars')->type('int')->unsigned()->not_null()->end()
	->property('sells')->type('int')->unsigned()->not_null()->end()
	->property('duration')->type('int')->not_null()->end()
	->property('dimension_H')->type('int')->unsigned()->not_null()->end()
	->property('dimension_W')->type('int')->unsigned()->not_null()->end()
	->property('dimension_L')->type('int')->unsigned()->not_null()->end()
	->property('peso')->type('float')->not_null()->end()
	->belongs_to($categories)
	->has_many($images)
	->has_many($orders)
	->has_many($coupons)
	->has_many($payments)
	->has_many($shipments)
	->has_many($prices)
	->has_many($sconti);
$sconti
	->property('sale')->type('float')->not_null()->end()
	->property('start')->type('date')->not_null()->end()
	->property('end')->type('date')->not_null()->end()
	->has_many($products);
$traduzioni
	->property('name')->dimension(30)->not_null()->end()
	->property('descrizione')->type('text')->not_null()->end()
	->property('lang')->dimension(5)->not_null()->end()
	->belongs_to($products);
$reviews
	->property('comment')->type('text')->not_null()->end()
	->property('data')->type('timestamp')->set_default(CURRENT)->not_null()->end()
	->property('vote')->type('int')->unsigned()->not_null()->end()
	->belongs_to($users)
	->belongs_to($traduzioni)
	->belongs_to($products);
$prices
	->property('price')->type('float')->not_null()->end()
	->property('q_min')->type('int')->unsigned()->not_null()->end()
	->property('q_max')->type('int')->unsigned()->not_null()->end()
	->belongs_to($categoriesU);
$categoriesU
	->property('name')->dimension(30)->not_null()->unique()->end()
	->property('fixed_sale')->type('float')->not_null()->end()
	->has_many($users);
$images
	->property('url')->dimension(150)->not_null()->end();	
$coupons	
	->property('code')->dimension(30)->not_null()->end()
	->property('sale')->type('float')->not_null()->end()
	->has_many($products)
	->has_many($orders);
$orders
	->property('total')->type('float')->not_null()->end()
	->property('payed')->type('boolean')->not_null()->set_default(0)->end()
	->property('quantity')->type('int')->unsigned()->not_null()->set_default(1)->from($products)->end()
	->belongs_to($users)
	->has_many($coupons)
	->has_many($products)
	->belongs_to($payments)
	->belongs_to($shipments);
$payments
	->property('price')->type('float')->not_null()->end()
	->property('UNI_ID')->dimension(16)->not_null()->end() 
	->has_many($products);
$traduzioniP
	->property('name')->dimension(60)->not_null()->end()
	->property('lang')->dimension(5)->not_null()->end()
	->belongs_to($payments);
$traduzioniG
	->property('name')->dimension(60)->not_null()->end()
	->property('lang')->dimension(5)->not_null()->end()
	->belongs_to($geographic);
$shipments
	->property('time_min')->type('int')->unsigned()->not_null()->end()
	->property('time_max')->type('int')->unsigned()->not_null()->end()
	->belongs_to($geographic)
	->has_many($products);
$traduzioniS
	->property('name')->dimension(60)->not_null()->end()
	->property('lang')->dimension(5)->not_null()->end()
	->belongs_to($shipments);	
$weights
	->property('price')->type('float')->not_null()->end()
	->property('min')->type('float')->not_null()->end()
	->property('max')->type('float')->not_null()->end()
	->belongs_to($shipments);
$users->save();
$categories->save();
$traduzioniC->save();
$products->save();
$sconti->save();
$traduzioni->save();
$reviews->save();
$categoriesU->save();
$prices->save();
$images->save();
$coupons->save();
$orders->save();
$payments->save();
$traduzioniP->save();
$geographic->save();
$traduzioniG->save();
$shipments->save();
$traduzioniS->save();
$weights->save();

DB::insert('nc__categoriesU',array('name'=>'Normale'));
?>