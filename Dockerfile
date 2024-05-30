FROM php:8.2-cli
USER root
RUN docker-php-ext-install sockets
RUN mkdir /data
WORKDIR /src
CMD [ "php", "./owg.php" ]