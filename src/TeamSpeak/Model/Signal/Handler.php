<?php
/**
 * TeamSpeak 3 PHP Framework
 *
 * Refer to the LICENSE file for license information
 *
 * @author    Aaron Scherer <aaron@st-gc.org>
 * @copyright Copyright (c) 2013 Seductive Turtle gaming Community. All rights reserved.
 */
namespace TeamSpeak\Model\Signal;

use TeamSpeak\Exception\Model\SignalException;

/**
 * Helper class providing handler functions for signals.
 */
class Handler
{

	/**
	 * Stores the name of the subscribed signal.
	 *
	 * @var string
	 */
	protected $signal = null;

	/**
	 * Stores the callback function for the subscribed signal.
	 *
	 * @var mixed
	 */
	protected $callback = null;

	/**
	 * The TeamSpeak3_Helper_Signal_Handler constructor.
	 *
	 * @param  string $signal
	 * @param  mixed  $callback
	 *
	 * @throws SignalException
	 * @return Handler
	 */
	public function __construct( $signal, $callback )
	{

		$this->signal = (string)$signal;

		if ( !is_callable( $callback ) ) {
			throw new SignalException( "invalid callback specified for signal '" . $signal . "'" );
		}

		$this->callback = $callback;
	}

	/**
	 * Invoke the signal handler.
	 *
	 * @param  array $args
	 *
	 * @return mixed
	 */
	public function call( array $args = array() )
	{

		return call_user_func_array( $this->callback, $args );
	}
}
