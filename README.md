# HeroPress

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dthtvwls/HeroPress/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/dthtvwls/HeroPress/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/dthtvwls/HeroPress/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/dthtvwls/HeroPress/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/dthtvwls/HeroPress/badges/build.png?b=master)](https://scrutinizer-ci.com/g/dthtvwls/HeroPress/build-status/master)

[![Build Status](https://travis-ci.org/dthtvwls/HeroPress.svg?branch=master)](https://travis-ci.org/dthtvwls/HeroPress)
[![Dependency Status](https://gemnasium.com/dthtvwls/HeroPress.svg)](https://gemnasium.com/dthtvwls/HeroPress)
[![Code Climate](https://codeclimate.com/github/dthtvwls/HeroPress/badges/gpa.svg)](https://codeclimate.com/github/dthtvwls/HeroPress)
[![Test Coverage](https://codeclimate.com/github/dthtvwls/HeroPress/badges/coverage.svg)](https://codeclimate.com/github/dthtvwls/HeroPress)

Guess what? I hate WordPress but I still need simple content management that runs on cheap hosting or Heroku.

Slim because it's probably the nicest PHP microframework.

Handlebars because it's the best, period.

CKEditor because it's amazing, check it out: http://ckeditor.com/demo#inline

Has a small, solid codebase that's secure and tested.

## Setup

After cloning:

`db/create`

`db/add-user` (create a username/password for logging in to edit content)

`./composer install`

`./composer test` (optional, run tests)

`./composer start` (start dev server on port 8888)

## Deployment

`heroku create` (or `heroku create *mysitename*`)

`heroku addons:add heroku-postgresql`

`heroku run db/create`

`heroku run db/add-user`

`git push heroku master`

https://dashboard.heroku.com/new?template=https://github.com/dthtvwls/HeroPress
