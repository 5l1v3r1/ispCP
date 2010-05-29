<?php
/**
 * ispCP ω (OMEGA) a Virtual Hosting Control System
 *
 * @copyright	2006-2010 by ispCP | http://isp-control.net
 * @version		SVN: $Id$
 * @link		http://isp-control.net
 * @author		ispCP team
 *
 * @license
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * The Original Code is "ispCP - ISP Control Panel".
 *
 * The Initial Developer of the Original Code is ispCP Team.
 * Portions created by Initial Developer are Copyright (C) 2006-2010 by
 * isp Control Panel. All Rights Reserved.
 */

/**
 * @see IspCP_ConfigHandler
 */
require_once  INCLUDEPATH . '/IspCP/ConfigHandler.php';

/**
 * Class to handle configuration parameters from a flat file
 *
 * IspCP_ConfigHandler adapter to handle configuration parameters that are stored
 * in a flat file where each pair of key values are separated by the equal sign.
 *
 * By default, this object parse the default ispCP configuration file.
 *
 * @See ispCP_ConfigHandler
 */
class ispCP_ConfigHandler_File extends ispCP_ConfigHandler {

	/**
	 * Configuration file path
	 *
	 * @var string Configuration file path
	 */
	protected $_path_file;

	/**
	 * Loads the ispCP config file (default directory: /etc/ispcp/ispcp.conf)
	 *
	 * @param string $path_file Configuration file path
	 * @throws Exception
	 * @return void
	 */
	public function __construct($path_file = null) {

		if(is_null($path_file)) {
			switch (PHP_OS) {
				case 'FreeBSD':
				case 'OpenBSD':
				case 'NetBSD':
					$path_file = '/usr/local/etc/ispcp/ispcp.conf';
					break;
				default: 
					$path_file = '/etc/ispcp/ispcp.conf';
			}
		}

		$this->_path_file = $path_file;

		parent::__construct($this->parseFile());
	}

	/**
	 * Opens a configuration file and parses its KEY = Value pairs into the
	 * {@link IspCP_ConfigHangler::parameters} array.
	 *
	 * @throws Exception
	 * @return Configuration parameters
	 */
	private function parseFile() {

		$fd = @file_get_contents($this->_path_file);

		if ($fd === false) {
			throw new Exception(
				"Unable to open the configuration file `$path_file`!"
			);
		}

		$lines = explode(PHP_EOL, $fd);

		foreach ($lines as $line) {
			if (!empty($line) && $line[0] != '#' && strpos($line, '=')) {
				list($key, $value) = explode('=', $line, 2);

				$parameters[trim($key)] = trim($value);
			}
		}

		return $parameters;
	}
}