<?php defined('_JEXEC') or die;

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