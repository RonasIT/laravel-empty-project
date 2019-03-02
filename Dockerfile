FROM ronasit/php-nginx:7.3

ENV WEB_DOCUMENT_ROOT /app/public
ENV WEB_DOCUMENT_INDEX index.php

WORKDIR /app
COPY . /app
RUN chown -R application:www-data /app
USER application
