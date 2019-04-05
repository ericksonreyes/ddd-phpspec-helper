## DDD PhpSpec Helper
PhpSpec helper for generating domain event and entity specification and implementation classes.

[![License](https://img.shields.io/github/license/ericksonreyes/domain-driven-design.svg)](LICENSE.MD)
[![Packagist Version](https://img.shields.io/packagist/v/ericksonreyes/ddd-phpspec-helper.svg)](https://packagist.org/packages/ericksonreyes/domain-driven-design)
[![Last Commit](https://img.shields.io/github/last-commit/ericksonreyes/ddd-phpspec-helper.svg)](https://github.com/ericksonreyes/domain-driven-design/commits/master)
[![Stable Version](https://img.shields.io/github/tag/ericksonreyes/ddd-phpspec-helper.svg)](https://github.com/ericksonreyes/domain-driven-design/tags)
[![PHP Version](https://img.shields.io/packagist/php-v/ericksonreyes/ddd-phpspec-helper.svg)](https://github.com/ericksonreyes/domain-driven-design/tags)
[![Downloads](https://img.shields.io/github/downloads/ericksonreyes/ddd-phpspec-helper/total.svg)](https://github.com/ericksonreyes/domain-driven-design/tags)
[![Installations](https://img.shields.io/packagist/dm/ericksonreyes/ddd-phpspec-helper.svg)](https://packagist.org/packages/ericksonreyes/domain-driven-design)


## How to install
```bash
composer require ericksonreyes/domain-driven-design
```

## How to use
```bash
bin/phpspec entity MyDomain/EntityName
bin/phpspec event MyDomain/EventName
bin/phpspec command MyApplication/Command/WipeFileSystem
bin/phpspec handler MyApplication/Handler/WipeFileSystemHandler
```
