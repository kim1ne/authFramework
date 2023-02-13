# FrameworkAuthORM
### Quick launch of a website with an authorization system



* Use Redis-7.0
* Use MySQL-5.7-Win10
* PHP 8.0+



```
composer install
```

### ORM

##### Create

```
$object = new ActiveRecord();
$object->set('*', '*')...;
$object->save();
```

##### Read

```
$object = ActiveRecord::all();
$object = ActiveRecord::find();
$object = ActiveRecord::findByColumn();
```
