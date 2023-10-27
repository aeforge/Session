
# Session

AEForge Session component. Providing a way to deal with session across the application.

## Installation

To install via composer:

```Composer
  composer require aeforge/session
```

To clone the project:

```Cloning
    https://github.com/aeforge/Session.git
```

## Usage/Examples

### Example
```php
use Aeforge\Session\Session;

// Start a new session instance
$session = new Session();
// Add expiring time to 60 (in minutes)
$session->register(60);
//Set a normal session key and value
$session->set("session_key", "session_value");
//Set a flashed session key and value (useful for forms errors for example)
$session->flash("flashed_key", "flashed_value");
// Get a normal session value using a key
echo $session->get("hello");
//Get a flashed session value using a key
echo $session->getFlashed("hello");
// Clears both the flash session array and the data session array(normal session)
$session->clear();
// Clear the data array only(normal session)
$session->clearData();
// Clear the flashed array only
$session->clearFlashed();
// Ends the session
$session->destroy();
// Regenerates the session id
$session->regenerate();
// Check if the session expired
$session->isExpired ();
```