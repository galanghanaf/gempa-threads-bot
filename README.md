# Gempa Threads Bot

This system using <a href="https://github.com/galanghanaf/php-mvc-framework">PHP MVC Framework</a> with PHP 8.1.10 and NodeJS 18.18.0

## Configuration

- Setup Database & URL `app/config/Config.php`

```
define("BASE_URL", "http://localhost/gempa-threads-bot/");
define("DB_HOST", "localhost");
define("DB_USER", "username");
define("DB_PASS", "password");
define("DB_NAME", "tbl_earthquake");
```

- Setup Threads Bot

```
cd app/bot
```

```
npm install
```

- Rename `app/bot/.env.example` to `app/bot/.env`
- Fill Username & Password Threads (Instagram) `app/bot/.env`

```
PORT="your_port"
IG_USERNAME="your_username"
IG_PASSWORD="your_password"
```
