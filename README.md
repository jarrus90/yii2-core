# yii2-core

Several common basic functionality for personal modules

> **NOTE:** Module is in initial development. Anything may change at any time.

## Contributing to this project

Anyone and everyone is welcome to contribute. Please take a moment to review the [guidelines for contributing](CONTRIBUTING.md).

## License

Yii2-core is released under the BSD-3-Clause License. See the bundled [LICENSE.md](LICENSE.md) for details.

##Requirements

YII 2.0

##Installation

~~~php

"require": {
    "jarrus90/yii2-core": "*",
},

php composer.phar update

~~~

#Console controllers

##Migration
[Thanks to dmstr](https://github.com/dmstr/yii2-migrate-command)

Console Migration Command with multiple paths/aliases support
~~~php
    'controllerMap'       => [
		'migrate' => [
			'class' => 'jarrus90\Core\Console\MigrateController'
		],
	],
~~~
##Assets cleanup
[Thanks to assayer-pro](https://github.com/assayer-pro/yii2-asset-clean)

Yii2 console command to clean web/assets/ directory
~~~php
	'controllerMap' => [
		'asset' => [
			'class' => 'jarrus90\Core\Console\AssetController',
		],
	],
~~~