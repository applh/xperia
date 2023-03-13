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

## LEAFLETJS

* Map framework
* https://leafletjs.com/examples/quick-start/

## P5JS

* Multimedia Sketch framework
* https://p5js.org/get-started/
  
## WP TECHNOLOGIES 

### Gutenberg

* https://github.com/front/gutenberg-js
* window.wp.apiFetch

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
