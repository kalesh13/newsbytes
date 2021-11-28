### Installation and testing

> Make sure that you have composer installed on your system.

Install all the vendor files using the command

```
composer install
```

After package installation, running the application is as easy as

```
php artisan serve
```

This will start a local server and you can access the application at `127.0.0.1:8000`. The UI is pretty much self explanatory.

Since database is already present in the git, migrations are there and database is pre-populated with some test data.

If you'd like to remove the test data from the database, run

```
php artisan migrate:fresh
```
This will drop all the existing tables and create them again, resulting in clean tables.


#### How to use the application

- Enter the original url on the input field and click the `Create tiny url` button. This will create a tiny url of the format `127.0.0.1:8000/marketing/aQbt9T`
- By default, generated urls can be accessed without any restriction. To restrict a url to be accessed only once, select the checkbox `Restrict to single use` before submitting the form.
- The recent ten urls can be found in the `Url History` section.
- A poller is attached to the history section, which calls the API every ten seconds, updating the table.
- The search box allows retrieval of tiny url by submitting the original url. The search box is not that user friendly now. You have to hit enter to perform a search (or have to wait till next polling) and to reset you have to completely remove all the text from the search box and hit enter. This can be made more user friendly by adding some buttons.
