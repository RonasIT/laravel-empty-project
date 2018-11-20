FROM webdevops/php-nginx-dev:7.1

RUN apt-get clean && \
    apt-get -y update && \
    apt-get -y install ssmtp sudo libpq-dev libsodium-dev libmagickwand-dev libmagickcore-dev && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN echo > /opt/docker/bin/service.d/dnsmasq.d/10-init.sh && \
    echo > /opt/docker/etc/supervisor.d/dnsmasq.conf && \
    echo > /opt/docker/bin/service.d/dnsmasq.sh

RUN docker-php-ext-install pgsql pdo_pgsql && \
    pecl install libsodium-2.0.7 && \
    echo 'yes' | pecl install imagick && \
    echo "extension=/usr/local/lib/php/extensions/no-debug-non-zts-20160303/sodium.so" >> /usr/local/etc/php/conf.d/sodium.ini && \
    echo "extension=/usr/local/lib/php/extensions/no-debug-non-zts-20160303/imagick.so" >> /usr/local/etc/php/conf.d/imagick.ini

COPY docker/20-xdebug.ini /usr/local/etc/php/conf.d/20-xdebug.ini