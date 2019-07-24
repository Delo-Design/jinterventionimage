<?php
/**
 * @package    jinterventionimage
 * @author     Dmitry Tsymbal <cymbal@delo-design.ru>
 * @copyright  Copyright © 2019 Delo Design. All rights reserved.
 * @license    GNU General Public License version 3 or later; see license.txt
 * @link       https://delo-design.ru
 */


defined('_JEXEC') or die;

use Intervention\Image\ImageManagerStatic as Image;

class JInterventionimage
{

	/**
	 * @param array $options
	 *
	 * @return \Intervention\Image\ImageManager
	 *
	 * @since version
	 */
	public static function getInstance($options = array(['driver' => 'gd']))
	{
		JLoader::registerNamespace('Intervention\Image', JPATH_LIBRARIES . DIRECTORY_SEPARATOR . 'jinterventionimage');
		return Image::configure($options);
	}


}