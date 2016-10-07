<?php 

class Email{

	public static function send($conf){
		try {
		    $mandrill = new Mandrill(config('email.mandrill.app_key'));
		    $message = array(
		        'from_email' => isset($conf['from_email']) ? $conf['from_email'] : config('email.from_email'),
		        'from_name' => isset($conf['from_name']) ? $conf['from_name'] : config('email.from_name'),
		        'to' => array(
		            array(
		                'email' => $conf['to_email'],
		                'name' => $conf['to_name'],
		            )
		        ),
		        'html' => $conf['html'],
		        'subject' => $conf['subject'],
		        'headers' => array('Reply-To' => isset($conf['from_email']) ? $conf['from_email'] : config('email.from_email')),
		    );
		    $async = false;
		    $result = $mandrill->messages->send($message, $async);
		    return $result;
		} catch(Mandrill_Error $e) {
		    // Mandrill errors are thrown as exceptions
		    echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
		    // A mandrill error occurred: Mandrill_PaymentRequired - This feature is only available for accounts with a positive balance.
		    throw $e;
		}
	}
}