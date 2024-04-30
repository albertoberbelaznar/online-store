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


## Inventory: Available endpoint examples

### Add product to inventory
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

### Remove product from inventory
```
curl --location --request DELETE 'http://localhost:8001/v1/product/46b238c6-2368-48dc-8de3-7c1e985d10d4'
```

### Get products in inventory
```
curl --location --request GET 'http://localhost:8001/v1/products'
```

## Shopping cart: Available endpoint examples

### Add item to shopping cart
```
curl --location --request PUT 'http://localhost:8001/v1/user/6dcaa98f-e0f9-48da-9750-468c071208f7/cart/ec5cf87e-c738-462c-8241-0780287ec4bf/item/11bb7111-508d-4fb9-982b-9d0586c5076f' \
--header 'Content-Type: application/json' \
--data-raw '{
    "product_id": "2fd839bf-d5f9-498a-9e98-3346cae278d4",
    "amount": 40
}'
```

### Get user shopping cart
```
curl --location --request GET 'http://localhost:8001/v1/user/6dcaa98f-e0f9-48da-9750-468c071208f7/cart/ec5cf87e-c738-462c-8241-0780287ec4bf'
```

### Calculate shopping cart price
```
curl --location --request GET 'http://localhost:8001/v1/user/6dcaa98f-e0f9-48da-9750-468c071208f7/cart/ec5cf87e-c738-462c-8241-0780287ec4bf/price'
```