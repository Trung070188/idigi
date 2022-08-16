#!/bin/bash

while :
do
  echo "queue:work started"
  php /var/www/artisan queue:work --tries=3 --timeout=120 2>&1
  sleep 5
done
