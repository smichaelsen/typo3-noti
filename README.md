# Noti

## Your TYPO3 notification API

Sending an email notification for a certain event is very common requirement for extension developers and of course it's not that hard.
Using the TYPO3 `MailMessage` class you'll be done in a few minutes.

But wait.. the client wants to configure the recipient mail addresses? Don't rack your brain where to put the configuration. Use Noti!
 
## What does it do?

Using noti an extension can trigger an event like "A new user registered", "we received a new product rating", "the daily data import went wrong".

In the TYPO3 backend you can create subscription records for those events.
 
Right now there are two notification types available:
 
### Email Notification

Let's you configure a list of mail addresses that receive a notification when the event is triggered.

### Slack Notification

Sends a message to our favorite chat app when the event is triggered. 

## How do I implement it in my extension?

In your `ext_localconf.php` register your event:

````
\Smichaelsen\Noti\EventRegistry::registerEvent(
    (new \Smichaelsen\Noti\Domain\Model\Event('myUniqueEventIdentifier'))
        ->setTitle('New user registered') // LLL reference is possible and recommended here
        ->addPlaceholder('userName', 'The user name') // This will appear in the backend to show the available placeholders to the user
);
````

Then in your code trigger the event like this:

````
EventRegistry::triggerEvent(
    'myUniqueEventIdentifier',
    [
        'userName' => $newUser->getUsername(),
    ]
);
````
