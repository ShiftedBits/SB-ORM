SB-ORM
======
## Introduction
The ShiftedBits ORM is to ease the development of database-assisted technologies. In doing so, it will ensure that the developer gets the data they require in a quick and easily accessible way. 

## Requirements
* The ORM will provide data security measures to prevent most attack vectors to leak database data through abuse of the system the ORM is supporting (known as SQL Injection Attacks). It does not know all vectors, and not all vectors can be handled by the ORM, but it will do it's best.
* The ORM will be written in such a way as to follow RESTful queries, including the basic GET, POST, PUT and DELETE queries from such requests.
* The ORM will be able to provide a state-ful transaction-based presence within the programming interface, in order to provide the simplicity required when it comes to modifying data (via POST, PUT, or DELETE queries via the RESTful interfaces expected).
* The ORM will also be maintained in a way to provide easy testing, and confirmation of it's security.
* The ORM will be written as to provide easy proof of quality. This goes hand-in-hand with it's ease of testing requirement. The more we can test, and the better tests it can take, the easier it will be to provide proof of quality.

## Implementation Notes
The simplest implementation is to use the built-in PDO library from the PHP language and build off of that. In order to provide easy RESTful usability, automatic analyzation of the table, it's keys, and column types is necessary. In order to provide a transaction-based presence, the usage of magic-methods within PHP to automatically map a function call to a column name within a table will be necessary, as will the use of a modified Factory pattern that implements a DataMapper pattern. The ability to run multiple SQL queries in a transaction must be necessary, but also switchable via an autocommit flag. If autocommit is on, all statements once created are run and any outcomes are then reported. If autocommit is off, all statements created since the flag was switched are saved until the commit command is run. This also means that any cheap checking is done as the data comes in. Verify column names with data types for all values processed. Ensure that all values are escaped and cleaned properly.

## Use Cases
### Simple GET Request (Id known)
```php
<?php
$row = $model->get($id);
```
### Simple GET Request (Column Name and Value Known)
```php
<?php
$row = $model->get($column, $value);
```
### Simple GET Request (multiple row matches)
```php
<?php
$rows = $model->get($id, $id, $id, ..., $id);
$rows = $model->get($ids); //In Array Format
$rows = $model->get($column, $value);
```
### Simple POST Request (Id known)
```php
<?php
$model->post($id, $data);
```
### Simple POST Request (Column Name and Value Known)
```php
<?php
$model->post($column, $value, $data);
```
### Simple PUT Request (Id Known)
```php
<?php
$model->put($id, $data);
```
### Simple PUT Request (Column Name and Value Known)
```php
<?php
$model->put($column, $value, $data);
```
### Simple DELETE Request (Id Known)
```php
<?php
$model->delete($id);
```
### Simple DELETE Request (Column Name and Value Known)
```php
<?php
$model->delete($column, $value);
```
### Simple DELETE Request (Multiple Ids Known)
```php
<?php
$model->delete($id, $id, $id, ..., $id);
$model->delete($ids); //In Array Format
```
### Simple DELETE Request (Column Name Known, Multiple Values)
```php
<?php
$model->delete($column, $value, $value, $value, ..., $value);
$model->delete($column, $values); //As Array
```
### Simple DELETE Request (Multiple Columns, One Value Each)
```php
<?php
$model->delete($data); //Array( column => value );
```
### Completing a transaction
```php
<?php
$model->commit(); //Performs an auto-rollback on error, and throws an exception itself.
```
### Turning Autocommit Off
```php
<?php
$model->autocommit(false);
```
### Turning Autocommit On
```php
<?php
$model->autocommit(true);
```
### Retreiving Known Row
```php
<?php
$object = $model->fetch($id)
$object = $model->fetch($column, $value);
```
### Updating Object (where <column> is the name of the column in the table)
```php
<?php
$object-><column>($value);
```
### Retreiving Data (where <column> is the name of the column in the table)
```php
<?php
$value = $object->column();
```
### Saving Object
```php
<?php
$object->save();
```
### Deleting Object
```php
<?php
$object->delete();
```
### Reload Object From Database
```php
<?php
$object->reload();
```
