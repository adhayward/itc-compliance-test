<?php
/**
 * Load a list of products from the API and display them on screen
 */
require_once "autoload.php";

use app\Helpers;
use app\models\Product;

$products = Product::list(true);
echo Helpers::render('list', ['data'=>$products]);
