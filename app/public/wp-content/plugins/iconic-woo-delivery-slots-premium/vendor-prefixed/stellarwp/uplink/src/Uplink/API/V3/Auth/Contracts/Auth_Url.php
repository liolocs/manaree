<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by James Kemp on 24-September-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */ declare( strict_types=1 );

namespace Iconic_WDS_NS\StellarWP\Uplink\API\V3\Auth\Contracts;

interface Auth_Url {

	/**
	 * Retrieve an Origin's auth url, if it exists.
	 *
	 * @param  string  $slug  The product slug.
	 *
	 * @return string
	 */
	public function get( string $slug ): string;

}
