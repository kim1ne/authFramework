# FrameworkAuthORM
### Quick launch of a website with an authorization system

_______
* Use Redis-7.0
* Use MySQL-5.7-Win10
* PHP 8.0+
_______

```
composer install
```

### ORM

##### Create

```
$object = new ActiveRecord();
```
```
$object->set($property, $values)...;
```
```
$object->save();
```

##### Update

```
$object->set($property, $values)...;
```
```
$object->save();
```

##### Read

```
$object = ActiveRecord::all();
```
```
$object = ActiveRecord::find(id);
```
```
$object = ActiveRecord::findByColumn($column, $value);
```
