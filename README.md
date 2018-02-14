# mybilemo

## Create an API with Symfony.

-----------------
# Installation

### Use the command :

php bin/console doctrine:database:create

php bin/console doctrine:schema:update --force

php bin/console doctrine:fixtures:load

php bin/console fos:user:create

php bin/console create:new:client

php bin/console server:run
(You will see Server listening on http://127.0.0.1:8000)

-------------------
### Test with Postman :

#### Get New Access token in Postman :

##### Make a request POST with http://127.0.0.1:8000/oauth/v2/token

On the 'body tab', check 'raw', 'JSON' and inquire:

    {
      "grant_type": "password",
      "client_id": "YourClientId",
      "client_secret": "YourCLientSecret",
      "username": "YourUsername",
      "password": "YourPassword"
    }

You will receive an access token and a refresh token.
Your access token expires after one hour !

#### Connect to the Api with this access token

##### Get products's list : Make the request GET with http://127.0.0.1:8000/products

On the 'authorization', choose 'Bearer token', and copy-paste the access token.

##### Show a product : Make the request GET with http://127.0.0.1:8000/products/{id}

##### Create a new user : Make the request POST with http://127.0.0.1:8000/users
On the 'body tab', check 'raw', 'JSON' and inquire:

    {
       "username": "YourUsername",
       "email": "YourEmail",
       "password": "YourPassword"
    }

##### Get user's list : Make the request GET with http://127.0.0.1:8000/users

##### Show a user : Make the request GET with http://127.0.0.1:8000/users/{id}

##### Delete a user : Make the request DELETE with http://127.0.0.1:8000/users/{id}

#### Use the refresh token

##### Make a request POST with http://127.0.0.1:8000/oauth/v2/token

On the 'body tab', check 'raw', 'JSON' and inquire:

    {
       "grant_type": "refresh_token",
       "client_id": "YourClientId",
       "client_secret": "YourClientSecret",
       "refresh_token": "YourRefreshToken"
    }

--------------

# Documentation

#### You will find the documentation at http://127.0.0.1:8000/api/doc

--------------

### TuyetrinhVO