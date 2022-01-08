#!/bin/bash
#Startup multiple processes
cd /var/www/html
composer install
bin/console app:ssbot

