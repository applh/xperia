# XP-SubDomain

WP Plugin to share subdomains to only one WP install

## Description

Once the plugin active, you can add subdomains and assign home pages to them.
  
## API

* You can add a api key to the plugin settings page
* Then you can send POST requests to the following url: 
  * https://yourdomain.com/wp-admin/admin-ajax.php
  * action = xpsubdomain
* There's a cli.php file that you can use to communicate with your WP websites

### API: MAIL

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
            "todo": "xpi_admin_helper::test"
        }
    ]
}
```

## TODOS / IDEAS

* Use slug name for home pages
  * Currently page id is used
* Add several pages to subdomains
* Add a public key to api key
* Add email template to api attachments
* Add android app to send SMS from WP website API
