FROM php:8.2-apache

# Suppress ServerName warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Install SSL tooling
RUN apt-get update \
	&& apt-get install -y --no-install-recommends openssl \
	&& rm -rf /var/lib/apt/lists/*

# Enable mod_rewrite
RUN a2enmod rewrite

# Generate self-signed cert for localhost (dev only)
RUN mkdir -p /etc/ssl/private /etc/ssl/certs \
		&& openssl req -x509 -nodes -days 3650 -newkey rsa:2048 \
			-subj "/CN=localhost" \
			-keyout /etc/ssl/private/ssl-cert-snakeoil.key \
			-out /etc/ssl/certs/ssl-cert-snakeoil.pem

# Enable SSL and default HTTPS virtual host
RUN a2enmod ssl && a2ensite default-ssl

# Copy project files
COPY public/ /var/www/html/
COPY tests/ /var/www/tests/

# Set permissions
RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80
EXPOSE 443
