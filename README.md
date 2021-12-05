# CuePhp-Cache

[![Build Status](https://github.com/cueBlog-Club/cache/actions/workflows/ci.yml/badge.svg)](https://github.com/cueBlog-Club/cache/actions)
[![Code Coverage](https://codecov.io/gh/cueBlog-Club/cache/branch/main/graph/badge.svg)](https://codecov.io/gh/cueBlog-Club/cache/branch/main)

[![Latest Stable Version](https://img.shields.io/packagist/v/cuephp/cache.svg?style=flat-square)](https://packagist.org/packages/cuephp/cache)
[![Total Downloads](https://img.shields.io/packagist/dt/cuephp/cache.svg?style=flat-square)](https://packagist.org/packages/cuephp/cache)


### Suport Storage Engine

+ InMemory
+ File
+ Memcached
+ Redis
+ Yac

### Instanll

```
compose require cuephp/cache
```

### Cache Usage

```php
use CuePhp\Cache\Engine\InMemoryEngine;
use CuePhp\Cache\Config\InMemoryConfig;

$config = new InMemoryConfig;
$engine = new InMemoryEngine( $config );

$engine->set( 'key', 'value', 10 );

$item = $engine->get('key');
$item->getData(); // return 'value'
```

### Counter Usage

```php
use CuePhp\Cache\Engine\InMemoryEngine;
use CuePhp\Cache\Config\InMemoryConfig;

$config = new InMemoryConfig;
$engine = new InMemoryEngine( $config );

$incrCounter= $engine->incr( 'key' );
$value = $incrCounter->getData(); //return 1 if 'key' not exist

$incrCount = $engine->incr('key', 10);
$value = $incrCounter->getData(); //return 11

```