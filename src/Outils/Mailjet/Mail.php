<?php

namespace App\Outils\Mailjet;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
	private $api_key = '2e14aa067669e27803c47cad4c19d30e';
	private $api_key_secret = '51093a1bd58f0be0ced37024e7fed888';

	public function send($to_email, $to_name,  $subjet, $content)
	{
		$mj = new Client($this->api_key, $this->api_key_secret, true, ['version' => 'v3.1']);
		$body = [
			'Messages' => [
				[
					'From' => [
						'Email' => "aby.creation@gmail.com",
						'Name' => "A.C Beauty"
					],
					'To' => [
						[
							'Email' => $to_email,
							'Name' => $to_name
						]
					],

					"TemplateID" => 2674110,
					"TemplateLanguage" => true,
					"Subject" => $subjet,
					"Variables" => [
						"content" => $content
					]
				]

			]
		];

		$response = $mj->post(Resources::$Email, ['body' => $body]);
		$response->success();
	}
}