#!/bin/bash
while :
do
    echo 'schedule:run started'
	php /var/www/artisan schedule:run
	php /app/idigi-cms-v2022/logfix.php
	sleep 60
done
