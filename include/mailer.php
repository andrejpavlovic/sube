<?php

class mailer {
	function mailer($sendto, $subject, $message ) {
    $this->sendto = $sendto;
    $this->subject = $subject;
    $this->message = $message;
	}

  function setTo($to) {
    $this->to = $to;
  }

  function setFrom($from) {
    $this->from = $from;
  }

  function setBcc($bcc) {
    $this->bcc = $bcc;
  }

  function send() {
    // To send HTML mail, the Content-type header must be set
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";

    // Additional headers
    if (0 != strcmp($this->to,'')) $headers .= 'To: '.$this->to."\r\n";
    
    if (0 != strcmp($this->from,'')) $headers .= 'From: '.$this->from."\r\n";
    
    if (0 != strcmp($this->cc,'')) $headers .= 'Cc: '.$this->cc."\r\n"; 
    
    if (0 != strcmp($this->bcc,'')) $headers .= 'Bcc:'.$this->bcc."\r\n";

    // Mail it
    return mail($this->sendto, $this->subject, $this->message, $headers);
  }

}


?> 