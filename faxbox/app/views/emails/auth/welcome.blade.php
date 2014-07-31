Welcome to Faxbox

To set your password click the link below:
{{ action('UserController@activate', ['id' => $userId, 'code' => $activationCode]) }}
