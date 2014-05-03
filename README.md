# Socialworth
[![Latest Stable Version](https://poser.pugx.org/evansims/socialworth/v/stable.png)](https://packagist.org/packages/evansims/socialworth) [![Build Status](https://travis-ci.org/evansims/socialworth.svg?branch=master)](https://travis-ci.org/evansims/socialworth) [![Coverage Status](https://coveralls.io/repos/evansims/socialworth/badge.png?branch=master)](https://coveralls.io/r/evansims/socialworth?branch=master) [![License](https://poser.pugx.org/evansims/socialworth/license.png)](https://packagist.org/packages/evansims/socialworth)

A simple PHP library for determining the popularity of a given URL by querying social network APIs.

It presently supports:
- Twitter (counts mentions and retweets)
- Facebook (counts likes, comments and shares)
- Google+ (+1s)
- Pinterest (shares)
- Reddit (counts submitted stories and upvotes)
- StumbleUpon views
- LinkedIn shares
- ~~Hacker News~~ _API service is currently offline._
- ~~Mozscape Backlinks~~ _Retired._

There a variety of use cases for this library; generating a list of your blog's
most popular articles for optimizing placement, or featuring social network
counters on your pages without relying on bloated external JavaScript includes.

## Installation
To add this package as a dependency for your project, simply add
`evansims/socialworth` to your project's composer.json file.
Here is an example of a minimal composer.json file:

    {
        "require": {
            "evansims/socialworth": "*"
        }
    }

Then run `composer install` to install the library. Composer generates a
`vendor/autoload.php` file that you'll need to include in your project
before invoking Socialworth:

    require 'vendor/autoload.php';

## Usage
To query all supported services for a URL:

    <?php
    use Evansims\Socialworth;

    $socialworth = Socialworth('https://github.com/');
    var_dump($socialworth->all());
    ?>

Alternatively you can query just one service:

    <?php
    use Evansims\Socialworth;

    var_dump(Socialworth::twitter('https://github.com/'));
    ?>

Or leave out specific services from your query:

    <?php
    use Evansims\Socialworth;

    $socialworth = Socialworth('https://github.com/');
    $socialworth->linkedin = false;

    var_dump($socialworth->all());
    ?>

The `all()` method will return an object that you can use to grab individual
service results or find the combined popularity from the services:

    <?php
    use Evansims\Socialworth;

    $socialworth = Socialworth('https://github.com/');
    $response = $socialworth->all();

    var_dump($response->total); // Total likes, shares, upvotes, etc.
    var_dump($response->reddit); // Just shares and upvotes from reddit.
    var_dump($response->twitter); // Just mentions, retweets and shares on Twitter.
    ?>

## Demo Script
A demo script is provided that allows you to query the library from your
browser, or the command line.

To call the script from the command line ...

    $ php demo.php https://github.com/

Or, to query individual services ...

    $ php demo.php --twitter --facebook https://github.com/

If the demo script is accessible from your web server, you can pass a url ...

    http://localhost/path/to/demo.php?url=https://github.com/

Whether from the CLI or the browser, you will receive a JSON object back.

    {
        "total": 48217,
        "twitter": 26582,
        "facebook": 15284,
        "pinterest": 157,
        "reddit": 5,
        "googleplus": 6049,
        "stumbleupon": 297,
        "linkedin": 0
    }

---

This work was inspired by Jonathan Moore's gist: https://gist.github.com/2640302
