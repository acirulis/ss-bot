FROM debian:bullseye-slim
LABEL maintainer="andis.cirulis@whitedigital.eu"

# Some general stuff & nginx

RUN apt update \
&& apt -y upgrade \
&& apt -y install curl wget build-essential apt-transport-https software-properties-common tzdata unzip bzip2 cron vim git tmux lsb-release ca-certificates nginx \
# Set Europe/Riga timezone
&& ln -fs /usr/share/zoneinfo/Europe/Riga /etc/localtime && dpkg-reconfigure -f noninteractive tzdata

# PHP 8.1
RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg \
&& echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list \
&& apt update \
&& apt-get install -y php8.1-fpm \
    && apt-get install -y php8.1-mbstring php8.1-gd php8.1-bcmath php8.1-zip php8.1-xml php8.1-curl php8.1-intl php8.1-pgsql php8.1-redis php8.1-xdebug


# # forward request and error logs to docker log collector
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
 	&& ln -sf /dev/stderr /var/log/nginx/error.log

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

# Install NodeJs & Yarn
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
&& apt install -y nodejs \
&& curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
&& echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
&& apt update && apt install yarn

# Install Symfony binary
RUN wget https://get.symfony.com/cli/installer -O - | bash \
&&  mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# #Expose http, https
EXPOSE 80 443

CMD /root/startup.sh