FROM nginx:alpine

COPY public /var/www/public
ADD nginx/default.conf /etc/nginx/conf.d/default.conf
WORKDIR /var/www/