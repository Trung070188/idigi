#!/bin/bash
while :
do
  echo 'schedule:run started'
	php /var/www/artisan schedule:run
	sleep 60
done
