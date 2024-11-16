<?php

namespace App\Http\Middleware;

use Themosis\Core\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
	/**
	 * URIs that should be excluded from CSRF verification.
	 *
	 * @var array
	 */
	protected $except = [
		'products/*',
		'en/products/*',
		'produits/*',
        'en/produits/*',
		'paiement/',
		'en/paiement/',
		'payment/*',
		'en/payment/*',
		'sso-reed',
		'sso-reed/*',
	];
}
