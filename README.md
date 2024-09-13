# nutricion-api

#Build docker
docker-compose build

#start the container
docker-compose up

#Environments del proyecto

Comprobar que el .env esté apuntando a prod cuando se realice el deploy.

# Command for local environment: composer dump-env local
# Command for prod environment: composer dump-env prod

#Cambio de entorno y poner permisos

Hacemos el comando para limpiar caché:

# php bin/console cache:clear

Nos dirigimos a var/cache y le ponemos los permisos con el siguiente comando a prod:

# chown www-data:www-data -Rf prod


php bin/console lexik:jwt:generate-keypair
php bin/console doctrine:schema:update --force