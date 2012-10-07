SUBE
=======

SUBE stands for Student Underground Buying Exchange. It's a simple website written in PHP, that allows for users to register and create books, course notes and housing listing.

Features
--------

  * Demo: [www.uwsube.com](http://www.uwsube.com/)
  * Basic separation of business and presentation logic.

Requirements
--------

  * PHP 5.2+ required.
  * Set register_globals=On and allow_url_fopen=On in php.ini
  * MySQL database

Getting Started
--------

  1. Copy the files from [web](https://github.com/andrejpavlovic/sube/tree/master/web) folder into the document root on your server.
  
  2. Create a MySQL database and generate starting data by running SQL script found in [install/database.sql](https://github.com/andrejpavlovic/sube/blob/master/install/database.sql)
  
  3. Update the database connection and other settings inside of [web/include/settings.php](https://github.com/andrejpavlovic/sube/blob/master/web/include/settings.php)

  4. The site should now be up!

Background
--------

In 2005, [UWSUBE](http://www.uwsube.com/) was designed and developed by a couple of University of Waterloo undergraduate students. Since then, thousands of UW students have used it to buy/sell books and find off-campus housing. Since there have been a lot of requests over the years to allow others to setup a similar service, it was decided to post the code on GitHub, and allow anyone to setup a similar site, or contribute to the existing codebase. And that's how SUBE came to be.

Development
--------

  * [Source code](https://github.com/andrejpavlovic/sube)
  * [Issue tracker](https://github.com/andrejpavlovic/sube/issues)

SUBE is developed by [Andrej Pavlovic](mailto:andrej.pavlovic@pokret.org). You are more than welcome to contribute by [logging issues](https://github.com/andrejpavlovic/sube/issues), [sending pull requests](http://help.github.com/send-pull-requests/), or [just giving feedback](mailto:andrej.pavlovic@pokret.org).

License
--------

SUBE has been released under the LGPLv3 license.
