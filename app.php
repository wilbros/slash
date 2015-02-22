<?php
require __DIR__.'/vendor/autoload.php';

$app = new \Slash\Slash([
	'app.environment' => \Slash\Slash::DEV,
	'app.debug' => true,
	'route.caseSensitive' => false
]);

$app->get('/', function() {
	echo 'Hello World!';
});

$app->get('/product/{id}/list', function($id) {
	echo 'Hello Product :). Parameter Id: '.$id;
});

$app->post('/product/{id}/update', function($id) {
	echo 'Hello Product :). Parameter Id: '.$id." - Update";
});

$app->map('/about', function() {
	echo 'Hello Multiple World!';
})->method([\Slash\Http\Request::GET, \Slash\Http\Request::POST]);

$app->run();

