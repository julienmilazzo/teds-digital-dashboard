<?php

namespace App\Util;

class SendMail
{
    /**
     * @param $view
     * @param $subject
     * @param $mailer
     */
    public static function sendOrderMail($view, $subject, $mailer)
    {
        $message = (new \Swift_Message($subject))
            ->setTo('babou01250@gmail.com')
            ->setFrom('baptiste@teds.fr')
//            ->setBcc('julien@teds.fr')
            ->setBody(
                $view,
                'text/html'
            );
        $mailer->send(
            $message
        );
    }
}
