<?php

/**
 * Edit this file in order to configure additional
 * image sizes for your theme.
 *
 * @see https://developer.wordpress.org/reference/functions/add_image_size/
 *
 * @key string The size name.
 *
 * @param  int  $width  The image width.
 * @param  int  $height  The image height.
 * @param  bool|array  $crop  Crop option. Since 3.9, define a crop position with an array.
 * @param  bool|string  $media  Add to media selection dropdown. Make it also available
 *                            to the media custom field. If string, used as the display name ;)
 */
return [
    'feature-blog-thumbnail' => [490, 300, ['center', 'center']],
    'agenda-lightbox-thumbnail' => [285, 218, ['center', 'center']],
    'single-product-thumbnail' => [460, 460, ['center', 'center']],
    'loop-product-thumbnail' => [185, 185, ['center', 'center']],
    'slide-item-product-thumbnail' => [80, 80, ['center', 'center']],
    'other-product-thumbnail' => [135, 135, ['center', 'center']],
    'picto-color' => [16, 16, ['center', 'center']],
];
