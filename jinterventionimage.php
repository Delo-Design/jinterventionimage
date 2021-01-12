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
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\Path;

class JInterventionimage
{

	/**
	 * @param array $options
	 *
	 * @return \Intervention\Image\ImageManager
	 *
	 * @since version
	 */
	public static function getInstance($options = ['driver' => 'gd'])
	{
		JLoader::registerNamespace('Intervention\Image', JPATH_LIBRARIES . DIRECTORY_SEPARATOR . 'jinterventionimage');
		return Image::configure($options);
	}


    /**
     * @param $source
     * @param string $algorithm
     * @param null $thumb_path
     * @param $maxWidth
     * @param $maxHeight
     *
     * @return mixed|string|string[]
     *
     * @since version
     */
    public static function generateThumb($source, $maxWidth, $maxHeight, $algorithm = 'resize', $thumb_path = null)
    {
        $source = str_replace(JPATH_ROOT . DIRECTORY_SEPARATOR, '', $source);
        $paths = explode(DIRECTORY_SEPARATOR, $source);
        $file = array_pop($paths);
        $fileSplit = explode('.', $file);
        $fileExt = mb_strtolower(array_pop($fileSplit));
        $extAccept = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if(!in_array($fileExt, $extAccept))
        {
            return $file;
        }

        if($thumb_path === null)
        {
            $pathThumb = implode(DIRECTORY_SEPARATOR, array_merge($paths, ['_thumb']));
            $pathFileThumb = implode(DIRECTORY_SEPARATOR, array_merge($paths, ['_thumb'])) . DIRECTORY_SEPARATOR . $file;
        }
        else
        {
            $pathThumb = Path::clean($thumb_path . DIRECTORY_SEPARATOR . '_thumb');
            $pathFileThumb = Path::clean($thumb_path . DIRECTORY_SEPARATOR . '_thumb' . DIRECTORY_SEPARATOR . $file);
        }

        $params = [];

        $fullPathThumb =  Path::clean(JPATH_ROOT . DIRECTORY_SEPARATOR . $pathThumb . DIRECTORY_SEPARATOR . $file);

        //если есть превью, то отдаем ссылку на файл
        if(file_exists($fullPathThumb))
        {
            return $pathFileThumb;
        }


        //если нет, генерируем превью

        //проверяем создан ли каталог для превью
        $pathThumbSplit = explode(DIRECTORY_SEPARATOR, $pathThumb);
        $pathThumbCurrent = '';
        foreach ($pathThumbSplit as $pathCurrentCheck)
        {
            $pathThumbCurrent .= DIRECTORY_SEPARATOR . $pathCurrentCheck;
            $pathThumbCheck = Path::clean(JPATH_ROOT . DIRECTORY_SEPARATOR . $pathThumbCurrent);
            if(!file_exists($pathThumbCheck))
            {
                //создаем каталог
                Folder::create($pathThumbCheck);
            }
        }


        if(copy(JPATH_ROOT . DIRECTORY_SEPARATOR . $source, $fullPathThumb))
        {

            if ($algorithm === 'fit')
            {
                self::fit($fullPathThumb, $maxWidth, $maxHeight);
            }

            if ($algorithm === 'bestfit')
            {
                self::bestFit($fullPathThumb, $maxWidth, $maxHeight);
            }

            if ($algorithm === 'resize')
            {
                self::resize($fullPathThumb, $maxWidth, $maxHeight);
            }

        }


        return $pathFileThumb;

    }


    /**
     * @param $file
     * @param null $widthFit
     * @param null $heightFit
     *
     *
     * @since version
     */
    public static function resize($file, $widthFit = null, $heightFit = null)
    {
        list($width, $height, $type, $attr) = getimagesize($file);
        $newWidth = $width;
        $newHeight = $height;
        $maxWidth = (int)$widthFit;
        $maxHeight = (int)$heightFit;

        $manager = self::getInstance(['driver' => self::getNameDriver()]);
        $manager
            ->make($file)
            ->resize($maxWidth, $maxHeight, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->resizeCanvas($maxWidth, $maxHeight)
            ->save($file);

    }


    /**
     * @param $file
     * @param $widthFit
     * @param $heightFit
     *
     *
     * @since version
     */
    public static function bestFit($file, $widthFit, $heightFit)
    {
        list($width, $height, $type, $attr) = getimagesize($file);
        $newWidth = $width;
        $newHeight = $height;
        $maxWidth = (int)$widthFit;
        $maxHeight = (int)$heightFit;

        $ratio = $width / $height;

        if($width > $maxWidth)
        {
            $newWidth = $maxWidth;
            $newHeight = round($newWidth / $ratio);
        }

        if($newHeight > $maxHeight)
        {
            $newHeight = $maxHeight;
            $newWidth = round($newHeight * $ratio);
        }


        $manager = self::getInstance(['driver' => self::getNameDriver()]);
        $manager
            ->make($file)
            ->resize($newWidth, $newHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($file);

    }


    /**
     * @param $file
     * @param $widthFit
     * @param $heightFit
     *
     *
     * @since version
     */
    public static function fit($file, $widthFit, $heightFit )
    {
        list($width, $height, $type, $attr) = getimagesize($file);
        $newWidth = $width;
        $newHeight = $height;
        $maxWidth = (int)$widthFit;
        $maxHeight = (int)$heightFit;

        $manager = self::getInstance(['driver' => self::getNameDriver()]);
        $manager
            ->make($file)
            ->fit($maxWidth, $maxHeight, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($file);

    }


    /**
     *
     * @return string
     *
     * @since version
     */
    public static function getNameDriver()
    {
        if (extension_loaded('imagick'))
        {
            return 'imagick';
        }

        return 'gd';
    }


}