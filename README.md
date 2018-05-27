# mybilemo

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/ae8e4c58-c05d-46ea-9d00-8fe10a116d7e/small.png)](https://insight.sensiolabs.com/projects/ae8e4c58-c05d-46ea-9d00-8fe10a116d7e)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c4a56ec3b4884c91bbf43b97e164fea5)](https://www.codacy.com/app/tuyetrinhvo/mybilemo?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=tuyetrinhvo/mybilemo&amp;utm_campaign=Badge_Grade)

## Create an API with Symfony.


# Installation

## Use the command :

    php bin/console doctrine:database:create

    php bin/console doctrine:schema:update --force

    php bin/console doctrine:fixtures:load

    php bin/console fos:user:create

    php bin/console create:new:client

    php bin/console server:run


-------------------

## Tests with Postman :

### Get New Access token in Postman :

####  Make POST request with '127.0.0.1:8000/oauth/v2/token' 
##### (or 'bilemo.ttvo.fr/oauth/v2/token')

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

--------------

### Connect to the Api with this access token

#### 1.Get products's list : Make GET request with '127.0.0.1:8000/products'
                                               (or 'bilemo.ttvo.fr/products')

##### On the 'authorization', choose 'Bearer token', and copy-paste the access token.

#### 2.Show a product : Make GET request with '127.0.0.1:8000/products/{id}'
                                          (or 'bilemo.ttvo.fr/products/{id}')

#### 3.Create a new user : Make POST request with '127.0.0.1:8000/users'
                                              (or 'bilemo.ttvo.fr/users')

##### On the 'body tab', check 'raw', 'JSON' and inquire:

    {
       "username": "YourUsername",
       "email": "YourEmail",
       "password": "YourPassword"
    }

#### 4.Get user's list : Make GET request with '127.0.0.1:8000/users'
                                           (or 'bilemo.ttvo.fr/users')

#### 5.Show a user : Make GET request with '127.0.0.1:8000/users/{id}'
                                       (or 'bilemo.ttvo.fr/users/{id}')

#### 6.Delete a user : Make DELETE request with '127.0.0.1:8000/users/{id}'
                                            (or 'bilemo.ttvo.fr/users/{id}')

---------------

### Use the refresh token

#### Make POST request with '127.0.0.1:8000/oauth/v2/token'
##### (or 'bilemo.ttvo.fr/oauth/v2/token')

On the 'body tab', check 'raw', 'JSON' and inquire:

    {
       "grant_type": "refresh_token",
       "client_id": "YourClientId",
       "client_secret": "YourClientSecret",
       "refresh_token": "YourRefreshToken"
    }

--------------

# Documentation

#### You will find the documentation at '127.0.0.1:8000/api/doc'
##### (or 'bilemo.ttvo.fr/api/doc')

--------------

### TuyetrinhVO
