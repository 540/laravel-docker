#Crypto currency wallet API

**Based on <a href="https://github.com/540">540</a> / <a href="https://github.com/540/laravel-docker">laravel-docker</a> project.**

![Project Status Image](https://img.shields.io/github/workflow/status/MKoding/laravel-docker/Laravel%20Validation/develop?label=project%20status)
![Commit Activity Image](https://img.shields.io/github/commit-activity/m/MKoding/laravel-docker)
![Last Commit Image](https://img.shields.io/github/last-commit/MKoding/laravel-docker)

**Contributors of the project:**

![Project Contributors Image](https://contrib.rocks/image?repo=MKoding/laravel-docker)

***

##How to install the project
The project must be mounted using docker:

- Start docker daemon.
- Run your docker machine:
  - docker-compose up -d
- Enter to the docker machine:
  - docker exec -it laravel-php sh
- From inside the machine:
  - Run: composer install
  - Run: cp .env.example .env
  - Run: php artisan key:generate
  - Run: php artisan migrate
  - Run: php artisan db:seed
- Web running on http://localhost:8088
- Api status check running on GET request on http://localhost:8088/api/status
- Swagger running on http://localhost:8082/
- Postman base collection file can be found on src/postman
- phpMyAdmin running on http://localhost:9191
  - User: root
  - Password: secret
- As database we use MySQL:
  - Database name: laravel
  - Database user: root
  - Database password: secret
- To stop de container:
  - docker-compose stop

##How to colaborate with the project
###Install the pre-commit:
- From outside the machine:
  - Run: composer install
  - Run: cp git-hooks/pre-commit .git/hooks/pre-commit
  - Run: chmod +x .git/hooks/pre-commit
  
###Commit rules:
- Commit syntax:
  - git commit -m "[] - \<your commit here>"
- If there is a "PHPCS Failed in a File", run:
  - ./phpfixer
- Create one branch for each issue:
  - git checkout -b \<branch name here>
- If you want to merge your work with the develop branch open a pull request.
- When an issue is closed:
  - git commit -m "[#issueId] - \<your commit here>"

:)

