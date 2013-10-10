TheGame
=======

A game of "what was that awesome/retarded thing you said that time".  Keep track using a point system!

= Setup

From the project root

``mkdir data``

``mkdir data/logs``

Make the data directory (and subfolders) writable by your apache/nginx user

===========

``cp application/configs/application.ini-dist application/configs/application.ini``

Make your changes to the application.ini file (db credentials for each environment... email no-reply address, etc)

``cp public/.htaccess-dist public/.htaccess``

Set your environment and base rewrite in .htaccess

==========

Make sure mod rewrite is on (apache... not sure how to nginx this)

``sudo a2enmod rewrite``

=============

Document Root is [project]/public

