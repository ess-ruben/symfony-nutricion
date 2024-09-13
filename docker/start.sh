#!/bin/sh
set -xe
composer require symfony/runtime
# cp  /var/www/html/.env /var/www/html/.env

# Detect the host IP
#export DOCKER_BRIDGE_IP=$(ip ro | grep default | cut -d' ' -f 3)

#rm -Rf /app/public/uploads
#ln -s /home/smartwash/uploads /app/public/uploads

# Start Apache with the right permissions after removing pre-existing PID file
rm -f /var/run/apache2/apache2.pid
service ssh start
# service cron start

#UNCOMMENT FOR ACTIVATING QUEUE CONSUMER!!!
#chmod +x docker/queue_consumer.sh
#exec docker/queue_consumer.sh &
php /var/www/html/bin/console assets:install
php /var/www/html/bin/console cache:clear
php /var/www/html/bin/console lexik:jwt:generate-keypair

chmod 775 /var/www/html/var/cache -Rf
chown www-data:www-data /var/www/html/var/cache -Rf
mkdir -p /home/uploads
sudo ln -s /home/uploads /var/www/html/public/uploads || true
chmod 775 /var/www/html/public/uploads
chown www-data:www-data /var/www/html/public/uploads
chmod +x /var/www/html/docker/apache/start_safe_perms

exec /var/www/html/docker/apache/start_safe_perms -DFOREGROUND