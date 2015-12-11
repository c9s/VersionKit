VersionKit
==================

[![Build Status](https://travis-ci.org/c9s/VersionKit.svg?branch=master)](https://travis-ci.org/c9s/VersionKit)

Utility functions for manipulating version strings.

```php
$versionStrs = [
    'php-5.3.1',
    'php-5.3.22',
    'php-5.4',
    'php-5',
    'hhvm-3.2',
    'php-5.3.0-dev',
    'php-5.3.0-alpha3',
    'php-5.3.0-beta2',
    'php-5.3.0-RC5',
    '5.3.0-dev',
    '5.3.0-alpha3',
    '5.3.0-beta2',
    '5.3.0-RC5',
    '5.3.3',
    '5.3.0',
    '5.3',
    '5',
];
foreach ($versionStrs as $versionStr) {
    $version = new Version($versionStr);
    $version->major;
    $version->minor;
    $version->patch;
    $version->getVersion(); // 5.3.2
    $version->getCanonicalizedVersionName(); // php-5.3.0-dev
    $version->compare(new Version('php-5.3.2'));
}
```

