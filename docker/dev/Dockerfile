FROM debian:bullseye-slim
LABEL maintainer="andis.cirulis@whitedigital.eu"

# Some general stuff

RUN apt update \
&& apt -y upgrade \
&& apt -y install curl wget apt-transport-https software-properties-common tzdata unzip bzip2 vim git lsb-release ca-certificates \
# Set Europe/Riga timezone
&& ln -fs /usr/share/zoneinfo/Europe/Riga /etc/localtime && dpkg-reconfigure -f noninteractive tzdata

# PHP 8.1
RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg \
&& echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list \
&& apt update \
&& apt-get install -y php8.1-fpm \
    && apt-get install -y php8.1-mbstring php8.1-gd php8.1-bcmath php8.1-zip php8.1-xml php8.1-curl php8.1-intl php8.1-pgsql php8.1-xdebug

WORKDIR /root
ADD startup.sh ./
RUN chmod a+x startup.sh

ADD php-development.ini /etc/php/8.1/fpm/php.ini
ADD php-development.ini /etc/php/8.1/cli/php.ini

# Installing composer globally
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
&& php composer-setup.php \
&&  php -r "unlink('composer-setup.php');" \
&& mv composer.phar /usr/local/bin/composer

# Install Symfony binary
RUN wget https://get.symfony.com/cli/installer -O - | bash \
&&  mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# #Expose http, https
EXPOSE 80 443

CMD /root/startup.sh
