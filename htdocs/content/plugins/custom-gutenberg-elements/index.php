<?php

/**
 * Plugin Name: Custom Gutenberg Elements
 * Plugin URI: https://github.com/WordPress/gutenberg
 * Description: This is a plugin demonstrating power of blocks for the Gutenberg editor.
 * Version: 1.0.1
 * Author: RJL
 *
 * @package gutenberg-examples
 */

use Illuminate\Support\Facades\View;

defined('ABSPATH') || exit;


if (!defined('CGE_FILE')) {
	define('CGE_FILE', __FILE__);
}

/*LAYOUTS BLOCK*/
include 'cge-full-layout-content/index.php';
include 'cge-layout-content/index.php';
include 'cge-layout-sidebar/index.php';

/*LAYOUT CONTENT BLOCK*/
include 'cge-content-breadcrumbs/index.php';
include 'cge-full-page-heading/index.php';
include 'cge-full-page-banner/index.php';
include 'cge-content-bloc-type-1/index.php';
include 'cge-content-bloc-type-2/index.php';
include 'cge-content-bloc-type-3/index.php';
include 'cge-content-bloc-type-4/index.php';
include 'cge-content-bloc-type-5/index.php';
include 'cge-content-page-brands/index.php';
include 'cge-content-page-brands-images/index.php';
include 'cge-content-bloc-team/index.php';
include 'cge-content-title-type-1/index.php';
include 'cge-full-bloc-contact/index.php';
include 'cge-full-reinsurance/index.php';
include 'cge-full-footer-social/index.php';
include 'cge-content-inspiration-catalogue/index.php';
include 'cge-content-inspiration-pro/index.php';