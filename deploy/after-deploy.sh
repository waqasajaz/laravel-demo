#!/bin/bash
cd /home/ubuntu/codedeploy
cp -r * /var/www/html/
cd /var/www/html/
chown -R ytdjshags:ytdjshags *
chown -R www-data:www-data storage/
chown -R www-data:www-data public/storage/
