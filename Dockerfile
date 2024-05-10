# Используем официальный образ PHP
FROM php:7.4-apache

# Устанавливаем расширение mysqli
RUN docker-php-ext-install mysqli
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
# Копируем исходный код приложения в контейнер
COPY . /var/www/html
COPY images/ /var/www/html

# Запускаем Apache внутри контейнера
CMD ["apache2ctl", "-D", "FOREGROUND"]