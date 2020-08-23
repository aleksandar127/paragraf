<?php

namespace App;

use Exception;
use Throwable;
use Swift_Mailer;
use Swift_Message;
use Swift_Attachment;
use Swift_SmtpTransport;
use App\Interfejsi\MejlInterfejs;

class MejlSwift implements MejlInterfejs
{
    public function __construct()
    {
    }

    public function posalji($kome, $imeFajla)
    {
        try {
            $transport = (new Swift_SmtpTransport('smtp.mailtrap.io', 2525))
                ->setUsername(MAILTRAPUSER)
                ->setPassword(MAILTRAPPASS);
            $mailer = new Swift_Mailer($transport);

            $message = (new Swift_Message())

                ->setSubject('Putno osiguranje')

                ->setFrom(['aleksandar@markovic.com'])

                ->setTo([$kome]);

            $message->attach(Swift_Attachment::fromPath('./' . $imeFajla));

            $mailer->send($message);
        } catch (Throwable $e) {
            die("Doslo je do greske,mejl nije poslat");
        }
    }
}
