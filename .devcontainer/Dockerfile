# .devcontainer/Dockerfile
FROM mcr.microsoft.com/vscode/devcontainers/php:8.2

# Install system dependencies
RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \
    && apt-get -y install --no-install-recommends \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_pgsql \
    bcmath \
    gd \
    zip

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# [Optional] Set the default user. Omit if you want to keep the default as root.
ARG USER_UID=1000
ARG USER_GID=$USER_UID
RUN if [ "$USER_UID" -ne "0" ]; then \
    groupadd --gid $USER_GID vscode \
    && useradd --uid $USER_UID --gid $USER_GID -m vscode \
    && apt-get install -y sudo \
    && echo vscode ALL=\(ALL\) NOPASSWD:ALL > /etc/sudoers.d/vscode \
    && chmod 0440 /etc/sudoers.d/vscode; \
    fi

# Switch to the non-root user
USER vscode
