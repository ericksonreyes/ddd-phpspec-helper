## DDD PhpSpec Helper
PhpSpec helper for generating domain event and entity specification and implementation classes.

[![License](https://img.shields.io/github/license/ericksonreyes/ddd-phpspec-helper.svg)](LICENSE.MD)
[![Packagist Version](https://img.shields.io/packagist/v/ericksonreyes/ddd-phpspec-helper.svg)](https://packagist.org/packages/ericksonreyes/ddd-phpspec-helper)
[![Last Commit](https://img.shields.io/github/last-commit/ericksonreyes/ddd-phpspec-helper.svg)](https://github.com/ericksonreyes/ddd-phpspec-helper/commits/master)
[![Stable Version](https://img.shields.io/github/tag/ericksonreyes/ddd-phpspec-helper.svg)](https://github.com/ericksonreyes/ddd-phpspec-helper/tags)
[![PHP Version](https://img.shields.io/packagist/php-v/ericksonreyes/ddd-phpspec-helper.svg)](https://github.com/ericksonreyes/ddd-phpspec-helper/tags)
[![Downloads](https://img.shields.io/github/downloads/ericksonreyes/ddd-phpspec-helper/total.svg)](https://github.com/ericksonreyes/ddd-phpspec-helper/tags)
[![Installations](https://img.shields.io/packagist/dm/ericksonreyes/ddd-phpspec-helper.svg)](https://packagist.org/packages/ericksonreyes/ddd-phpspec-helper)


## How to install
```bash
composer require ericksonreyes/ddd-phpspec-helper
```

## How to use
```bash
bin/phpspec entity MyDomain/EntityName
bin/phpspec event MyDomain/EventName
bin/phpspec command MyApplication/Command/WipeFileSystem
bin/phpspec handler MyApplication/Handler/WipeFileSystemHandler
```
