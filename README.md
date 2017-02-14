# silid
A Meeting Room Booking System

<img src="https://raw.githubusercontent.com/warrenca/silid/master/public/images/silid-screenshot.png">

# Requirements
- MySQL with already made database, correct user permission and password.
- PHP v6
- Composer
- SMTP Server

# Run locally
```
$ git clone @github.com:warrenca/silid.git
$ cd silid
$ php composer.phar install
$ cp .env.example .env
# Fill all the env values!!!
$ php artisan migrate
$ php artisan db:seed
$ php -S localhost:8000 -t public
```

# With Docker locally
Run the following command, if successful you should be able to open http://localhost:8000 or the value you set in SILID_HOSTNAME env. DB_HOST is your host computer IP if you're running inside docker.
```
$ docker build -t warrenca/silid . && docker stop silid && docker rm silid && docker run --env-file=.env -p 8000:80 --name=silid -d warrenca/silid
```

# Todo
- Create compose file to include MySQL
- Test more
