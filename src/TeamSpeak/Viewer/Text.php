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

use TeamSpeak\Model\String;
use TeamSpeak\Node\AbstractNode;

/**
 * Renders nodes used in ASCII-based TeamSpeak 3 viewers.
 */
class TextViewer implements ViewerInterface
{
	/**
	 * A pre-defined pattern used to display a node in a TeamSpeak 3 viewer.
	 *
	 * @var string
	 */
	protected $pattern = "%0%1 %2\n";

	/**
	 * Returns the code needed to display a node in a TeamSpeak 3 viewer.
	 *
	 * @param  AbstractNode $node
	 * @param  array                    $siblings
	 *
	 * @return string
	 */
	public function fetchObject( AbstractNode $node, array $siblings = array() )
	{
		$this->currObj = $node;
		$this->currSib = $siblings;

		$args = array(
			$this->getPrefix(),
			$this->getCorpusIcon(),
			$this->getCorpusName(),
		);

		return String::factory( $this->pattern )->arg( $args );
	}

	/**
	 * Returns the ASCII string to display the prefix of the current node.
	 *
	 * @return string
	 */
	protected function getPrefix()
	{
		$prefix = "";

		if ( count( $this->currSib ) ) {
			$last = array_pop( $this->currSib );

			foreach ( $this->currSib as $sibling ) {
				$prefix .= ( $sibling ) ? "| " : "  ";
			}

			$prefix .= ( $last ) ? "\\-" : "|-";
		}

		return $prefix;
	}

	/**
	 * Returns an ASCII string which can be used to display the status icon for a
	 * TeamSpeak_Node_Abstract object.
	 *
	 * @return string
	 */
	protected function getCorpusIcon()
	{
		return $this->currObj->getSymbol();
	}

	/**
	 * Returns a string for the current corpus element which contains the display name
	 * for the current TeamSpeak_Node_Abstract object.
	 *
	 * @return string
	 */
	protected function getCorpusName()
	{
		return $this->currObj;
	}
}
