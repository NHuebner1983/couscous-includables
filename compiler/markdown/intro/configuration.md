---
currentMenu: configuration
---

# Configuration

Suspendisse varius ligula arcu, gravida facilisis augue ullamcorper eget. Sed volutpat, eros in congue dictum, ipsum ante ornare ligula, quis tristique neque ligula vel eros. Duis tincidunt bibendum semper.

```php
--- {root}/app/config.php ---

return [
    [...],
    'test-mode' => TRUE,
    'test-ip'   => '127.0.0.1',
    'timezone'  => 'UTC',
    'db' => [
        'database'    => 'db-name',
        'host'        => 'localhost',
        'port'        => NULL,
        'socket'      => NULL,
        'username'    => 'db-username',
        'password'    => 'db-password',
        'log-failed'  => TRUE,
        'log-success' => TRUE,
        'log-limit'   => 1000,
    ],
    [...]
];
```

>**Note:** Curabitur sit amet diam non lacus congue feugiat quis.