[TOC]

#  1. Public

> These are some public functions from my own php file, MySqlOperation.php -- not included in the class of _MySqlOperation_ ;
##  1. insert

``` php
function insert($table, $obj, $value) { 
     #Code  ...
    // $table = { table name }
    // $obj = { obj of table }
    // $value = { obj's value } 
}
```
## 2. update

``` php
function update($table) { 
     #Code  ...
    // $table = { table name }
}
```
## 3. set

``` php
function set($obj, $value) { 
     #Code  ...
    // $obj= { obj of table }
    // $value = { obj's value }
}
```
## 4. select

``` php
function select($content) { 
     #Code  ...
    // $table = { select content }
}
```
## 5. between

``` php
function between($obj, $start, $end) { 
     #Code  ...
    // $obj= { table obj }
    // $start= { obj of start  }
    // $end= { obj of end  }
```
## 6. from

``` php
function from($table) { 
     #Code  ...
    // $table = { table name }
}
```
## 7. where

``` php
function where($obj, $data) { 
     #Code  ...
    // $obj= { obj of table  }
    // $data= { obj's data  }
}
```
## 8. orderby

``` php
function orderby($obj) { 
     #Code  ...
    // $obj = { order by obj }
}
```
# 2. Private