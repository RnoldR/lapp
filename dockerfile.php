FROM php:8.1-apache-bullseye

# add global servername for localhost, prevents an uncertain apache2
RUN printf 'ServerName 127.0.0.1\n' >> /etc/apache2/apache2.conf

# copy the vhost file that gives global access to /var/www/html
COPY init.php/vhost.conf /etc/apache2/sites-enabled/000-default.conf

# do an apt update and install non-interactive aids to prevent some warnings from apt
ENV DEBIAN_FRONTEND=noninteractive
RUN apt update 
RUN apt install -y apt-utils

# install certificates, probably not needed anymore
RUN apt install ca-certificates \
 && apt clean
  
# install Postgres PDO
RUN apt install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# ensure that the /var/www/html is owner:group of www-data
# appears not to work from within a container when /var/www/html is a bind mount
RUN mkdir -p /var/www/html

EXPOSE 80

CMD ["/bin/bash", "-c", "chown -R www-data:www-data /var/www/html ; /usr/sbin/apache2ctl -D FOREGROUND"]
