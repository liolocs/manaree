<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by James Kemp on 24-September-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Iconic_WDS_NS\StellarWP\Uplink\Messages;

class Unreachable extends Message_Abstract {
	/**
	 * @inheritDoc
	 */
	public function get(): string {
		$message = esc_html__( 'Sorry, key validation server is not available.', 'jckwds' );

		return $message;
	}
}
