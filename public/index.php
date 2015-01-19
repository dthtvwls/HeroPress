<?php
require __DIR__ . '/../vendor/autoload.php';

/*
 * This strategy is meant to use Heroku Postgres on Heroku
 * and a SQLite DB otherwise.
 * Feel free to change it.
 */
if (isset($_ENV['DATABASE_URL'])) {
  $p = parse_url($_ENV['DATABASE_URL']);
  $dsn = "pgsql:user={$p['user']};password={$p['pass']};host={$p['host']};dbname=" . ltrim($p['path'], '/');
} else {
  $dsn = 'sqlite:' . __DIR__ . '/../db/db.sqlite3';
}

// Initialize the app
$app = new HeroPress($dsn);

/*
 * Default login/out handlers.
 * Change URLs freely but remember to update them in templates.
 */
$app->post('/login/', $app->databaseLoginHandler());
$app->get('/logout/', $app->genericLogoutHandler());

/*
 * Treat any unhandled POST request as an attempt to upsert a slug's content.
 */
$app->post('/:slug?', function ($slug = '') use ($app) {
  $app->response()->status(
    $app->isLoggedIn() && $app->csrfValid() ?
      $app->upsert($slug, file_get_contents('php://input')) : 401
  );
});

/*
 * Show the default layout and any content, for any unhandled GET slug.
 */
$app->get('/:slug?', function ($slug = '') use ($app) {
  $app->render('templates/layout.hbs', [
    'logged-in'  => $app->isLoggedIn(),
    'csrf-token' => $app->csrfToken(),
    'slug'       => $slug,
    'content'    => $app->select($slug)
  ]);
});

// Run the app
$app->run();
