# HeroPress

[![Code Climate](https://codeclimate.com/github/dthtvwls/HeroPress/badges/gpa.svg)](https://codeclimate.com/github/dthtvwls/HeroPress)
[![Code Coverage](https://scrutinizer-ci.com/g/dthtvwls/HeroPress/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/dthtvwls/HeroPress/?branch=master)
[![Build Status](https://travis-ci.org/dthtvwls/HeroPress.svg?branch=master)](https://travis-ci.org/dthtvwls/HeroPress)
[![Dependency Status](https://gemnasium.com/dthtvwls/HeroPress.svg)](https://gemnasium.com/dthtvwls/HeroPress)
[![Documentation Status](https://readthedocs.org/projects/heropress/badge/?version=latest)](https://readthedocs.org/projects/heropress/?badge=latest)

<!--
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dthtvwls/HeroPress/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/dthtvwls/HeroPress/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/dthtvwls/HeroPress/badges/build.png?b=master)](https://scrutinizer-ci.com/g/dthtvwls/HeroPress/build-status/master)
[![Test Coverage](https://codeclimate.com/github/dthtvwls/HeroPress/badges/coverage.svg)](https://codeclimate.com/github/dthtvwls/HeroPress)
[![Coverage Status](https://coveralls.io/repos/dthtvwls/HeroPress/badge.svg)](https://coveralls.io/r/dthtvwls/HeroPress)
[![Dependency Status](https://www.versioneye.com/user/projects/54f767984f31083e1b0016cc/badge.svg?style=flat)](https://www.versioneye.com/user/projects/54f767984f31083e1b0016cc)
[![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/dthtvwls/HeroPress.svg)](http://isitmaintained.com/project/dthtvwls/HeroPress "Average time to resolve an issue")
[![Percentage of issues still open](http://isitmaintained.com/badge/open/dthtvwls/HeroPress.svg)](http://isitmaintained.com/project/dthtvwls/HeroPress "Percentage of issues still open")
-->

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
