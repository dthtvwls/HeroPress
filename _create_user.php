#!/usr/bin/env php
<?php
if (php_sapi_name() !== 'cli') exit; // only allow CLI usage

require 'vendor/autoload.php'; // older php versions need the bcrypt shims in ircmaxell/password-compat

$username = readline('Enter username: ');
$password = escapeshellcmd(password_hash(readline('Enter password: '), PASSWORD_BCRYPT));

do {
  $platform = readline('Add this user to local SQLite (sqlite) or Heroku PostgreSQL (heroku)? ');

  if ($platform === 'sqlite') {
    `echo "INSERT INTO users (username, password) VALUES ('$username', '$password');" | sqlite3 db/db.sqlite3`;
  } else if ($platform === 'heroku') {
    `echo "INSERT INTO users (username, password) VALUES ('$username', '$password');" | heroku pg:psql`;
  } else {
    echo "Please enter 'sqlite' or 'heroku'.\n";
  }

} while ($platform !== 'sqlite' && $platform !== 'heroku');
