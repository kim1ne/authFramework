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

## ORM

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

## User auth

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

## Middleware

Registration middleware is not needed. The route is indicated. next the middleware() function is called. It accepts either an array or a string.
### File www/routes.php


```
Route::get('/', [\IndexController::class, 'index'])->middleware('auth');
```
```
Route::get('/', [\IndexController::class, 'index'])->middleware(['auth', 'admin]);
```

All middleware is stored in the namespace App\Http\Middleware\Wares. If the route has middleware 'auth' specified, the class must be named App\Http\Midlewares\Wares\AuthMiddleware and required middleware implements App\Http\Middlewares\BaseMiddlewareInterface.
BaseMiddlewareInterface implements 2 public function verify() and error().
verify() - check access and return true|false. If verify() return false, next is called error() - which return status code and description error.

```
class AuthMiddleware implements BaseMiddlewareInterface
{
    public function verify(): bool
    {
        return is_int(User::isAuth());
    }

    public function error(): array
    {
        return [
            401,
            'Ошибка авторизации'
        ];
    }
}
```

_______

## REST-API

When a user submits a request, the received user session token received during authorization must be transmitted. The token is passed in the headers[Authorize]. The token is needed if you want to use authorization

### 1
```
$token = $user->authorize();
return view(['token' => $token])
```
### 2

Token placed in headers[Authorize]
_______

## Database

_______

### DataBase Builder

App\Services\Db\Builder - allows you to perform sql queries to the database and return App\Services\Db\Db object

```
$users = Builder::query('SELECT * FROM `users`)->fetchAll();
```

