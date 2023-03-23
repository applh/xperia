# XPERIA

WP Plugin to share subdomains to only one WP install

## Description

Once the plugin active, you can add subdomains and assign home pages to them.
  
## API

* You can add a api key to the plugin settings page
* Then you can send POST requests to the following url: 
  * https://yourdomain.com/wp-admin/admin-ajax.php
  * action = xperia
* There's a cli.php file that you can use to communicate with your WP websites

#### API: mail

* You can use your website to send emails to your users
* `php cli.php send my-cli/request_mail.json`
* and in the file request_mail.json you can add the following json code:

```json
{
    "uc": "request_json",
    "attachments": {
    },
    "uploads": [
        {
            "todo": "xpi_admin_helper::mail",
            "mailto": "test@applh.com",
            "subject": "test",
            "message": "test"
        },
        {
            "todo": "xp_task::test"
        }
    ]
}
```

#### API: options

* You can update options to your WP website

#### API: media

* You can upload media to your WP website

#### API: posts

* You can create pages and posts to your WP website
* featured image can be uploaded as a file
* post content can be uploaded as a file
  * FIXME: .html doesn't work ?!

#### API: menus

* You can create menus to your WP website

```json
{
    "config_json": "site.json",
    "uc": "request_json",
    "attachments": {
        "image-1": "my-cli/image-1.jpg",
        "image-2": "my-cli/image-2.jpg"
    },
    "uploads": [
        {
            "todo": "xp_task::test"
        },
        {
            "todo": "xp_task::add_media",
            "media": [
                {
                    "file": "image-1",
                    "caption": "test caption 1",
                    "description": "test description 1"
                },
                {
                    "file": "image-2",
                    "caption": "test caption 2",
                    "description": "test description 2"
                }
            ]
        }
    ]
}
```

## TODOS / IDEAS

* Use slug name for home pages
  * Currently page id is used
* Add several pages to subdomains

* Add a public key to api key

* Add plugin update from uploaded zip archive

* Add email template to api attachments
* Add email PHP proxy code generator

* Add android app to send SMS from WP website API

* Add code in post_content
  * FIXME: sanitize_post removes tags like `<script>`, etc...



## PHP LOCAL SERVER

* https://www.php.net/manual/en/features.commandline.webserver.php
* Subdomains can be used with localhost
  * app.localhost:9876
  * news.localhost:7654

```bash

php -S localhost:8000

php -S localhost:8000 -t media

```

## VUEJS

* https://vuejs.org/guide/quick-start.html

Version with compiler included

* https://unpkg.com/vue@3/dist/vue.esm-browser.js
* https://unpkg.com/vue@3/dist/vue.esm-browser.prod.js


## UIKIT 

* Web ui framework
* https://getuikit.com/docs/introduction

## BABYLONJS

* 4D engine
* https://doc.babylonjs.com/setup/starterHTML
* https://github.com/RaananW/babylonjs-webpack-es6
  
## LEAFLETJS

* Map framework
* https://leafletjs.com/examples/quick-start/

## P5JS

* Multimedia Sketch framework
* https://p5js.org/get-started/
  
## CODEMIRROR

* Code editor
* https://codemirror.net/
* https://cdnjs.com/libraries/codemirror

## REVEALJS

* Presentation framework
* https://revealjs.com/installation/


## WP TECHNOLOGIES 

### Gutenberg

* https://github.com/front/gutenberg-js
* window.wp.apiFetch

#### JS Filters

```js
window.wp.domReady(function () {
    console.log('domReady');
});

// https://developer.wordpress.org/block-editor/reference-guides/filters/block-filters/
wp.hooks.addFilter(
    'blocks.registerBlockType', 
    'test1/test1/add-custom-control', 
    addCustomControl
);

// https://developer.wordpress.org/block-editor/reference-guides/filters/block-filters/
wp.hooks.addFilter(
    'editor.BlockEdit',
    'test1/test1/extra-controls',
    withInspectorControls
);
```

#### PHP Filters

* useful to dynamically add blocks by PHP (only one JS file needed)
* add editor-script to first block
* then add easily more blocks by inline JS

```php

wp_add_inline_script(
    'wp-customize-widgets',
    sprintf(
        'wp.domReady( function() {
            wp.customizeWidgets.initialize( "widgets-customizer", %s );
        } );',
        wp_json_encode( $editor_settings )
    )
);

// ...

/** This action is documented in edit-form-blocks.php */
do_action( 'enqueue_block_editor_assets' );

```

* block_editor_settings_all ?!

```php
	/**
	 * Filters the settings to pass to the block editor for all editor type.
	 *
	 * @since 5.8.0
	 *
	 * @param array                   $editor_settings      Default editor settings.
	 * @param WP_Block_Editor_Context $block_editor_context The current block editor context.
	 */
	$editor_settings = apply_filters( 'block_editor_settings_all', $editor_settings, $block_editor_context );

```

#### React vs Vue

If you want to build a framework with PHP as server language, then plugins must have APIs to customize the frontend.
* PHP API
* JS API

But if you don't want to add Nodejs, then typescript and JSX are bad choices.
* Gutenberg code is built with React, JSX.
* But Gutenberg provides also a JS API to customize the frontend.
* So basically, Gutenberg is rebuilding... VueJS with... React and JSX ?! 😱

* VueJS is a better choice as there's an ESM version including the compiler.
* It's easier for plugin developers to customize the frontend. 😎

### REST API

* https://developer.wordpress.org/rest-api/
* GET and POST requests


* `/wp-json/`
* `/wp-json/wp/v2/posts`
* `/wp-json/wp/v2/themes`

* https://developer.wordpress.org/block-editor/reference-guides/packages/packages-api-fetch/
  
```js
window.wp.apiFetch({
    path: '/wp-json/wp/v2/posts',
    method: 'POST',
    data: {
        title: 'My First Post',
        content: 'Hello World',
        status: 'publish',
    },
})
```

## WP-ENV

WordPress development environment based on Docker.

* https://developer.wordpress.org/block-editor/getting-started/devenv/
* https://www.npmjs.com/package/@wordpress/env
* https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/


### PHP VALUE

* change PHP values in .htaccess

```
php_value upload_max_filesize "100M"
php_value post_max_size "100M"

```

### PHP extensions

* Found
* https://juzhax.com/2021/08/installing-php-extension-in-wp-env/

* Working custom version
  
```bash

#!/bin/bash
WPIP="$(wp-env install-path)"
echo $WPIP
# split WPIP by / separator and get last part
WPIP=$(echo $WPIP | awk -F/ '{print $NF}')
echo $WPIP
# filter docker ps and get line with $WPIP-wordpress, then get first column
DPS="$(docker ps | grep "$WPIP-wordpress" | awk '{print $1}')"
echo $DPS

CONTAINER_ID=$DPS
echo $CONTAINER_ID

# install extension pdo_mysql
docker exec -it $CONTAINER_ID docker-php-ext-install pdo_mysql

# restart apache
docker exec -it $CONTAINER_ID /etc/init.d/apache2 reload

```

### Gutenbgerg packages

* https://developer.wordpress.org/block-editor/reference-guides/packages/packages-create-block/

* https://github.com/WordPress/gutenberg-examples


* https://developer.wordpress.org/block-editor/how-to-guides/data-basics/


* https://developer.wordpress.org/block-editor/reference-guides/packages/packages-element/
* https://developer.wordpress.org/block-editor/reference-guides/packages/packages-api-fetch/
* https://developer.wordpress.org/block-editor/reference-guides/packages/packages-viewport/


## Vue Librairies 


### Vue Draggable

* https://github.com/SortableJS/vue.draggable.next
* https://sortablejs.github.io/vue.draggable.next/#/handle

### Pinia

* https://pinia.vuejs.org/
* (VueX official alternative)

## Storybook

* Storybook is an open source tool for developing UI components in isolation 
  * for React, Vue, Angular, etc...
  * Component Driven Development (CDD)
  * around 1Go of dependencies (node_modules)
  * Playwright integration
  * export to static HTML+JS

* https://storybook.js.org/
* https://storybook.js.org/tutorials/intro-to-storybook/vue/en/get-started/

