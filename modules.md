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
Classes to define and use data. This framework relies on entities and attributes to perform: parameters and forms parsing, database operations.

### Dependencies
- monolitum/core


## : monolitum\backend
Generic Nodes to work with parameters, page routing. 

### Dependencies
- monolitum/core
- monolitum/entity


## : monolitum\database
Simple ORM classes to perform operations on database based on Models and data in Entities.

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


## : monolitum\wangeditor
HTML Editor with "face and eyes".

### Dependencies
- monolitum/core
- monolitum/backend
- monolitum/frontend
- monolitum/bootstrap
