# Online store

## Installation

### Set up local environment

1. Copy `.env.example` file to `.env` in order to set local environment variables.


2. Run the following commands:
    ```
    docker compose -f docker-compose.yml up -d --build
    docker exec -it online_store php composer.phar install
    ```


3. Create database tables
    ```
    docker exec -it online_store php bin/console doctrine:migrations:migrate
    ```

### Implemented code validation   

1. Run `cs-fix` and `stan` to validate implemented code 
   ```
    docker exec -it online_store php composer.phar csstan
    ```
   
2. Run example test
   ```
   docker exec -it online_store php bin/phpunit --configuration /var/www/phpunit.xml.dist
   ```

## Inventory: Available endpoint examples

### Add product to inventory
#### Example request
```
curl --location --request PUT 'http://localhost:8001/v1/product/46b238c6-2368-48dc-8de3-7c1e985d10d4' \
--header 'Content-Type: application/json' \
--data-raw '{
    "name": "Refresco Limón Zero",
    "price": 0.75,
    "type": 1,
    "amount": 125
}'
```
#### Example wrong request (missing name param)
```
curl --location --request PUT 'http://localhost:8001/v1/product/46b238c6-2368-48dc-8de3-7c1e985d10d4' \
--header 'Content-Type: application/json' \
--data-raw '{
    "price": 0.75,
    "type": 1,
    "amount": 125
}'
```
#### Example wrong request response
```json
{
    "code": "Online\\Store\\Shared\\Domain\\Exception\\InternalErrorException",
    "message": "Missing params: name"
}
```

### Remove product from inventory
#### Example request
```
curl --location --request DELETE 'http://localhost:8001/v1/product/46b238c6-2368-48dc-8de3-7c1e985d10d4'
```

### Get products in inventory
#### Example request
```
curl --location --request GET 'http://localhost:8001/v1/products'
```
#### Example response
```json
[
   {
      "id":"0504ccd6-18b1-4842-863a-4ed0f3752d3d",
      "name":"Refresco Naranja",
      "price":0.69,
      "type":1,
      "amount":95,
      "created_at":"2024-04-28 22:27:50",
      "updated_at":"2024-04-28 22:28:00"
   },
   {
      "id":"2fd839bf-d5f9-498a-9e98-3346cae278d4",
      "name":"Refresco Cola",
      "price":0.85,
      "type":1,
      "amount":150,
      "created_at":"2024-04-28 22:26:56",
      "updated_at":"2024-04-28 22:27:34"
   },
   {
      "id":"3a30ccaa-c286-43ae-a449-88a8ea0900aa",
      "name":"Refresco Lim\u00f3n",
      "price":0.69,
      "type":1,
      "amount":105,
      "created_at":"2024-04-28 22:28:20",
      "updated_at":null
   },
   {
      "id":"b7fd7a87-d257-4e4f-a7e5-ccb306ec3471",
      "name":"Patatas",
      "price":0.99,
      "type":2,
      "amount":1.5,
      "created_at":"2024-04-28 22:31:41",
      "updated_at":null
   }
]
```

## Shopping cart: Available endpoint examples

### Add item to shopping cart
#### Example request
```
curl --location --request PUT 'http://localhost:8001/v1/user/6dcaa98f-e0f9-48da-9750-468c071208f7/cart/ec5cf87e-c738-462c-8241-0780287ec4bf/item/11bb7111-508d-4fb9-982b-9d0586c5076f' \
--header 'Content-Type: application/json' \
--data-raw '{
    "product_id": "2fd839bf-d5f9-498a-9e98-3346cae278d4",
    
    "amount": 40
}'
```

### Get user shopping cart
#### Example request
```
curl --location --request GET 'http://localhost:8001/v1/user/6dcaa98f-e0f9-48da-9750-468c071208f7/cart/ec5cf87e-c738-462c-8241-0780287ec4bf'
```
#### Example response
```json
{
   "id": "ec5cf87e-c738-462c-8241-0780287ec4bf",
   "user_id": "6dcaa98f-e0f9-48da-9750-468c071208f7",
   "created_at": "2024-04-29 23:16:06",
   "updated_at": "2024-04-30 07:14:47",
   "item_list": [
      {
         "id": "0ecee8a7-c660-4105-b2f3-751dd846dfc4",
         "shopping_cart_id": "ec5cf87e-c738-462c-8241-0780287ec4bf",
         "product": {
            "id": "3a30ccaa-c286-43ae-a449-88a8ea0900aa",
            "name": "Refresco Limón",
            "price": 0.69,
            "type": 1,
            "amount": 105,
            "created_at": "2024-04-28 22:28:20",
            "updated_at": null
         },
         "price": 34.5,
         "amount": 50,
         "created_at": "2024-04-29 23:22:54",
         "updated_at": "2024-04-30 07:14:47"
      },
      {
         "id": "11bb7111-508d-4fb9-982b-9d0586c5076f",
         "shopping_cart_id": "ec5cf87e-c738-462c-8241-0780287ec4bf",
         "product": {
            "id": "2fd839bf-d5f9-498a-9e98-3346cae278d4",
            "name": "Refresco Cola",
            "price": 0.85,
            "type": 1,
            "amount": 150,
            "created_at": "2024-04-28 22:26:56",
            "updated_at": "2024-04-28 22:27:34"
         },
         "price": 34,
         "amount": 40,
         "created_at": "2024-04-30 07:14:47",
         "updated_at": null
      },
      {
         "id": "a2eda731-494e-4a7e-ba86-ef4426adcbc9",
         "shopping_cart_id": "ec5cf87e-c738-462c-8241-0780287ec4bf",
         "product": {
            "id": "b7fd7a87-d257-4e4f-a7e5-ccb306ec3471",
            "name": "Patatas",
            "price": 0.99,
            "type": 2,
            "amount": 1.5,
            "created_at": "2024-04-28 22:31:41",
            "updated_at": null
         },
         "price": 0.792,
         "amount": 0.8,
         "created_at": "2024-04-29 23:16:06",
         "updated_at": "2024-04-30 07:14:47"
      }
   ]
}
```

### Calculate shopping cart price
#### Example request
```
curl --location --request GET 'http://localhost:8001/v1/user/6dcaa98f-e0f9-48da-9750-468c071208f7/cart/ec5cf87e-c738-462c-8241-0780287ec4bf/price'
```
#### Example response
```json
{
    "shopping_cart_id": "ec5cf87e-c738-462c-8241-0780287ec4bf",
    "price": 69.292
}
```