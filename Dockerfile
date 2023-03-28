FROM php:8.1-fpm-alpine

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apk update && apk add git

RUN docker-php-ext-install pdo pdo_mysql

RUN apk add zsh vim zsh-autosuggestions zsh-syntax-highlighting bind-tools openssh curl && \
    rm -rf /var/cache/apk/*
RUN sh -c "$(wget https://raw.github.com/robbyrussell/oh-my-zsh/master/tools/install.sh -O -)"
RUN echo "source /usr/share/zsh/plugins/zsh-syntax-highlighting/zsh-syntax-highlighting.zsh" >> ~/.zshrc && \
echo "source /usr/share/zsh/plugins/zsh-autosuggestions/zsh-autosuggestions.zsh" >> ~/.zshrc
