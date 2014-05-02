**Socialworth** is a PHP script for determining the popularity of a given URL across social networks and search engines.

This script can be used as is or as a foundation for your own code. It's intended to demonstrate how one can harness existing APIs to acquire social networking metrics without the need for bloated client-side JavaScript SDKs or latency inducing middle man services.

It currently supports:
* Facebook likes, comments, click throughs and shares (combined.)
* Twitter mentions of the url (tweets, retweets, or shares.)
* Reddit (submitted stories + upvotes.)
* Hacker News (submitted stories, points + comments.)
* Google +1s.
* StumbleUpon views.
* LinkedIn shares.
* Pinterest shares. _Note: The Pinterest api is not documented and can be unreliable._
* Backlinks via [Mozscape's API](http://moz.com).

---

**Note:** For search engine backlinking you'll need to sign up for a [Mozscape API account](http://moz.com/) and modify the script to include your account details. Mozscape counts will not be included in responses unless this has been configured.

---

You can call the script from the command line ...
```
$ php socialworth.php https://github.com
```

Or pass a url parameter to the script ...
```
http://localhost/socialworth.php?url=https://github.com
```

... to receive a JSON object breaking down the metrics:
```json
{
    "count": 20733,
    "services": {
        "facebook": 588,
        "pinterest": 0,
        "twitter": 570,
        "linkedin": 451,
        "stumbleupon": 15503,
        "reddit": 16,
        "hackernews": 497,
        "googleplus": 3108
    }
}
```

---

This work was inspired by Jonathan Moore's gist: https://gist.github.com/2640302
