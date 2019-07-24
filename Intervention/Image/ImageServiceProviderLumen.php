<?php namespace Intervention\Image;
/**
 * @package    Intervention Image
 * @author     Oliver Vogel <info@olivervogel.com>
 * @copyright  Copyright 2015 Oliver Vogel
 * @license    MIT License; see license.txt
 * @link       http://image.intervention.io
 */

defined('_JEXEC') or die;

use Illuminate\Support\ServiceProvider;

class ImageServiceProviderLumen extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        // merge default config
        $this->mergeConfigFrom(
          __DIR__.'/../../config/config.php',
          'image'
        );

        // set configuration
        $app->configure('image');

        // create image
        $app->singleton('image',function ($app) {
            return new ImageManager($app['config']->get('image'));
        });

        $app->alias('image', 'Intervention\Image\ImageManager');
    }
}
