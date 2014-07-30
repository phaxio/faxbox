<?php namespace Faxbox\Mailers;

class UserMailer extends Mailer {

    /**
     * Outline all the events this class will be listening for.
     * @param  [type] $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen('user.signup', 		'Faxbox\Mailers\UserMailer@welcome');
        $events->listen('user.resend', 		'Faxbox\Mailers\UserMailer@welcome');
        $events->listen('user.forgot',      'Faxbox\Mailers\UserMailer@forgotPassword');
        $events->listen('user.newpassword', 'Faxbox\Mailers\UserMailer@newPassword');
    }

    /**
     * Send a welcome email to a new user.
     * @param  string $email
     * @param  int    $userId
     * @param  string $activationCode
     * @return bool
     */
    public function welcome($email, $userId, $activationCode)
    {
        \Log::info('Sending welcome email');
        $subject = 'Welcome to Faxbox';
        $view = 'emails.auth.welcome';
        $data['userId'] = $userId;
        $data['activationCode'] = $activationCode;
        $data['email'] = $email;

        return $this->sendTo($email, $subject, $view, $data );
    }

    /**
     * Email Password Reset info to a user.
     * @param  string $email
     * @param  int    $userId
     * @param  string $resetCode
     * @return bool
     */
    public function forgotPassword($email, $userId, $resetCode)
    {
        $subject = 'Password Reset Confirmation';
        $view = 'emails.auth.reset';
        $data['userId'] = $userId;
        $data['resetCode'] = $resetCode;
        $data['email'] = $email;

        return $this->sendTo($email, $subject, $view, $data );
    }

    /**
     * Email New Password info to user.
     * @param  string $email
     * @param  int    $userId
     * @param  string $resetCode
     * @return bool
     */
    public function newPassword($email, $newPassword)
    {
        $subject = 'New Password Information';
        $view = 'emails.auth.newpassword';
        $data['newPassword'] = $newPassword;
        $data['email'] = $email;

        return $this->sendTo($email, $subject, $view, $data );
    }



}