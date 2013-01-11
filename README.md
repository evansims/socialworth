# Socialworth

A PHP script for determining the popularity of a given URL across social networks and search engines.

This script can be used as is or as a foundation for your own code. It's intended to demonstrate how one can harness existing APIs to acquire social networking metrics without the need for bloated client-side JavaScript SDKs or latency inducing middle man services.

It currently supports:
* Facebook likes, comments, click throughs and shares (combined by default; seperately with minor modifications)
* Twitter mentions of the url (via tweets, retweets, shares, or anything else.)
* Reddit (combines the number of submitted stories and upvotes)
* Hacker News (combines the number of submitted stories, points and comments)
* Google +1s globally
* StumbleUpon views
* LinkedIn shares
* Pinterest shares (note: the pinterest api is not officially open nor is it documented; it's also very unreliable in it's responses)
* Mozscape (for backlinks; requires a free account with their service)

---

The script itself is named socialworth.php by default. You can use demo.html to see it in action.

If you wish to include the backlinks check, you'll need to sign up for a seomoz Mozscape API account and modify the script to include your account details. None of the other services require setup.

---

Pass a url parameter to the script to receive a JSON object breaking down the metrics.

```json
{
	count: 20733,
	services: {
		mozscape: 0,
		facebook: 588,
		pinterest: 0,
		twitter: 570,
		linkedin: 451,
		stumbleupon: 15503,
		reddit: 16,
		hackernews: 497,
		googleplus: 3108
	}
}
```

---

This work was inspired by and expanded upon Jonathan Moore's gist: https://gist.github.com/2640302
