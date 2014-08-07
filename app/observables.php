<?php

// User Login event
Event::listen('user.login', function($userId, $email)
{
    Session::put('userId', $userId);
    Session::put('email', $email);
});

// User logout event
Event::listen('user.logout', function()
{
    Session::flush();
});

// Subscribe to User Mailer events
Event::subscribe('Faxbox\Mailers\UserMailer');

Event::listen('fax.processed', function($fax){
    
    if($fax['direction'] == 'sent')
    {
        $send = false;

        switch ($fax['user']['sent_notification'])
        {
            case 'always':
                $send = true;
                break;

            case 'failed':
                $send = !$fax['sent'];
                break;

            case 'never':
                break;
        }

        $template = $fax['sent'] ? 'emails.fax.sent.success' : 'emails.fax.sent.failed';


        if ($send)
        {
            Mail::send($template, compact('fax'), function ($message) use ($fax)
            {
                $status = $fax['sent'] ? 'successfully sent' : 'sending failed';
                $message->to($fax['user']['email'])->subject('Fax ' . $status);
            });
        }
    }
    
    if($fax['direction'] == 'received')
    {
        $phoneViewer = Permission::name('Faxbox\Repositories\Phone\PhoneInterface', 'view', $fax['phone']['id']);
        $phoneAdmin = Permission::name('Faxbox\Repositories\Phone\PhoneInterface', 'admin', $fax['phone']['id']);

        $users = Sentry::findAllUsersWithAccess([$phoneViewer, $phoneAdmin]);
        
        foreach($users as $user)
        {
            $send = false;

            switch ($user->received_notification)
            {
                case 'always':
                    $send = true;
                    break;

                case 'mine':
                    $send = $user->hasAccess($phoneAdmin);
                    break;

                case 'groups':
                    // todo check groups
                    break;

                case 'never':
                    break;
            }
            
            if ($send)
            {
                Mail::send('fax.received', compact('fax'), function ($message) use ($fax)
                {
                    $message->to($fax['user']['email'])->subject('Incoming Fax from '.$fax['phone']);
                });
            }
        }

    }
});