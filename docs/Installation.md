# Installation

## Requirements
## Install
### Prepare databases
- Primary database

```DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=xcrawler_dev
DB_USERNAME=root
DB_PASSWORD=root
```

- Telescope database

```TELESCOPE_DB_CONNECTION=telescope
TELESCOPE_DB_HOST=127.0.0.1
TELESCOPE_DB_PORT=3306
TELESCOPE_DB_DATABASE=xcrawler_dev_telescope
TELESCOPE_DB_USERNAME=root
TELESCOPE_DB_PASSWORD=N6sPZhjEcr2K8x44
TELESCOPE_ENABLE_ALL=true
```

- You may need prepare `cache` database first

### Composer
- `composer install`
- `php artisan migrate`
- Setup [Horizon](https://laravel.com/docs/8.x/horizon)

`php artisan horizon:install`

- Setup [Telescope](https://laravel.com/docs/8.x/telescope)

`php artisan telescope:install`

### Supervisor
- Setup cron job for [Scheduling](https://laravel.com/docs/8.x/scheduling)

### Email
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
- Update Horizon as your needed
```
HORIZON_DEFAULT_MAX_PROCESSES=10
HORIZON_CRAWLING_MAX_PROCESSES=4
HORIZON_API_MAX_PROCESSES=6
HORIZON_MEMORY=4096
HORIZON_TRIES=5
```
- Slack with 2 routes: `SLACK_NOTIFICATIONS` for general purpose and `JAV_SLACK_NOTIFICATIONS` for JAV only  
