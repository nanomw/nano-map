# Nano Map

Map of 24/7 nodes with analysis by country and hosting provider. Only the nodes that have been online at least 90% during last 7 days are listed. Live demo here: https://nanomap.ddns.net/

It is created as a Progressive Web App. It means it works both on Android and iOS as a standalone app without installation via Play market/App store. All you need is just visit the website on your device and select "Add to home screen" option in the browser menu and voila you have a fully functional full screen mobile app.

## Features

Nodes are shown as markers on map. If you click a marker you'll see a tooltip which displays info about the node: 
- whether it's currently online or not
- IP address (if node is accessible via http by its IP address then it's a clickable link)
- hostname (if node is accessible via http by its hostname then it's a clickable link)
- city
- state/province
- country
- hosting provider (clickable)
- uptime and how long it's been online

In the top right corner of the screen you can see the green numbers (e.g. 298 (284) ) and below them the blue numbers (e.g. 90% 7D+). This is info about displayed nodes. It means that currently 298 nodes are shown, 284 of them are online. The nodes displayed are at least 90% online since 7 days ago or longer. If you click the green numbers you can change these settings. For example, you can choose to display only nodes that have been online at least 99% since 30 days or more. The blue numbers will change correspondingly.

Now take a look at the toolbar. In the top right corner you can see 4 icons: (from left to right) 
- globe
- chart
- list
- bar chart

#### Analysis by country (globe icon)

You can see a pie chart and table showing how many nodes each country has and its percentage compared to the whole. There is a "plus" sign next to the country name. If you click it you'll see analysis by state/province and by hosting provider for the given country. 

#### Analysis by hosting provider (chart icon)

Pie chart and table showing hosting providers global nano nodes market share. There is a checkbox next to each provider name. If you click one or several of them then two buttons will appear just below the chart. If you click the "Show selected on map" button the map will show only the nodes hosted by the provider(s) you selected. Now the green numbers at the top right corner display info about the selected provider(s). Now the numbers are clickable. If you click them you can choose if you want to continue viewing only selected provider(s) or come back to displaying all of them.

#### Nodes list (list icon)
 
List of nodes with all available details. All columns are sortable and searchable. For example, you can easily find your node by starting to type its IP address or hostname in the search field; you can search nodes by country, city, hosting provider etc.; sort by uptime or time since first online etc. etc. 

#### Statistics (bar chart icon)

Showing daily changes in 4 categories:
- Nodes with >99% uptime 
- Nodes with >90% uptime
- Total peers
- Unique IPs in last 10 days 

## Prerequisites

Web server, PHP, Composer, MariaDB for installation. NodeJS for development.

Note: https certificate for web server is required. I suggest obtaining it via letsencrypt. If you don't have (don't want to have) your own domain name you can get a free one at a dynamic dns service (e.g. https://www.noip.com/free). Letsencrypt supports certificates for such domain names.

## Installation

1. Upload the contents of `./server` folder to a location outside your web server document root. It must not be publicly accessible via http.
2. Run `composer require geoip2/geoip2:~2.0` in that folder.
3. Run `php ./dbdownload.php` in that folder. This will download free GeoIP databases from MaxMind.
4. Edit `config.php`
5. Execute sql queries from `createtables.sql`
6. Not 100% sure if this is required, but just in case populate the mysql time zone tables using the `mysql_tzinfo_to_sql` utility as described here: https://mariadb.com/kb/en/library/mysql_tzinfo_to_sql/ 
7. Add cronjobs (of course, replace `/path/to/folder` with your actual path):

    `17 18 * * 3  php /path/to/folder/dbdownload.php` # update GeoIP database once a week on Wednesdays.

    `*/10 * * * * php /path/to/folder/createjson.php` # update json files that are used by the client.

    `*/10 * * * * php /path/to/folder/activenodes.php` # insert nodes data into the database.

8. If you want to use Google Analytics then in file `./client/index.html` replace `UA-XXXXXXX-XX` with your tracking ID (there are two instances).
9. Upload the contents of  `./client` directory to your web server document root folder.
10. Set 404 page to `index.html`. Without this if user reloads page on a routed path, they will get a 404 error. For Nginx:
    ```
    error_page 404 /index.html;
    ```
11. You should see database populated and stats charts shown right away. But the nodes on the map will become visible only after 1 week after installation.


## Development

It is created with Quasar Framework. Read more about it here: https://quasar-framework.org/

1. Install to web server as described above.
2. `npm i`
3. If you want to use Google Analytics then in file `./src/index.template.html` replace `UA-XXXXXXX-XX` with your tracking ID (there are two instances).
4. In file `./src/store/module-data/state.js` set `host` variable to your base web server url, e.g. `host: 'https://subdomain.mysite.com',`. This is because the json files will be loaded from your web server.
5. Allow Access-Control-Allow-Origin in your web server config for json files. For example, for Nginx it is like this:
    ```
        location ~* \.(json)$ {
            add_header Access-Control-Allow-Origin *;
        }
    ```
6. `quasar dev -m pwa` will open your browser at `https://localhost:8080/` with hot module reload enabled.
7. `quasar build -m pwa` will generate production files in `./dist/pwa-mat` folder for uploading to web server.