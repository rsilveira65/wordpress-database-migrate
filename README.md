# Wordpres Database Migrate
## About
A simple script to dump, replace and migrate Wordpress local database to remote one.

## Configuration
To configure the script, set the follow parameters:
```bash
.
└── src
    └── config
        └── parameters.yml
```

## Run
In your console, run:

```bash
composer install
```

```bash
php wordpress-database-migrate.php run:migrate
```


