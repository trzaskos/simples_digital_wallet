
# Simple Wallet

This is a simple money wallet project.
The main idea is to carry out transactions between user wallets.

Users can be of two types: common_user or shopkeeper.
Only ordinary users can send and receive money. The shopkeeper type user can only receive but not send.




## Installation

After cloning the repository, run the following commands:

```bash
  cp .env.example .env
```
Configure your database data in the .env file.

Installation of required packages:
```bash
  composer install
  npm install
```

Here we will use Sail for the environment:
```bash
  ./vendor/bin/sail up -d
```

Database:
```bash
  ./vendor/bin/sail php artisan migrate
```
## API Reference

#### Create a new user

```http
  POST /api/user
```

Body:
| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | **Required** |
| `email` | `string` | **Required** and unique |
| `document_number` | `string` | **Required** and unique |
| `document_type` | `string` | **Required** CPF/CNPJ |
| `password` | `string` | **Required** |
| `role` | `string` | **Required** COMMON_USER/SHOPKEEPER |

A `token` will be returned for the created user. Store it, as it will be used in all other requests.

In the APIs below, all must have the following header:
| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `Accept`      | `string` | application/json |
| `Authorization`      | `string` | Token generated at user creation |

#### Get all registered users except the one belonging to the entered ID

```http
  GET /users/{id}
```

#### Create a new wallet for a user

```http
  POST /wallet
```

Body:
```json
{
    "name": "Wallet Name",
    "user_id": 1,
    "amount": "2300.00"
}
```

#### Get all user wallets

```http
  GET /wallets/{id}
```

#### Get a specific wallet

```http
  GET /wallet/{id}
```

#### Get a user's transactions

```http
  GET /transactions/{id}
```

#### Send money to another user

```http
  POST /send
```

Body:
```json
{
    "payer": 1,
    "payee": 2,
    "wallet_id": 1,
    "amount": "200.00",
    "description": "transaction description test"
}
```
