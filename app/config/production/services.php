<?php

require_once('safeenv.php');
return array (
  'phaxio' => 
  array (
    'public' => safe_getenv('services.phaxio.public'),
    'secret' => safe_getenv('services.phaxio.secret'),
  ),
  'mailgun' => [
      'domain' => safe_getenv('services.mailgun.domain'),
      'secret' => safe_getenv('services.mailgun.secret'),
  ],
);