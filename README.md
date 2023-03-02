# XPeria

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


