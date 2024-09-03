## Library Management System

 Library Management System is a software to manage  create, show,update and delete books, and allowing users to borrow books and giving it a rating ,with ability to sort and filter books.
### Githup Link

- [githup link](https://github.com/Ali-Darwesh/library-management-system).
## API Testing with Postman
To test the API endpoints, you can use the Postman collection provided in the project. Find it at `resources/docs/postman/endpointTask4.postman_collection.json`.

## Create Laravel project with 
### Create the project and initialize it
- composer create-project --prefer-dist laravel/laravel library-management-system
- git init
- git add .
- git commit -m ""
### Make important files like api route,models,controllers,requests,seeder
- php artisan install:api
- php artisan make:model Book -mcr --api  
- php artisan make:model Rating -mcr --api  
- php artisan make:model Borrow_record -mcr --api
- php artisan make:controller UserController
- php artisan make:controller AuthrController
- php artisan migrate  
- php artisan make:request BookRequest
- php artisan make:seeder AdminSeeder
- php artisan migrate --seed
## Models
All of them contain fillable array,ralations
- Book Model scopes(Sort,Filter)


## Services
I make Services for create ,update and delete operations
- BookService

## Database
there is three tables :
- books
- ratnigs FK(user_id,book_id)
- users
- borrow_records FK(user_id,book_id)
rating related to movies with one to one relation
rating related to users with one to many relation
### seeder
to create the admin account
## Controllers
In controllers I use all above to keep it in better look and clean code


