<?php
/**
 * TeamSpeak 3 PHP Framework
 *
 * Refer to the LICENSE file for license information
 *
 * @author    Aaron Scherer <aaron@st-gc.org>
 * @copyright Copyright (c) 2013 Seductive Turtle gaming Community. All rights reserved.
 */
namespace TeamSpeak\Viewer;

use TeamSpeak\Node\AbstractNode;

/**
 * Interface class describing a TeamSpeak 3 viewer.
 */
interface ViewerInterface
{
	/**
	 * Returns the code needed to display a node in a TeamSpeak 3 viewer.
	 *
	 * @param  AbstractNode $node
	 * @param  array        $siblings
	 *
	 * @return string
	 */
	public function fetchObject( AbstractNode $node, array $siblings = array() );
}
