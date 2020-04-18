FROM alpine:3.11

ARG HOST_USER_ID
ARG HOST_GROUP_ID
ARG HOST_OS
ARG APPLICATION_ENV

# --- Install base packages --------------------------------------------------------------------------------------------
RUN	addgroup -S nginx && \
    adduser -S -D nginx -G nginx && \
    apk add --update --no-cache \
    shadow \
    openssl \
    openrc \
    sudo \
    nano \
    curl \
    git \
    nginx \
    sqlite \
    php7 \
    php7-fpm \
    php7-phar \
    php7-openssl \
    php7-opcache \
    php7-mbstring \
    php7-fileinfo \
    php7-session \
    php7-xdebug \
#	 php7-mysqli \
#	 php7-dom \
#	 php7-intl \
#	 php7-imap \
    php7-json \
    php7-xsl \
    php7-xml \
    php7-xmlwriter \
    php7-tokenizer \
    php7-curl \
    php7-gd

# --- Create directories -----------------------------------------------------------------------------------------------
VOLUME ["/var/www", "/etc/nginx/sites-enabled", "/etc/php7", "/run/nginx", "/run/php", "/var/mail"]

# --- Copy configurations ----------------------------------------------------------------------------------------------
COPY rootfs/ /

# --- Configure init system --------------------------------------------------------------------------------------------
RUN sed -i 's/#rc_sys=""/rc_sys="lxc"/g' /etc/rc.conf && \
# Tell openrc loopback and net are already there, since docker handles the networking
    echo 'rc_provide="loopback net"' >> /etc/rc.conf && \
# no need for loggers
    sed -i 's/^#\(rc_logger="YES"\)$/\1/' /etc/rc.conf && \
# can't get ttys unless you run the container in privileged mode
    sed -i '/tty/d' /etc/inittab && \
# can't set hostname since docker sets it
    sed -i 's/hostname $opts/# hostname $opts/g' /etc/init.d/hostname && \
# can't mount tmpfs since not privileged
    sed -i 's/mount -t tmpfs/# mount -t tmpfs/g' /lib/rc/sh/init.sh && \
# can't do cgroups
    sed -i 's/cgroup_add_service /# cgroup_add_service /g' /lib/rc/sh/openrc-run.sh && \
# clean apk cache
    rm -rf /var/cache/apk/* && \
# add nginx as a service
    rc-update add nginx default && \
# add php-fpm as a service
    rc-update add php-fpm7 && \
# install composer
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

RUN if [[ "$HOST_OS" == "Linux" ]]; then \
# add group for developers
    groupadd -g ${HOST_GROUP_ID} dev && \
# add user and add him to developers group
    useradd -u ${HOST_USER_ID} dev -g dev -s /bin/sh -p $(openssl passwd -1 "dev") -m  && \
# let dev user do sudo
    echo -n $'dev ALL=(ALL) ALL' >> /etc/sudoers \
; fi

# --- Working directory is our application root ------------------------------------------------------------------------
WORKDIR /var/www

# --- Start NGINX in a foreground process ------------------------------------------------------------------------------
CMD ["/sbin/init"]