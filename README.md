# HeroPress

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dthtvwls/HeroPress/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/dthtvwls/HeroPress/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/dthtvwls/HeroPress/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/dthtvwls/HeroPress/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/dthtvwls/HeroPress/badges/build.png?b=master)](https://scrutinizer-ci.com/g/dthtvwls/HeroPress/build-status/master)

[![Build Status](https://travis-ci.org/dthtvwls/HeroPress.svg?branch=master)](https://travis-ci.org/dthtvwls/HeroPress)

A micro CMS designed to cause as little cognitive overhead as possible to my workflow
of creating marketing sites and iterating design and content.

HeroPress is really a thin layer over the Slim framework, that manages slug => content pairs via PDO.
The PDO abstraction makes it simple to use Heroku's Postgres on Heroku, but use SQLite in development.
This is valuable to me because as long as I have access to a relatively recent Mac, I'm good to go.

It also provides templating via Handlebars, which I prefer because I can reuse them in JS.

The other big win is CKEditor's inline mode, which I use to allow authenticated users to update
content with a solid WYSIWYG editor and no backend whatsoever.

See public/index.php for a good default setup.

## Setup

After cloning:

`sqlite3 db/db.sqlite3 < db/schema.sql`

`./composer install`

`./composer test` (optional, run tests)

`./composer start` (to start dev server on port 8888)

There is a script called _create_user.php that can create users from the command line. There should be a cleaner way to do this.

## Deployment

`heroku create` (or `heroku create *mysitename*`)

`heroku addons:add heroku-postgresql`

`heroku pg:psql < db/schema.sql` (requires psql command line client, try `brew install postgresql` otherwise you're on your own)

`git push heroku master`

https://dashboard.heroku.com/new?template=https://github.com/dthtvwls/HeroPress
