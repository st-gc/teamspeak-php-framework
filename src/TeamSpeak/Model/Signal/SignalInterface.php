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

use TeamSpeak\Exception\ModelException;
use TeamSpeak\Adapter\AbstractAdapter;
use TeamSpeak\Adapter\ServerQuery\Reply;
use TeamSpeak\Adapter\ServerQuery\Event;
use TeamSpeak\Node\HostNode;
use TeamSpeak\Node\ServerNode;
use TeamSpeak\Adapter\FileTransferAdapter;


/**
 * Interface class describing the layout for Signal Helper callbacks.
 */
interface SignalInterface
{
	/**
	 * Possible callback for '<adapter>Connected' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("serverqueryConnected", array($object, "onConnect"));
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("filetransferConnected", array($object, "onConnect"));
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("blacklistConnected", array($object, "onConnect"));
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("updateConnected", array($object, "onConnect"));
	 *
	 * @param  AbstractAdapter $adapter
	 *
	 * @return void
	 */
	public function onConnect( AbstractAdapter $adapter );

	/**
	 * Possible callback for '<adapter>Disconnected' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("serverqueryDisconnected", array($object, "onDisconnect"));
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("filetransferDisconnected", array($object, "onDisconnect"));
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("blacklistDisconnected", array($object, "onDisconnect"));
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("updateDisconnected", array($object, "onDisconnect"));
	 *
	 * @return void
	 */
	public function onDisconnect();

	/**
	 * Possible callback for 'serverqueryCommandStarted' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("serverqueryCommandStarted", array($object, "onCommandStarted"));
	 *
	 * @param  string $cmd
	 *
	 * @return void
	 */
	public function onCommandStarted( $cmd );

	/**
	 * Possible callback for 'serverqueryCommandFinished' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("serverqueryCommandFinished", array($object, "onCommandFinished"));
	 *
	 * @param  string                               $cmd
	 * @param  Reply $reply
	 *
	 * @return void
	 */
	public function onCommandFinished( $cmd, Reply $reply );

	/**
	 * Possible callback for 'notifyEvent' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("notifyEvent", array($object, "onEvent"));
	 *
	 * @param  Event    $event
	 * @param  HostNode $host
	 *
	 * @return void
	 */
	public function onEvent( Event $event, HostNode $host );

	/**
	 * Possible callback for 'notifyError' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("notifyError", array($object, "onError"));
	 *
	 * @param  Reply $reply
	 *
	 * @return void
	 */
	public function onError( Reply $reply );

	/**
	 * Possible callback for 'notifyServerselected' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("notifyServerselected", array($object, "onServerselected"));
	 *
	 * @param HostNode $host
	 *
	 * @return void
	 */
	public function onServerselected( HostNode $host );

	/**
	 * Possible callback for 'notifyServercreated' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("notifyServercreated", array($object, "onServercreated"));
	 *
	 * @param  HostNode $host
	 * @param  integer              $sid
	 *
	 * @return void
	 */
	public function onServercreated( HostNode $host, $sid );

	/**
	 * Possible callback for 'notifyServerdeleted' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("notifyServerdeleted", array($object, "onServerdeleted"));
	 *
	 * @param  HostNode $host
	 * @param  integer              $sid
	 *
	 * @return void
	 */
	public function onServerdeleted( HostNode $host, $sid );

	/**
	 * Possible callback for 'notifyServerstarted' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("notifyServerstarted", array($object, "onServerstarted"));
	 *
	 * @param  HostNode $host
	 * @param  integer              $sid
	 *
	 * @return void
	 */
	public function onServerstarted( HostNode $host, $sid );

	/**
	 * Possible callback for 'notifyServerstopped' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("notifyServerstopped", array($object, "onServerstopped"));
	 *
	 * @param  HostNode $host
	 * @param  integer              $sid
	 *
	 * @return void
	 */
	public function onServerstopped( HostNode $host, $sid );

	/**
	 * Possible callback for 'notifyServershutdown' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("notifyServershutdown", array($object, "onServershutdown"));
	 *
	 * @param  HostNode $host
	 *
	 * @return void
	 */
	public function onServershutdown( HostNode $host );

	/**
	 * Possible callback for 'notifyLogin' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("notifyLogin", array($object, "onLogin"));
	 *
	 * @param  HostNode $host
	 *
	 * @return void
	 */
	public function onLogin( HostNode $host );

	/**
	 * Possible callback for 'notifyLogout' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("notifyLogout", array($object, "onLogout"));
	 *
	 * @param  HostNode $host
	 *
	 * @return void
	 */
	public function onLogout( HostNode $host );

	/**
	 * Possible callback for 'notifyTokencreated' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("notifyTokencreated", array($object, "onTokencreated"));
	 *
	 * @param  ServerNode $server
	 * @param  string                 $token
	 *
	 * @return void
	 */
	public function onTokencreated( ServerNode $server, $token );

	/**
	 * Possible callback for 'filetransferHandshake' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("filetransferHandshake", array($object, "onFtHandshake"));
	 *
	 * @param  FileTransferAdapter $adapter
	 *
	 * @return void
	 */
	public function onFtHandshake( FileTransferAdapter $adapter );

	/**
	 * Possible callback for 'filetransferUploadStarted' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("filetransferUploadStarted", array($object, "onFtUploadStarted"));
	 *
	 * @param  string  $ftkey
	 * @param  integer $seek
	 * @param  integer $size
	 *
	 * @return void
	 */
	public function onFtUploadStarted( $ftkey, $seek, $size );

	/**
	 * Possible callback for 'filetransferUploadProgress' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("filetransferUploadProgress", array($object, "onFtUploadProgress"));
	 *
	 * @param  string  $ftkey
	 * @param  integer $seek
	 * @param  integer $size
	 *
	 * @return void
	 */
	public function onFtUploadProgress( $ftkey, $seek, $size );

	/**
	 * Possible callback for 'filetransferUploadFinished' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("filetransferUploadFinished", array($object, "onFtUploadFinished"));
	 *
	 * @param  string  $ftkey
	 * @param  integer $seek
	 * @param  integer $size
	 *
	 * @return void
	 */
	public function onFtUploadFinished( $ftkey, $seek, $size );

	/**
	 * Possible callback for 'filetransferDownloadStarted' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("filetransferDownloadStarted", array($object, "onFtDownloadStarted"));
	 *
	 * @param  string  $ftkey
	 * @param  integer $buff
	 * @param  integer $size
	 *
	 * @return void
	 */
	public function onFtDownloadStarted( $ftkey, $buff, $size );

	/**
	 * Possible callback for 'filetransferDownloadProgress' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("filetransferDownloadProgress", array($object, "onFtDownloadProgress"));
	 *
	 * @param  string  $ftkey
	 * @param  integer $buff
	 * @param  integer $size
	 *
	 * @return void
	 */
	public function onFtDownloadProgress( $ftkey, $buff, $size );

	/**
	 * Possible callback for 'filetransferDownloadFinished' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("filetransferDownloadFinished", array($object, "onFtDownloadFinished"));
	 *
	 * @param  string  $ftkey
	 * @param  integer $buff
	 * @param  integer $size
	 *
	 * @return void
	 */
	public function onFtDownloadFinished( $ftkey, $buff, $size );

	/**
	 * Possible callback for '<adapter>DataRead' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("serverqueryDataRead", array($object, "onDebugDataRead"));
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("filetransferDataRead", array($object, "onDebugDataRead"));
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("blacklistDataRead", array($object, "onDebugDataRead"));
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("updateDataRead", array($object, "onDebugDataRead"));
	 *
	 * @param  string $data
	 *
	 * @return void
	 */
	public function onDebugDataRead( $data );

	/**
	 * Possible callback for '<adapter>DataSend' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("serverqueryDataSend", array($object, "onDebugDataSend"));
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("filetransferDataSend", array($object, "onDebugDataSend"));
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("blacklistDataSend", array($object, "onDebugDataSend"));
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("updateDataSend", array($object, "onDebugDataSend"));
	 *
	 * @param  string $data
	 *
	 * @return void
	 */
	public function onDebugDataSend( $data );

	/**
	 * Possible callback for '<adapter>WaitTimeout' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("serverqueryWaitTimeout", array($object, "onWaitTimeout"));
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("filetransferWaitTimeout", array($object, "onWaitTimeout"));
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("blacklistWaitTimeout", array($object, "onWaitTimeout"));
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("updateWaitTimeout", array($object, "onWaitTimeout"));
	 *
	 * @param  integer         $time
	 * @param  AbstractAdapter $adapter
	 *
	 * @return void
	 */
	public function onWaitTimeout( $time, AbstractAdapter $adapter );

	/**
	 * Possible callback for 'errorException' signals.
	 *
	 * === Examples ===
	 *   - \TeamSpeak\Modal\Signal::getInstance()->subscribe("errorException", array($object, "onException"));
	 *
	 * @param ModelException $e
	 *
	 * @return void
	 */
	public function onException( ModelException $e );
}
