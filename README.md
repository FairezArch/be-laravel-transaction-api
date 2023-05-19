- [Configuration](#configuration)
- [Collection](#collection)
  - [Import](#import)
  - [Environment](#environment)
- [Running](#running)
- [Test](#test)

## Configuration

Select folder `backend`

```
$ cd backend
```

- Setup .env file
  - DB_DATABASE
  - DB_USERNAME
  - DB_PASSWORD

- Run command artisan migrate and seeder.

```
$ php artisan migrate --seed
```


## Collection
Open the folder `Collection` in this project, And will see two files:
- `thunder-collection_api-transaction_postman.json` for list API.
- `thunder-environment_transaction_postman.json` for env of API.

### Import

Open the `Postman`.
- Click menu collection then click `import` -> select `files` -> select `thunder-collection_api-transaction_postman.json`.
- Click menu environment then click `import` -> select `files` -> select `thunder-environment_transaction_postman.json`.
- For the collection select environment `transaction`.

### Environment

In the `transaction` environment, there are two variables: 
- `base_url`, for set url of project, example after running artisan serve the url that be `http://127.0.0.1:8000/api` because run for api.
- `token`, token after login.

## Running

Running command artisan serve

```
$ php artisan serve
```

## Test

In this project also set unit test and for running test please running command:

```
$ php artisan test
```