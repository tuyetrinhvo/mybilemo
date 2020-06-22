# mybilemo


## Tests with Postman :

### Get New Access token in Postman :

####  Make POST request with 'bilemo.ttvo.fr/oauth/v2/token'

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

#### 1.Get products's list : Make GET request with 'bilemo.ttvo.fr/api/products'

On the 'authorization', choose 'Bearer token', and copy-paste the access token.

#### 2.Show a product : Make GET request with 'bilemo.ttvo.fr/api/products/{id}'

#### 3.Create a new product : Make POST request with 'bilemo.ttvo.fr/api/products/'
On the 'body tab', check 'raw', 'JSON' and inquire:

    {
       "name": "NomDuProduit",
       "description": "DescriptionDuProduit",
       "brand": "Marque"
       "price": "Prix"
    }

#### 3.Create a new user : Make POST request with 'bilemo.ttvo.fr/api/users/'
On the 'body tab', check 'raw', 'JSON' and inquire:

    {
       "username": "YourUsername",
       "email": "YourEmail",
       "password": "YourPassword"
    }

#### 4.Get user's list : Make GET request with 'bilemo.ttvo.fr/api/users'

#### 5.Show a user : Make GET request with 'bilemo.ttvo.fr/api/users/{id}'

#### 6.Delete a user : Make DELETE request with 'bilemo.ttvo.fr/api/users/{id}'

---------------

### Use the refresh token

#### Make POST request with 'bilemo.ttvo.fr/oauth/v2/token'

On the 'body tab', check 'raw', 'JSON' and inquire:

    {
       "grant_type": "refresh_token",
       "client_id": "YourClientId",
       "client_secret": "YourClientSecret",
       "refresh_token": "YourRefreshToken"
    }

--------------

# Documentation

#### You will find the documentation at 'bilemo.ttvo.fr/api/doc'

--------------

### TuyetrinhVO
