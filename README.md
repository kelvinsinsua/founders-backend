# FoundersBackend

FoundersTest is an API to create reminders.

## Requirements

* PHP 8.2
* composer 2.*
* sqlite3
* symfony-cli

## Installation

```bash
git clone https://github.com/kelvinsinsua/founders-backend.git
```
```bash
cd founders-backend
```
Install dependencies.
```bash
composer install
```
Create database.
```bash
php bin/console doctrine:database:create
```
Excecute migrations
```bash
php bin/console doctrine:migrations:migrate
```
Run server
```bash
symfony serve
```


## Cron Job

Excecute manually:
```bash
php bin/console app:reminders
```

Automatically:
```bash
0 0 * * * php /<your_directory>/founders-backend/bin/console app:reminders
```



## Aditional info

Server url:

http://localhost:8000

Documentation:

http://localhost:8000/documentation



## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](https://choosealicense.com/licenses/mit/)