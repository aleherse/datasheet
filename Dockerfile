FROM php:7.1

RUN apt-get update && apt-get install -yq vim zip git && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer-bin

WORKDIR /code
VOLUME /code

CMD tail -f /dev/null
