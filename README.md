StoneApple
==========

A [Silex][s] + [Pomm][p] + [Behat][b] webapplication.

General idea
------------

The goal is to have a *blog* running on Silex + Pomm, which is actually will 
have tests (TDD/BDD) in the future but that part is still under construction.


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


[s]: http://silex.sensiolabs.org/
[p]: http://pomm.coolkeums.org/
[b]: http://behat.org/