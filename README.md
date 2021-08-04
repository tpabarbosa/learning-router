
# Learning how a Simple Router Class Works

**Basic usage:**

```php
Router::init();
Router::get('/', function() {
    return 'Hello world';
});

//Router::resolve($method, $path) returns an object that represents the route
$route = Router::resolve('get', '/');
//outputs: '/' [GET]
echo $route;

//outputs: 'Hello world'
echo $route->callback();

//outputs: "Method 'POST' is not allowed to path '/'. \r\n Please try one of this methods: [GET]."
$other_route = Router::resolve('post', '/');
echo $other_route;
```