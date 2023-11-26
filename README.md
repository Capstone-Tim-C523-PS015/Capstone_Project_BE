## Cara install

-   Clone this repository

```sh
git clone https://github.com/IlhamM3/Capstone_Project.git
# make sure you are in the folder
cd (folder yang sudah diclone tadi)
```

-   Install semua package

```sh
composer install
```

-   Copy/rename file .env.example to .env

```sh
# windows
copy .env.example .env

```

```sh
php artisan jwt:secret
php artisan migrate:fresh --seed
```

- Menjalankan web
```sh
php -S 127.0.0.1:8000 -t public
```
-   And go to http://localhost:8000/

## Cara refresh branch local vsc
```sh
git fetch
```
jika masih sering terjadi commit jalankan perintah
```sh
git pull
```
