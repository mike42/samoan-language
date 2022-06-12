FROM debian:bullseye

RUN DEBIAN_FRONTEND=noninteractive apt-get update && apt-get install -y \
    apache2 \
    curl \
    git \
    libapache2-mod-php \
    net-tools \
    openssl \
    php \
    php-cli \
    php-mbstring \
    php-mysql \
    wget

COPY maintenance/docker/sm-language-web.conf /etc/apache2/sites-available/sm-language-web.conf

RUN a2dissite 000-default && \
    a2ensite sm-language-web && \
    a2enmod ssl && \
    a2enmod rewrite && \
    rm /var/www/html/index.html

EXPOSE 80
EXPOSE 443
CMD apachectl -D FOREGROUND
