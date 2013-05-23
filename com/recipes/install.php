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
$products = DB::read('nc__products');
$recipes = DB::create('nc__recipes');
$translatesr = DB::create('nc__translatesR');
$images = DB::create('nc__r_images');
$products
	->has_many($recipes);
$recipes
	->property('difficulty')->type('INT')->dimension(1)->not_null()->end()
	->property('tempo')->type('INT')->dimension(1)->not_null()->end()
	->has_many($products);
$translatesr
	->property('name')->dimension(60)->not_null()->end()
	->property('lang')->dimension(5)->not_null()->end()
	->property('ingredients')->type('TEXT')->not_null()->end()
	->property('preparation')->type('TEXT')->not_null()->end()
	->belongs_to($recipes);
$images
	->property('url')->dimension(150)->not_null()->end()
	->belongs_to($recipes);
$products->save();
$recipes->save();
$translatesr->save();
$images->save();
?>