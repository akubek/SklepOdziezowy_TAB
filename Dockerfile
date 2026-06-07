# Używamy tego samego obrazu bazowego, co wcześniej
FROM php:8.5-cli

# Instalujemy wymagane pakiety bazy danych na etapie BUDOWANIA obrazu
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    # Czyszczenie cache apt, aby zmniejszyć wagę obrazu
    && apt-get clean && rm -rf /var/lib/apt/lists/*