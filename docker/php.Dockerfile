FROM webdevops/php-nginx:7.1

RUN apt-get clean && \
    echo "deb http://httpredir.debian.org/debian jessie-backports main contrib non-free" >> /etc/apt/sources.list && \
    apt-get -y update && \
    apt-get -y install ssmtp sudo && \
    apt-get -t jessie-backports install -y libsodium-dev libmagickwand-dev libmagickcore-dev && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN echo > /opt/docker/bin/service.d/dnsmasq.d/10-init.sh && \
    echo > /opt/docker/etc/supervisor.d/dnsmasq.conf && \
    echo > /opt/docker/bin/service.d/dnsmasq.sh

RUN pecl install libsodium-2.0.7 && \
    echo 'yes' | pecl install imagick && \
    echo "extension=/usr/local/lib/php/extensions/no-debug-non-zts-20160303/sodium.so" >> /usr/local/etc/php/conf.d/sodium.ini && \
    echo "extension=/usr/local/lib/php/extensions/no-debug-non-zts-20160303/imagick.so" >> /usr/local/etc/php/conf.d/imagick.ini
