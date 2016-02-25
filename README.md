# HeroPress

[![Build Status](https://travis-ci.org/dthtvwls/HeroPress.svg?branch=master)](https://travis-ci.org/dthtvwls/HeroPress)

Simple CMS using Bootstrap, jQuery, and Handlebars. Uses PHP's Slim microframework on the backend and CKEditor for
edit-in-place.

Demo: http://hero-press.herokuapp.com/ (username: example@example.com, password: "example") (Try navigating to a random url)

## Setup

After cloning:

`db/create`

`db/add-user` (create a username/password for logging in to edit content)

`./composer install`

`./composer start` (start dev server on port 8888)

## Deployment

[![Launch on Heroku](https://www.herokucdn.com/deploy/button.svg)](https://dashboard.heroku.com/new?template=https://github.com/dthtvwls/HeroPress)

Don't forget to run afterwards:

`heroku run db/create`

`heroku run db/add-user`
