FROM php:7.2-apache

RUN apt-get update && apt-get install -y git

WORKDIR /var/www

COPY . .

RUN git clone --branch 1.1.32 https://github.com/planetteamspeak/ts3phpframework
