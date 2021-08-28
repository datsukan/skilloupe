FROM amazonlinux:2

RUN yum -y update

RUN ln -sf /usr/share/zoneinfo/Asia/Tokyo /etc/localtime

RUN amazon-linux-extras install php7.3 -y && \
    yum -y install php-pdo \
    php-mbstring \
    php-xml \
    php-bcmath \
    php-mysqlnd \
    php-pear \
    php-devel \
    gcc \
    make

RUN pecl install xdebug

RUN yum clean all

RUN ln -sf /dev/stdout /var/log/php-fpm/access.log && \
    ln -sf /dev/stderr /var/log/php-fpm/error.log

EXPOSE 9000
CMD ["php-fpm", "--nodaemonize"]
