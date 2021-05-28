# Rocketchat auto-messages
Simple PHP scripts to automation of sending Rocketchat messages.

## How to install
```bash
php composer.phar install
```

## How to configure
Copy **config.example.php** to **config.inc.php**. The first index is the user or the name of the message you want to schedule. Next you have to specify on which year, month, day, hour, minute or day of week you want to send the message:

```php
$users = [
    'user1' => [
        'webhook' => 'https://rocketchat.yourserver.com/hooks/123456abcdef',
        'year'      => '*',
        'month'     => '*',
        'day'       => '*',
        'hour'      => '9',
        'minute'    => '0',
        'daysOfWeek'=> '1',
        'message' => [
            "text" => "Wake up!"
        ]
    ]
];
```

Finally, in °°message°° array, you can specify the message to send with the syntax provided by Rocket Chat.

In this example the text "Wake up!" will be sent only on monday (day of the week number 1) at minute 0 and hour 9.

## How to run
```bash
php rc-automator.php
```

If there is a message to send, the output will be:
```
[*] Checking messages for user1... sending message... sent!
```

Otherwise the output will be:
```
[*] Checking messages for user1... no message to send.
``` 


## Cron
You should configure this script in crontab like this:

```
  0/5 *  *  *  * apache  cd /path/of/the/script && php rc-automator.php
```

If the frequency of the messages is not under 5 minutes it is recommendend to set the execution every 5 minutes.
