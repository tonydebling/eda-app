<?php

namespace Target\Mail;

class Mailer
{
	protected $view;
	protected $config;
	protected $mg;
	
	public function __construct($view, $config, $mgClient)
	{
		$this->view = $view;
		$this->config = $config;
		$this->mgClient = $mgClient;
	}
	
	public function send($to, $subject, $template, $data)
	{		
		$this->view->appendData($data);
		$body = $this->view->render($template);		
		$domain = $this->config->get('mail.domain');		
		$message = [
			'from'		=> $this->config->get('mail.from'),
			'to'		=> $to,
			'subject'	=> $subject,
			'html'		=> $body,		
		];
		$this->mgClient->sendMessage($domain, $message);
		return;

	}
}




