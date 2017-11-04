FROM webdevops/php-nginx:debian-9

RUN apt-get clean && \
    echo "deb http://mirror.optus.net/deb-multimedia/ stretch main non-free" > /etc/apt/sources.list.d/deb-multimedia.list && \
    apt-get update && \
    apt-get install -y --force-yes deb-multimedia-keyring && \
    apt-get update && \
    apt-get install -y ca-certificates \
                       curl \
                       apt-transport-https \
                       ffmpeg \
                       imagemagick \
                       graphicsmagick \
                       cron \
                       ssmtp \
                       php7.0-pgsql \
                       php-imagick \
                       phpunit && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN echo > /opt/docker/bin/service.d/dnsmasq.d/10-init.sh && \
    echo > /opt/docker/etc/supervisor.d/dnsmasq.conf && \
    echo > /opt/docker/bin/service.d/dnsmasq.sh

RUN composer config --global github-oauth.github.com 560fbbdd764fad094d181c8d10ce1aa44df50029

COPY kubernetes/dev/php/postrun.sh /
COPY kubernetes/dev/php/ssmtp.conf /etc/ssmtp/ssmtp.conf

COPY kubernetes/dev/php/crontab /etc/

RUN sed -i s/'autostart = false'/'autostart = true'/g /opt/docker/etc/supervisor.d/cron.conf && \
    chmod 600 /etc/crontab

WORKDIR /app

COPY composer.json /app/

RUN composer install --no-autoloader --no-scripts

COPY . /app

RUN ln -sf /storage/images /app/public/images && \
    cp .env.dev .env

RUN composer install && \
    chown -R application:www-data /app
