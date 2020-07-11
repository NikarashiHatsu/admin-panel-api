## About Admin Panel API
This project is used for private project which need an Admin Dashboard. I make this to provide their API needs. Made with Laravel Passport which support fully OAuth2 implementation, the whole idea of building this is just perfection.

## How to Install
1. Clone this repository.
2. Go to the project's directory and run `composer install` to install all dependencies Laravel needs to use.
3. Run `php artisan migrate` to migrate the databases. The Passport service provider registers its own database migration directory with the framework.
4. Run `php artisan passport:install` to install Laravel Passport. This command will create the encryption keys needed to generate secure access tokens. In addition, the command will create "personal access" and "password grant" clients which will be used to generate access tokens.
5. You're basically done.

### Notes
- This application is far beyond perfect, since it's the first time for me to use OAuth2 implementation. So expect bugs and errors. Please give me a report if you find some.
- The API documentation is available [here](https://documenter.getpostman.com/view/9584289/T17Ge7Nf?version=latest).
- More documentation on how to install will be informed later on.