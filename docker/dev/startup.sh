#!/bin/bash
#Startup multiple processes
service php8.1-fpm start
nginx -g "daemon off;"
