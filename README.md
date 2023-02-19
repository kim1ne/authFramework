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

##### Delete

```
$object->delete();
```

```
ActiveRecord::remove($id);
```

_______

##### Check unique

```
ActiveRecord::unique($column, $value);
```

_______

### User auth

User sessions are stored in redis. In the App\Models\User class, the SESSION_TIME constant contains the session time.

##### User registration

```
$user = new User();
$user->set($column, $value);
$user->save();
```

##### User authorization

```
$user->auth();
```

##### User logout

```
$user->logout();
```

##### Current user

```
$user = User::current;
```

##### Check auth user

```
User::isAuth();
```

User::current() - return user from database
User::isAuth() - check session user

_______
