## IMS Version 2.0.0
This is the application code for the intial build of IMS v2.0.0.

### Build details
IMS 2.0 is built on the [Laravel Framework (v9)](https://laravel.com/docs/9.x). Auth scaffolding is provided by [Laravel Jetstream](https://jetstream.laravel.com/2.x/introduction.html), with the [Laravel Livewire](https://laravel-livewire.com/) stack.

Other technologies used include [Tailwind CSS](https://tailwindcss.com/docs/installation) and [Alpine.js](https://alpinejs.dev/start-here) for lightweight reactive frontend components.

Documentation which may be relevant to the packages used:
* [Tenancy For Laravel](https://tenancyforlaravel.com/)
* [Filament Table Builder](https://filamentphp.com/docs/2.x/tables/installation)
* [Filament Form Builder](https://filamentphp.com/docs/2.x/forms/installation)
* [Jetstream](https://jetstream.laravel.com/2.x/introduction.html)
* [Saloon](https://github.com/Sammyjo20/Saloon)

Together Tailwind, Alpine, Laravel and Livewire are referred to as the TALL stack. Relevant documentation is available through the links on each technology.

### Installation
* Compose the docker instance
  * using docker compose
  ```bash
  # to return control of the terminal use -d
  docker compose up -d --build
  # to monitor the connections use without, usually after the initial build
  docker compose up
  ```
* Copy the .env.example file to .env
  setup according to your installation.
  * the key will be generated in the next step
  * make sure the APP_* are all set per your env
  * when setting the APP_URL ignore the schema at the beginning. ie. ims.com.au, not https://ims.com.au
  * make sure the database setup (DB_*) is correct, creating one if needed
  * make sure the APIHUB_* variables are correct or the app will not be able to use APIHub. The ip you are using may also need to be whitelisted with APIHub in order for it to accept requests. If you are using a vpn that is set up and localhost it should work.

* Install dependencies 
  ```shell
  # install php dependencies
  composer install

  # generate laravel key, ONLY DO THIS THE FIRST TIME
  php artisan key:generate

  # install node dependencies and build dev assets
  npm install && npm run dev

  # migrate unmigrated database migrations.
  php artisan migrate
  ```
  Note that since registration is disabled, the initial install includes a default admin user and team creation via migration.
  It is recommended to change the users details after installation ASAP.
  ```md
  email: admin@mail.com
  password: supersecret
  ```

  Laravel will not work if the .env file is not present and containing all relevant details. See Laravel documentation for more details.
### Development
You may keep the frontend build up to date whilst developing by using the following command
```bash
npm run watch
```
This will automatically rebuild the frontend assets (js and css) based on changes so you may immediately see the results.

During development it may be necessary to completely wipe the database and do a fresh migration. In this case use the following command:
```bash
# if seeded data not desired
php artisan migrate:fresh
# if seeded data desired
php artisan migrate:fresh --seed
```
**Please note this will entirely wipe the database and reset everything to default values or seeded data if used. This will include user logins.**

Development dependencies are included to help with building, testing and debugging of the application. Note these are not used or available on production environments. These include:
* [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar)
* [Laravel IDE Helper](https://github.com/barryvdh/laravel-ide-helper)

### Issues
* using php artisan compose can create files with root:root permissions, navigate to the folder and use the following to fix as a workaround.
  ```bash
  sudo chown user:group filename
  ```
  This appears to be a result of using linux environments and docker, possible solution [here](https://github.com/aschmelyun/docker-compose-laravel/issues/62).
  
  **FIX**: If you attach the shell to vs code, you can use `su bitnami` to correct the issue for the rest of the session and ownership issues will not affect artisan generated/modified files. This is much preferable to the above approach, but the chown approach will be required if this is not done.

  The docker image for the development environment is stored in the Buroserv Github Repositories.
  Buroflow uses three subdomains for the "teams" access:
  admin.
  reseller.
  retailer.
  These will have to be added to the hosts or DNS.

## Test LOCIDs for Various technologies
These can be used to check SQ results for various technology types on the service. For a full range of test data refer to the Superloop Connect Sandbox Excel sheet.
### SQ Examples - Production Connect API
* FTTB Example: http://localhost:8000/superloop/location/qualification?locId=LOC000147614298
* FTTC Example: http://localhost:8000/superloop/location/qualification?locId=LOC000081462568
* FTTN(Upgradeable) Example: http://localhost:8000/superloop/location/qualification?locId=LOC000023006777
* FTTP Example: http://localhost:8000/superloop/location/qualification?locId=LOC000148569394
* FW Example: http://localhost:8000/superloop/location/qualification?locId=LOC000004071849
* HFC Example: http://localhost:8000/superloop/location/qualification?locId=LOC000177563089
* SAT Example: http://localhost:8000/superloop/location/qualification?locId=LOC000068562318

### SQ Examples - Sandbox Connect API
Note the LOCIDs all start similar to 3801...
* FTTN Example: http://localhost:8000/superloop/location/qualification?locId=LOC380100005601
