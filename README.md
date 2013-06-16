StoneApple
==========

A [Silex][s] + [Pomm][p] + [Behat][b] webapplication.

General idea
------------

Building a blog which serves me as a playground to toy around with various 
building blocks. The ultimate goal is to explore PostgreSQL & Pomm, the bonus 
is figuring out how to put the different blocks together using the Silex 
µframework (because it's been a while).

I'm a fan of BDD/TDD, that's why I'm using Behat+[Mink][m] to keep it all running 
smoothly.


Installation
------------

    $ git clone git@github.com:Lisje/StoneApple.git
    $ curl -sS https://getcomposer.org/installer | php
    $ php composer.phar install --dev

Apache2 vhost example:

    <VirtualHost *:80>
        ServerName stone-apple
        DocumentRoot /var/www/StoneApple/web
        DirectoryIndex index.php
        ErrorLog /var/log/apache2/error.stone-apple.log
        CustomLog /var/log/apache2/access.stone-apple.log combined
    </VirtualHost>

Database configuration (you need to create a config file for each environment):

    <?php # resources/config/dev.php

    // Pomm
    $app['pomm.dns'] = 'pgsql://user:pass@host:port/dbname';


Pomm/Model
----------

Generating the model from the db schema:

    $ php src/StoneApple/generate_model.php


Testing
-------

    $ ./bin/behat


Inspiration
-----------

* https://github.com/ronanguilloux/SilexMarkdown
* https://github.com/chanmix51/PommServiceProvider
* https://github.com/yuriteixeira/behat-silex
* https://github.com/everzet/silex-mink


[s]: http://silex.sensiolabs.org/
[p]: http://pomm.coolkeums.org/
[b]: http://behat.org/
[m]: http://mink.behat.org/
