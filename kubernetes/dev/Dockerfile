FROM webdevops/php-apache:ubuntu-16.04

RUN echo > /opt/docker/bin/service.d/dnsmasq.d/10-init.sh && \
    echo > /opt/docker/etc/supervisor.d/dnsmasq.conf && \
    echo > /opt/docker/bin/service.d/dnsmasq.sh

RUN apt-get -y update && \
    ACCEPT_EULA=Y apt-get -y -o Dpkg::Options::=--force-confdef -o Dpkg::Options::=--force-confnew install \
             ssmtp \
             sudo && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

COPY kubernetes/dev/ssmtp.conf /etc/ssmtp/ssmtp.conf
RUN echo 'sleep 20 && sudo -u application php /app/artisan migrate --force >> /var/log/migration.log || true' > /postrun.sh

WORKDIR /app
COPY . /app

RUN chown -R application:www-data /app
