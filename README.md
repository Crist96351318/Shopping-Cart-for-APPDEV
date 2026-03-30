# Shopping-Cart-for-APPDEV

## Backend API Endpoints

All backend endpoints are in `/backend` and return JSON.

- `GET /backend/get_products.php` - List all products
- `POST /backend/registration.php` - Register a user (JSON body: `first_name`, `last_name`, `email`, `password`, `address`)
- `POST /backend/login.php` - Log in (JSON body: `email`, `password`)
- `POST /backend/add_to_cart.php` - Add product to cart (JSON body: `product_id`, `quantity`)
- `GET /backend/cart.php` - Get cart details
- `POST /backend/cart.php` - Add item to cart
- `PUT/PATCH /backend/cart.php` - Update quantity in cart (JSON body: `product_id`, `quantity`)
- `DELETE /backend/cart.php` - Remove item from cart (JSON body: `product_id`)
- `POST /backend/checkout.php` - Checkout cart (user must be logged in)
- `GET /backend/user.php` - Get current logged-in user info

## Quick frontend fetch example

```js
fetch('/backend/get_products.php')
  .then(resp => resp.json())
  .then(data => console.log(data.products));
```
