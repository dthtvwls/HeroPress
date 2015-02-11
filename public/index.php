<?php
require __DIR__ . '/../vendor/autoload.php';


// Initialize the app
$app = new HeroPress;


// Define endpoints for login and logout handlers
$app->post('/login/', $app->dbLogin());
$app->get('/logout/', $app->logout());


/*
 * Catch-all route. Should be last.
 */
$app->map('/:slug?', function ($slug = '') use ($app) {

  if ($app->request->isPost()) {
    $app->response->status($app->isLoggedIn() && $app->csrfValid() ? $app->upsert($slug) : 401);

  } else if ($app->request->isAjax()) {
    echo $app->select($slug);

  } else {
    $app->render('layout', $app->mergeParams([ 'slug' => $slug ]));
  }
})->via('GET', 'POST');


// Run the app
$app->run();
