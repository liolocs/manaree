<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by James Kemp on 25-August-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Iconic_WDS_NS\StellarWP\Uplink\Messages;

class Valid_Key_New extends Valid_Key {
	/**
	 * @inheritDoc
	 */
	public function get(): string {
		$message = sprintf(
			__( 'Thanks for setting up a valid key. It will expire on %s.', '%TEXTDOMAIN%' ),
			$this->expiration
		);

		return esc_html( $message );
	}
}
