<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/
	'facebook' => [
	    'client_id' => '1973487292913203',
	    'client_secret' => '3bc9f936edabc223a818a2a0d7614fe8',
	    'redirect' => 'http://www.schemk.com/auth/facebook/callback',
	],
	'mailgun' => [
		'domain' => 'sandboxacbb02e2cf1d45238c5e6854bf19a8e4.mailgun.org',
		'secret' => 'key-ed7a05c20c961413ac2bf32ccfeadfe9',
	],

	'mandrill' => [
		'secret' => '',
	],

	'ses' => [
		'key' => '',
		'secret' => '',
		'region' => 'us-east-1',
	],

	'stripe' => [
		'model'  => 'App\User',
		'secret' => '',
	],

];
