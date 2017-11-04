FROM webdevops/php-nginx:debian-9

RUN apt-get clean && \
    apt-get -y update && \
    apt-get -y install ssmtp php7.0-pgsql phpunit php-xdebug && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN echo > /opt/docker/bin/service.d/dnsmasq.d/10-init.sh && \
    echo > /opt/docker/etc/supervisor.d/dnsmasq.conf && \
    echo > /opt/docker/bin/service.d/dnsmasq.sh

COPY docker/nginx/crontab /etc/

RUN sed -i s/'autostart = false'/'autostart = true'/g /opt/docker/etc/supervisor.d/cron.conf && \
    chmod 600 /etc/crontab

COPY docker/nginx/ssmtp.conf /etc/ssmtp/
COPY docker/nginx/20-xdebug.ini /etc/php/7.0/cli/conf.d/