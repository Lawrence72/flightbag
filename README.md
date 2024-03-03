# Flight Bag
Flight bag is a lightweight tool kit for the Flight PHP Framework ([flightphp.com](flightphp.com))

### How to install

composer require lawrence72/flightbag

### Sanitizer

$sanitizer = new Sanitizer(); OR
Flight::register('sanitizer', Sanitizer::class);

$text = "Some Text"
$new_text = $sanitizer->clean($text);

#### How to use with HTML tags

Include the tags you wish to allow
$text = "<b>Some Text</b>"
$new_text = $sanitizer->clean($text,['b']);

Sanitizer accepts strings,arrays,objects or Flight Collections

### Session

$session = new Session(); OR
Flight::register('session', Session::class);

$session->set('user_id', 1);

$has_user_id = $session->has('user_id');

$get_user_id =  $session->get('user_id');

$session->remove('user_id');

$session->destroy(); 

Session also accepts an optional encryption key. This will encrypt the session data.

$session = new Session('some_very_secure_key');

##### Flash Messages

Flash messages is also supported

setFlash accepts a message and a class name to assign to the flash message.
$session->setFlash('message','warning');

getFlash returns an array of recently added flash messages, flash messages are automatically removed after getFlash is called.

$session->getFlash();

### Cookie

$cookie = new Cookie(); OR
Flight::register('cookie', Cookie::class);

$cookie->cookie()->set('token', 'somevalue', 3600);

set() accepts all PHP cookie options. 

$cookie->cookie()->has('token');

$cookie->cookie()->get('token');

$cookie->cookie()->remove('token');