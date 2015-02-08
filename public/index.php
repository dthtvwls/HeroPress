<?php
require __DIR__ . '/../vendor/autoload.php';

/*
 * Construct a DSN, using DATABASE_URL if defined (as on Heroku)
 * or a local SQLite DB file otherwise
 */
if (isset($_ENV['DATABASE_URL'])) {
  $p = parse_url($_ENV['DATABASE_URL']);
  $dsn = "pgsql:user={$p['user']};password={$p['pass']};host={$p['host']};dbname=" . ltrim($p['path'], '/');
} else {
  $dsn = 'sqlite:' . __DIR__ . '/../db/db.sqlite3';
}

// Initialize the app
$app = new HeroPress($dsn);

// Define endpoints for login and logout handlers
$app->post('/login/', $app->dbLogin());
$app->get('/logout/', $app->logout());


/*
 * Catch-all route. Should be last.
 */
$app->map('/:slug?', function ($slug = '') use ($app) {

  if ($app->request->isPost()) {
    $app->response->status(
      $app->isLoggedIn() && $app->csrfValid() ? $app->upsert($slug, file_get_contents('php://input')) : 401
    );

  } else if ($app->request->isAjax()) {
    echo $app->select($slug);

  } else {
    $app->render('layout', $app->mergeParams([ 'slug' => $slug ]));
  }
})->via('GET', 'POST');


// Run the app
$app->run();
