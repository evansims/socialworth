# Socialworth
[![Latest Stable Version](https://poser.pugx.org/evansims/socialworth/v/stable.png)](https://packagist.org/packages/evansims/socialworth) [![Build Status](https://travis-ci.org/evansims/socialworth.svg?branch=master)](https://travis-ci.org/evansims/socialworth) [![Coverage Status](https://coveralls.io/repos/evansims/socialworth/badge.png?branch=master)](https://coveralls.io/r/evansims/socialworth?branch=master) [![License](https://poser.pugx.org/evansims/socialworth/license.png)](https://packagist.org/packages/evansims/socialworth)

A PHP library for determining the popularity of a given URL by querying social network APIs.

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

## Installation
To add this package as a dependency to your project, simply add
`evansims/socialworth` to your project's composer.json file.
Here is a minimal example of a composer.json file:

    {
        "require": {
            "evansims/socialworth": "*"
        }
    }

Then run `composer update` to install it. Composer generates an
`vendor/autoload.php` file you'll need to include in your project
before calling Socialworth.

    require 'vendor/autoload.php';

## Usage
To query all supported services for a URL from within your project:

    <?php
    use Evansims\Socialworth;

    $socialworth = Socialworth('https://github.com');
    var_dump($socialworth->all());
    ?>

Alternatively you can query just one:

    <?php
    use Evansims\Socialworth;

    var_dump(Socialworth::twitter('https://github.com'));
    ?>

Or leave out specific services from your query:

    <?php
    use Evansims\Socialworth;

    $socialworth = Socialworth('https://github.com');
    $socialworth->linkedin = false;

    var_dump($socialworth->all());
    ?>

The `all()` method will return an object that you can pull the total overall
count from, or individual services counts. Like so:

    <?php
    use Evansims\Socialworth;

    $socialworth = Socialworth('https://github.com');
    $response = $socialworth->all();

    var_dump($response->total); // Total likes, shares, upvotes, etc.
    var_dump($response->reddit); // Just shares and upvotes from reddit.
    var_dump($response->twitter); // Just mentions, retweets and shares on Twitter.
    ?>

## Demo Script
A demo script is provided that allows you to query the library from your
browser, or the command line.

To call the script from the command line ...

    $ php demo.php https://github.com

Or, to query individual services ...

    $ php demo.php --twitter --facebook https://github.com

If the demo script is accessible from your web server, you can pass a url ...

    http://localhost/socialworth.php?url=https://github.com

Whether from the CLI or the browser, you will receive a JSON object back.

    {
        "total": 20733,
        "facebook": 588,
        "pinterest": 0,
        "twitter": 570,
        "linkedin": 451,
        "stumbleupon": 15503,
        "reddit": 16,
        "hackernews": 497,
        "googleplus": 3108
    }

---

This work was inspired by Jonathan Moore's gist: https://gist.github.com/2640302
