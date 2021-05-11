**Laravel docker**

The project must be mounted using docker:

- Start docker daemon

- Run your docker machine: docker-compose up -d
- From inside the machine:
  - Run: composer install
  - Copy the .env.example to .env
  - Add "APP_KEY=" to new .env
  - Run: php artisan key:generate
  - Run: php artisan migrate
  - Run: php artisan db:seed
- From outside the machine:
  - Run: composer install
  - Run: cp git-hooks/pre-commit .git/hooks/pre-commit
  - Run: chmod +x .git/hooks/pre-commit
- Web running on http://localhost:8088
- Api status check running on GET request: http://localhost:8088/api/status
- Swagger running on http://localhost:8082/
- Postman base collection file can be found on src/postman
- phpMyAdmin running on http://localhost:9191
  - user: root
  - password: secret
- As database we use MySQL:
  - database name: laravel
  - database user: root
  - database password: secret



- To stop de container:
  docker-compose stop

**Git rules**
- Commit syntax => git commit -m "[] - Your commit..."
- If there is a PHPCS Failed in a File, run: "./phpfixer"
- Create one branch for each issue => (git branch <new branch name> , git checkout <new branch name>)
- When an issue is closed => git commit -m "[#issueId] - Your commit..."

:)

