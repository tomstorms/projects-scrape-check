# Island Meets City / ScrapeCheck

## Overview

The ScrapeCheck project consists of multiple projects:
- Scrape Engine = Puppeteer scraping
- Check Engine  = Platform that verifies data that was scrape
- Portal        = WordPress website that you can view stats on


## Get Started

```
lando start
```

Portal: /public_html

## Supports Lando

This project supports lando.

```
lando start
```

Export or Import the database:

```
lando db-export +db/wordpress.sql
lando db-import +db/wordpress.sql.gz
```

## Scrape Engine

This is a node based app. Run it using this command:

```
cd scrape-engine
npm install
npm run start
```

This project connects to the Portal Queue table to know what URLs to scrape.

All the scrape data is stored here:

```
cd scrape-data
```

You may need to update the server endpoint URL depending on your lando's instance.

```/scrape-check/scrape-engine/scrape.js```

and update the ```const serverEndpoint``` variable value.


## Check Engine

This is a PHP based project.

```
cd check-engine
```


## Portal

This is a WordPress website


## Running on Ubuntu

```
apt-get install -yq --no-install-recommends libasound2 libatk1.0-0 libc6 libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgcc1 libgconf-2-4 libgdk-pixbuf2.0-0 libglib2.0-0 libgtk-3-0 libnspr4 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 libx11-6 libx11-xcb1 libxcb1 libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 libxrandr2 libxrender1 libxss1 libxtst6 libnss3 
```


## References

networkidle0 = waits for the network to be idle (no requests for 500ms). Wait for JS to render page. Good for Single Page Applications
networkidle2 = handy for pages that do long-polling


## Other Resources

- https://www.aymen-loukil.com/en/blog-en/google-puppeteer-tutorial-with-examples/
- https://developers.google.com/web/tools/puppeteer/articles/ssr