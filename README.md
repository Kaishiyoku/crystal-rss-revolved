# About

Crystal RSS is a minimalist newsfeed reader.

The features are limited by intention to be able to concentrate on what matters: reading the news.

Any RSS feed can be added and article summaries are then being fetched and displayed on the dashboard automatically.

The feed reader is optimized for usage on smartphones.

# Major release notes

## Version 3.x

Version 3.x is a complete rewrite of the codebase with the use of Inertia.js, so expect breaking changes.
I tried to keep the database structure the same as in version 2.x.
Please note that some DB table fields are no longer used, but you should be able to just upgrade
to version 3.x without having to remigrating the whole database.

* minimum PHP version: 8.1
* based on Laravel 10
* no API (maybe I will bring it back in the future)

# Installation

## General installation instructions

1. download the latest release: https://github.com/Kaishiyoku/Crystal-RSS/releases/latest
2. run `composer install --no-dev --no-scripts`
3. copy the .env.example file and fill in the necessary values: `@php -r \"file_exists('.env') || copy('.env.example', '.env');\"`
4. run `php artisan key:generate`
5. run `php artisan migrate`
6. run `npm install && npm run prod`

## Configuration options

* `RSS_CRAWLER_RETRY_COUNT=5` determines how many retries should be made if the RSS crawler fails for specific reasons
* `FEED_ITEMS_PER_PAGE=15` the number of feed items which should be displayed per page
* `MONTHS_AFTER_PRUNING_FEED_ITEMS=2` the number of months after read feed items should be deleted
* `CONTACT_EMAIL` is being displayed on the welcome page; leave empty if you don't want to display the contact us link
* `GITHUB_URL` is being displayed on the welcome page; leave empty if you don't want to display the GitHub link

# Features

* manage feeds and categorize them
* a feed discovery tool helps finding the feed URL for a given website (blogs, online newspapers etc.)
* the feed discovery tool automatically searches for the website's favicon
* the dashboard shows a list of newly fetched articles in chronological order
* you can mark articles as read
* read articles are being deleted automatically after a while
* the website is fully responsive and mobile-friendly


# Screenshots

<details>
<summary>Click to toggle screenshots</summary>

![Add feed](https://698857750-files.gitbook.io/~/files/v0/b/gitbook-x-prod.appspot.com/o/spaces%2F-MiLDJ9RD-asJHmj5ZWy%2Fuploads%2Fgit-blob-7cdcdd61d73aea880395927b75a399a44db1ea3b%2Fadd_feed.png?alt=media)

![Add feed](https://698857750-files.gitbook.io/~/files/v0/b/gitbook-x-prod.appspot.com/o/spaces%2F-MiLDJ9RD-asJHmj5ZWy%2Fuploads%2Fgit-blob-4608d2410c0b41cd93b4b09871015b12ff19872b%2Fadd_feed_dark.png?alt=media)

![List feeds](https://698857750-files.gitbook.io/~/files/v0/b/gitbook-x-prod.appspot.com/o/spaces%2F-MiLDJ9RD-asJHmj5ZWy%2Fuploads%2Fgit-blob-e595124eb4140e6bb707d8365f021952d29d8009%2Ffeeds.png?alt=media)

![List feeds](https://698857750-files.gitbook.io/~/files/v0/b/gitbook-x-prod.appspot.com/o/spaces%2F-MiLDJ9RD-asJHmj5ZWy%2Fuploads%2Fgit-blob-11d55e594d28157f7afd0bc66cd450bf85aac139%2Ffeeds_dark.png?alt=media)

![Dashboard](https://698857750-files.gitbook.io/~/files/v0/b/gitbook-x-prod.appspot.com/o/spaces%2F-MiLDJ9RD-asJHmj5ZWy%2Fuploads%2Fgit-blob-fe715fc21d2576c338822207b71de77e4508b27f%2Fdashboard.png?alt=media)

![Dashboard](https://698857750-files.gitbook.io/~/files/v0/b/gitbook-x-prod.appspot.com/o/spaces%2F-MiLDJ9RD-asJHmj5ZWy%2Fuploads%2Fgit-blob-a2aaa98b89df3b124d62bbfd48a9f3f3c038e953%2Fdashboard_dark.png?alt=media)

![Add feed (mobile)](https://698857750-files.gitbook.io/~/files/v0/b/gitbook-x-prod.appspot.com/o/spaces%2F-MiLDJ9RD-asJHmj5ZWy%2Fuploads%2Fgit-blob-1f8bad23739d3f9a9feafee324fa8370f2e4a36c%2Fadd_feed_mobile.png?alt=media)

![Add feed (mobile)](https://698857750-files.gitbook.io/~/files/v0/b/gitbook-x-prod.appspot.com/o/spaces%2F-MiLDJ9RD-asJHmj5ZWy%2Fuploads%2Fgit-blob-db41f7b3ac4a5af285f062d0ccf7aa2dbba77faa%2Fadd_feed_dark_mobile.png?alt=media)

![List feeds (mobile)](https://698857750-files.gitbook.io/~/files/v0/b/gitbook-x-prod.appspot.com/o/spaces%2F-MiLDJ9RD-asJHmj5ZWy%2Fuploads%2Fgit-blob-84e57857c69696ef9c4f13fe86c74247a7ef5f35%2Ffeeds_mobile.png?alt=media)

![List feeds (mobile)](https://698857750-files.gitbook.io/~/files/v0/b/gitbook-x-prod.appspot.com/o/spaces%2F-MiLDJ9RD-asJHmj5ZWy%2Fuploads%2Fgit-blob-8bb00e3489d5d90c9540da3f3bb852c6cd438ae9%2Ffeeds_dark_mobile.png?alt=media)

![Dashboard (mobile)](https://698857750-files.gitbook.io/~/files/v0/b/gitbook-x-prod.appspot.com/o/spaces%2F-MiLDJ9RD-asJHmj5ZWy%2Fuploads%2Fgit-blob-467c1c17476dcf318e5e5bb9f05fab71fe230aec%2Fdashboard_mobile.png?alt=media)

![Dashboard (mobile)](https://698857750-files.gitbook.io/~/files/v0/b/gitbook-x-prod.appspot.com/o/spaces%2F-MiLDJ9RD-asJHmj5ZWy%2Fuploads%2Fgit-blob-17ac945b77e23c4ea021315c6c98cc8a8d987c8e%2Fdashboard_dark_mobile.png?alt=media)
    
</details>


# FAQ

## What happened to the old Crystal RSS?

The old source code is still available at https://github.com/Kaishiyoku/Crystal-RSS. The repo has been archived though. The old Crystal RSS was too feature-rich already although I originally wanted it to be a minimalist newsfeed reader with an enjoyable user experience. Both premises weren't followed anymore by packing more and more features in it while not giving any more benefits beside some interesting insights (e.g. statistics). The old codebase ran pretty smoothly but fetching and saving new articles was slow because of fetching so much data. Back then the full article text has been fetched for the search feature.

But why should I search for old articles which I either already read or added to a read it later list? There are much better ways of saving articles for the future.

Same goes for the favorite and voting features. I planned to build some kind of algorithm on top of it but for what reason? If I'm interested in a certain topic I mainly filter by feed manually anyway. Crystal RSS isn't a smart replacement for the Google feed for example. It is a feed reader and optimally you want to read all articles or at least mark those as read you don't want to read. There is also another benefit of this approach: It gives some good feelings if you mark your last new articles as read and your dashboard is empty. You can then come back anytime looking for new articles.


# Author

[Andreas Wiedel](https://andreas-wiedel.de)
