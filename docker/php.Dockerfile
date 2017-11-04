FROM webdevops/php-apache:ubuntu-16.04

RUN apt-get -y update && \
    ACCEPT_EULA=Y apt-get -y -o Dpkg::Options::=--force-confdef -o Dpkg::Options::=--force-confnew install ssmtp php-pgsql && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

COPY kubernetes/dev/ssmtp.conf /etc/ssmtp/ssmtp.conf
