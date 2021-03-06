Yii 2 Pug (ex Jade) extension
===============================

This extension provides a view renderer for [Pug](https://pugjs.org/) templates
for [Yii framework 2.0](http://www.yiiframework.com/) applications.

[![License](https://poser.pugx.org/pug/yii2/license.svg)](https://packagist.org/packages/pug/yii2)
[![Latest Stable Version](https://poser.pugx.org/pug/yii2/v/stable.svg)](https://packagist.org/packages/pug/yii2)
[![Travis CI Build Status](https://travis-ci.org/pug-php/pug-yii2.svg)](https://travis-ci.org/pug-php/pug-yii2)
[![Test Coverage](https://codeclimate.com/github/pug-php/pug-yii2/badges/coverage.svg)](https://codeclimate.com/github/pug-php/pug-yii2/coverage)
[![Issue Count](https://codeclimate.com/github/pug-php/pug-yii2/badges/issue_count.svg)](https://codeclimate.com/github/pug-php/pug-yii2)
[![StyleCI](https://styleci.io/repos/113600110/shield?branch=master)](https://styleci.io/repos/113600110)

Support
-------
* [GutHub issues](https://github.com/pug-php/pug-yii2/issues)


Installation
------------

The preferred way to install this extension is through [composer](https://getcomposer.org/).

Either run

```bash
composer require pug/yii2
```

or add

```
"pug/yii2": "^1.0",
```

to the `require` section of your `composer.json` file.


Configure
---------
```php
<?php

return [
  // ...
  'components' => [
    // ...
    'view' => [
      // ...
      'renderers' => [
        'pug' => 'Pug\\Yii\\ViewRenderer',
      ],
    ],
  ],
];
```

You can also use other pug renderer like
[phug](https://www.phug-lang.com) or
[tale-pug](https://github.com/Talesoft/tale-pug)
```php
<?php

return [
  // ...
  'components' => [
    // ...
    'view' => [
      // ...
      'renderers' => [
        'pug' => [
          'class'    => 'Pug\\Yii\\ViewRenderer',
          'renderer' => 'Phug\\Renderer',
        ],
      ],
    ],
  ],
];
```
Phug and Pug-php (the default renderer) are automatically installed
when you install the last version of `pug/yii2`, for other pug renderer,
replace the renderer class and include it.

For example, for Tale-pug, use `composer require talesoft/tale-pug`
then replace `'Tale\\Pug\\Renderer'` with `'Tale\\Pug\\Renderer'`
in the config example above.

### Cache path and View path

By default, this extension uses `'@runtime/pug/cache'` to store compiled
templates, and looks in `'@app/views'` to find the templates to compile.

You can change this, by specifying `cachePath` and/or `viewPath` in the
configuration, for example:

```
return [
  // ...
  'components' => [
    // ...
    'view' => [
      // ...
      'renderers' => [
        'pug' => [
          'class'     => 'Pug\\Yii\\ViewRenderer',
          'cachePath' => '@app/runtime/Pugcache',
          'viewPath'  => '@app/themes/dark/templates/views',
        ],
      ],
    ],
  ],
];
```

`pug/yii` provide 2 global variables: `app` (`Yii::$app`) and `view` (view
path given to `->render()` in your controller). It imply that:
- If you set **app** or **view** shared variables, they will be erased by
those system values.
- If you pass **app** or **view** locals to the `->render()` method they
will have the precedence on system values.

To avoid such name conflict, you can pack into on object and store in a
shared variable with a name you chose:
```php
return [
  // ...
  'components' => [
    // ...
    'view' => [
      // ...
      'renderers' => [
        'pug' => [
          'class'           => 'Pug\\Yii\\ViewRenderer',
          // ...
          'systemVariable'  => '_yii',
        ],
      ],
    ],
  ],
];
```
With this config, **app** and **view** are no longer reserved names
(can be used for your own locals or shared variables),
only **_yii** is, and so you can get app and view like this:
```pug
h1=_yii.view.defaultExtension
p=_yii.app.version
```
Or if you set the `expressionLanguage` option to `"php"`:
```pug
h1=$_yii->view->defaultExtension
p=$_yii->app->getVersion()
```

Credits
-------

This solution merge both project
[rmrevin/yii2-pug](https://github.com/rmrevin/yii2-pug)
(original fork that support pug-php 2) and
[jacmoe/yii2-tale-pug](https://github.com/jacmoe/yii2-tale-pug)
(tale-pug and tale-jade Yii2 solution)
and finally bring support for pug-php 3 and phug engines.
 