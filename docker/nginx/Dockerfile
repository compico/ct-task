FROM nginx:alpine

RUN apk add --no-cache libcap

RUN addgroup -g 82 www-data 2>/dev/null || true \
    && adduser -u 1000 -G www-data -s /bin/sh -D appuser 2>/dev/null || true

RUN mkdir -p /var/cache/nginx /var/log/nginx /app/run \
    && chown -R appuser:www-data /var/cache/nginx /var/log/nginx /app/run \
    && chmod -R 755 /var/cache/nginx /var/log/nginx /app/run

RUN rm /etc/nginx/conf.d/default.conf
RUN rm /etc/nginx/nginx.conf

COPY --chown=appuser:www-data docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY --chown=appuser:www-data docker/nginx/default.conf /etc/nginx/conf.d/default.conf

RUN setcap 'cap_net_bind_service=+ep' /usr/sbin/nginx

