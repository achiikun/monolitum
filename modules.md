# MODULES

## : monolitum\thirdparty_moment
Copypaste of Moment.php library

### Dependencies
_No dependencies_

## : monolitum\core
Engine of the framework

### Dependencies
- monolitum\thirdparty_moment


## : monolitum\entity
Classes to define and use data. This framework relies on entities and attributes to perform: get/post parameter parsing, form validation and database operations.

### Dependencies
- monolitum/core


## : monolitum\backend
Generic Nodes to work with get/post parameters, page routing and resource management. 

### Dependencies
- monolitum/core
- monolitum/entity


## : monolitum\database
Simple ORM classes to perform operations on database based on Models and Data in Entities.

### Dependencies
- monolitum/core
- monolitum/entity
- monolitum/backend
- **PDO (External)**


## : monolitum\auth
Simple Authentication manager. Given an Entity and username/password attributes it handles the session, login, logout, password change and simple permissions.

### Dependencies
- monolitum/core
- monolitum/entity
- monolitum/backend
- monolitum/database


## : monolitum\frontend
Classes to work with HTML and CSS. It includes classes to work with forms, as well.

### Dependencies
- monolitum/core
- monolitum/entity
- monolitum/backend


## : monolitum\fontawesome
Package with an embedded version of the fontawesome.

### Dependencies
- monolitum/core
- monolitum/frontend
- monolitum/backend


## : monolitum\bootstrap
Wrapper around bootstrap.

### Dependencies
- monolitum/core
- monolitum/entity
- monolitum/backend
- monolitum/frontend


## : monolitum\quilleditor
HTML Editor with "face and eyes".
Original Quill in [github](https://github.com/nadar/quill-delta-parser) has been refactored to be compatible with PHP 5.6.

### Dependencies
- monolitum/core
- monolitum/backend
- monolitum/frontend
- monolitum/bootstrap
