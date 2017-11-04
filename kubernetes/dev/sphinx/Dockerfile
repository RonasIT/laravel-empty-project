FROM debian:jessie

RUN echo "deb http://ftp.debian.org/debian jessie-backports main" >> /etc/apt/sources.list && \
    apt-get update && \
    apt-get -y -t jessie-backports install sphinxsearch supervisor cron && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY crontab /etc/
COPY sphinx.conf /etc/sphinxsearch/sphinx.conf

CMD /usr/bin/supervisord
