<?php

namespace App\Services;

use App\Models\Emailstext;
use Mail;
use DB;

class MailingService
{
    public function basic_email($email, $code) {
      $data = array('name'=>"Potwierdzenie głosowania", 'email'=>$email, 'code'=>$code);
   
      try {
          Mail::send(['html'=>'emails.mail'], ['mail_data'=>$data], function($message)  use ($data) {
             $message->to($data['email'], substr($data['email'],0,strpos('@', $data['email'])))->subject
                ('Potwierdzenie wyboru głosów');
             $message->from('lista@marcinstefanski.pl','Lista LARAMACEST');
          });
          Mail::send(['html'=>'emails.basemail'], ['mail_data'=>$data], function($message)  use ($data) {
             $message->to('marcin@marcinstefanski.pl', 'Lista Admin')->subject
                ('Informacja o oddaniu głosów');
             $message->from('lista@marcinstefanski.pl','Lista LARAMACEST');
          });
          return "Dziękujemy. Wysłano wiadomość - sprawdź skrzynkę, aby potwierdzić";
      } catch (Exception $e) {
          return 'Wystąpił błąd w czasie wysyłki wiadomości';
      }
   }
}
