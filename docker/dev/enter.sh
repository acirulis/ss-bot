#!/bin/bash
docker exec -e XDEBUG_CONFIG="idekey=PHPSTORM" -e PHP_IDE_CONFIG="serverName=ssbot.local.io" -e SSLKEYLOGFILE=/var/www/html/ssl-key.log -w /var/www/html/ -it ssbot_dev_webserver bash

