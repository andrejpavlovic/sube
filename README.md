SUBE
=======

SUBE stands for Student Underground Buying Exchange. It is a simple website written in PHP that allows users to register and create book, course note and housing listings.

Features
--------

  * Live site: [www.uwsube.com](http://www.uwsube.com/)
  * Basic separation of business and presentation logic. Loosely based on MVC architecture.

Requirements
--------

  * PHP 5.2+
  * *register_globals* and *allow_url_fopen* must be enabled in php.ini
  * MySQL database

Getting Started
--------

  1. Copy the files from the [web folder](https://github.com/andrejpavlovic/sube/tree/master/web) into the document root on your server.
  
  2. Create a MySQL database and generate table data by running the SQL script found in [database/install.sql](https://github.com/andrejpavlovic/sube/blob/master/install/database.sql).
  
  3. Update the database connection and email settings inside of [web/include/settings.php](https://github.com/andrejpavlovic/sube/blob/master/web/include/settings.php).
  
  4. If you also want the book covers to show up, you need to ensure that *cache/book_covers* folder is writable by the server and configure Amazon API access in [web/include/settings.php](https://github.com/andrejpavlovic/sube/blob/master/web/include/settings.php). This means you will need to:
    1. Enable the feature
    2. Register for [Amazon Web Services](http://aws.amazon.com/)
    3. Update the AWS key ID
    4. Generate/update [private key and cert files](https://github.com/andrejpavlovic/sube/tree/master/web/include/amazon).
    5. Register for the [Amazon Affiliate Program](https://affiliate-program.amazon.com/) to receive the associate tag.

Backend Management
--------

Please note, there isn't any kind of a backend administration system. It was never developed.

Take a look at the [SQL queries](https://github.com/andrejpavlovic/sube/blob/master/USEFUL_SQL_QUERIES.md) that we used frequently in order to manage some of the data on the site.

Background
--------

In 2004, [UWSUBE](http://www.uwsube.com/) was designed and developed by a couple of University of Waterloo undergraduate students. Since then, thousands of UW students have used it to buy/sell books and find off-campus housing. Since there have been a lot of requests over the years to allow others to setup a similar service, it was decided to post the code on GitHub, and allow anyone use or contribute to the existing codebase.

Development
--------

  * [Source code](https://github.com/andrejpavlovic/sube)
  * [Issue tracker](https://github.com/andrejpavlovic/sube/issues)

You are more than welcome to contribute by [logging issues](https://github.com/andrejpavlovic/sube/issues), [sending pull requests](http://help.github.com/send-pull-requests/), or [just giving feedback](mailto:andrej.pavlovic@pokret.org).

License
--------

SUBE has been released under the LGPLv3 license.
