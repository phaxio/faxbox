Welcome to Faxbox

To set you password click the link below:
{{ action('UserController@activate', ['id' => $userId, 'code' => $activationCode]) }}
