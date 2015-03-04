<?php
require __DIR__ . '/../vendor/autoload.php';

use HeroPress\App;
use HeroPress\Data;
use HeroPress\Security;


// Initialize the app
$app = new App;


// Define endpoints for login and logout handlers
$app->post('/login/', Security::login());
$app->get('/logout/', Security::logout());


/*
 * Catch-all route. Should be last.
 */
$app->map('/:slug?', function ($slug = '') use ($app) {

  if ($app->request->isPost()) {
    $app->response->status(Security::isLoggedIn() && Security::csrfValid() ? Data::upsert($slug) : 401);

  } else if ($app->request->isAjax()) {
    echo Data::select($slug);

  } else {
    $app->render('layout', $app->addParams([ 'slug' => $slug ]));
  }
})->via('GET', 'POST');


// Run the app
$app->run();
