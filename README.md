# User Management API (Symfony 4)

## Setup
Assuming you have docker installed, cd into the cloned directory and run the following commands:

* `sudo docker-compose up -d`
* `sudo docker-compose exec php composer install`
* Setup a new client: `sudo docker-compose exec php php bin/console fos:oauth-server:create-client --grant-type="token" --grant-type="authorization_code" --grant-type="password"`
* Create admin user: `sudo docker-compose exec php php bin/console fos:user:create admin --super-admin`

Now you can access API at `http://localhost:8001`.

##  End-points
##### Get Token (Login)
```
POST /oauth/v2/token HTTP/1.1
Host: localhost:8001
Content-Type: application/json
Cache-Control: no-cache

{
	"client_id": "1_4pc1r1ygmdk4ws80s8ck4gwcso84w48008os4gw80kwcks8gc4",
	"client_secret": "5sesub28au4gggswwsc8csw08ws88008k00owwk08sk8s0gw0k",
	"grant_type": "password",
	"username": "admin",
	"password": "123"
}
```
##### Create User
```
POST /api/v1/users HTTP/1.1
Host: localhost:8001
Content-Type: application/json
Authorization: Bearer NDc5ZjdhMGZhMWFlMTBiOGUxNDBlZjcxMTgxYzdkYjNjYWM2NTExZGRkMzYwMzVkMWE2ZDk3ZjljYjMwZTFlYg
Cache-Control: no-cache

{
	"username": "mohsen",
	"email": "persisch@msn.com",
	"plainPassword": "123",
	"fullname": "Mohsen"
}
```
##### Delete User
```
DELETE /api/v1/users/mohsen HTTP/1.1
Host: localhost:8001
Content-Type: application/json
Authorization: Bearer NjMzNjI0NDMyMWYxYmZkZTM4ZDMyYTU5MDJjZGU5YjcxOTE0OTFiMWU2NDIwOTg4YjRhNWI3MjM3ZDVkMjIwZQ
Cache-Control: no-cache
```
##### Create UserGroup
```
POST /api/v1/usergroups HTTP/1.1
Host: localhost:8001
Authorization: Bearer NWNjYzJiNTRiNDA5OGZkMDBhYzhlZjdmMGQyMjI5ZTY1MThlMzRiODJkMTE0MDFkZWJjNjhhODY5OTNjMmJmYQ
Content-Type: application/json
Cache-Control: no-cache

{
	"name": "my_group"
}
```
##### Delete UserGroup
```
DELETE /api/v1/usergroups/my_group HTTP/1.1
Host: localhost:8001
Authorization: Bearer NWNjYzJiNTRiNDA5OGZkMDBhYzhlZjdmMGQyMjI5ZTY1MThlMzRiODJkMTE0MDFkZWJjNjhhODY5OTNjMmJmYQ
Content-Type: application/json
Cache-Control: no-cache
```
##### Add User to UserGroup
```
POST /api/v1/usergroups/my_group/users/mohsen HTTP/1.1
Host: localhost:8001
Authorization: Bearer NWNjYzJiNTRiNDA5OGZkMDBhYzhlZjdmMGQyMjI5ZTY1MThlMzRiODJkMTE0MDFkZWJjNjhhODY5OTNjMmJmYQ
Cache-Control: no-cache
```
##### Remove User from UserGroup
```
DELETE /api/v1/usergroups/my_group/users/mohsen HTTP/1.1
Host: localhost:8001
Authorization: Bearer NWNjYzJiNTRiNDA5OGZkMDBhYzhlZjdmMGQyMjI5ZTY1MThlMzRiODJkMTE0MDFkZWJjNjhhODY5OTNjMmJmYQ
Cache-Control: no-cache
```

## TODO:
* Unit Test
