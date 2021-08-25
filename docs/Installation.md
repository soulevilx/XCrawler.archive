# Installation

## Requirements
## Install
- Setup .env
- `composer install`
- `php artisan migrate`
- Setup [Horizon](https://laravel.com/docs/8.x/horizon)
- Setup [Telescope](https://laravel.com/docs/8.x/telescope)
Telescope will use different database with Primary
```
  TELESCOPE_DB_CONNECTION=telescope
  TELESCOPE_DB_HOST=127.0.0.1
  TELESCOPE_DB_PORT=3306
  TELESCOPE_DB_DATABASE=telescope
  TELESCOPE_DB_USERNAME=root
  TELESCOPE_DB_PASSWORD=root
```
- Setup cron job for [Scheduling](https://laravel.com/docs/8.x/scheduling)

## Email
- Edit .env and provide Email configuration
```
MAIL_DRIVER=smtp
MAIL_HOST=smtp.googlemail.com
MAIL_PORT=465
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=ssl
MAIL_TO_ADDRESS=// Your WordPress email for posting
MAIL_TO_NAME="Viet Vu"
MAIL_FROM_ADDRESS=soulevilx@gmail.com
MAIL_FROM_NAME="Viet Vu"
```
