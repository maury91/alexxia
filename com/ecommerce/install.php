<?php
$products = DB::create('nc__products');
$images = DB::create('nc__images');
$users = DB::read('users');
$offers = DB::create('nc__offers');
$orders = DB::create('nc__orders');
$categories = DB::create('nc__categories');
$shipments = DB::create('nc__shipments');
$weights = DB::create('nc__weights');
$payments = DB::create('nc__payments');
$reviews = DB::create('nc__reviews');
$users
	->property('founds')->type('float')->not_null()->end();
$products
	->property('price')->type('float')->not_null()->end()
	->property('name')->dimension(30)->not_null()->end()
	->property('peso')->type('float')->not_null()->end()
	->property('descrizione')->type('text')->not_null()->end()
	->property('quantity')->type('int')->unsigned()->not_null()->end()
	->property('stars')->type('int')->unsigned()->not_null()->end()
	->belongs_to($categories)
	->has_many($images)
	->has_many($orders)
	->has_many($offers)
	->has_many($payments)
	->has_many($shipments);
$images
	->property('url')->dimension(150)->not_null()->end();
$categories
	->property('name')->dimension(80)->not_null()->end()
	->belongs_to($categories);
$reviews
	->property('comment')->type('text')->not_null()->end()
	->property('data')->type('timestamp')->set_default(CURRENT)->not_null()->end()
	->property('vote')->type('int')->unsigned()->not_null()->end()
	->belongs_to($users)
	->belongs_to($products);
$offers
	->property('start')->type('date')->not_null()->end()
	->property('end')->type('date')->not_null()->end()
	->property('q_min')->type('int')->unsigned()->not_null()->end()
	->property('coupon')->dimension(20)->not_null()->end()
	->property('sale')->type('float')->not_null()->set_default(0)->end()
	->property('description')->type('text')->not_null()->end()
	->has_many($products)
	->has_many($orders);
$orders
	->property('total')->type('float')->not_null()->end()
	->property('payed')->type('boolean')->not_null()->set_default(0)->end()
	->property('quantity')->type('int')->unsigned()->not_null()->set_default(1)->from($products)->end()
	->belongs_to($users)
	->has_many($offers)
	->has_many($products)
	->belongs_to($payments)
	->belongs_to($shipments);
$shipments
	->property('name')->dimension(60)->not_null()->end()
	->property('time_min')->type('int')->unsigned()->not_null()->end()
	->property('time_max')->type('int')->unsigned()->not_null()->end()
	->has_many($products);
$weights
	->property('price')->type('float')->not_null()->end()
	->property('min')->type('float')->not_null()->end()
	->property('max')->type('float')->not_null()->end()
	->belongs_to($shipments);
$payments
	->property('price')->type('float')->not_null()->end()
	->property('name')->dimension(60)->not_null()->end()
	->property('UNI_ID')->dimension(16)->not_null()->end()
	->has_many($products);


$users->save();
$offers->save();
$orders->save();
$categories->save();
$products->save();
$images->save();
$shipments->save();
$weights->save();
$payments->save();
$reviews->save();
?>