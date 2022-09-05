<?php

namespace app\models;

use app\Helpers;
/**
 * Model containing the information for each insurance product
 */
class Product {
    public string $id;
    public string $name;
    public string $description;
    public string $type;
    public $suppliers;

    /**
     * Given a product ID code, fetch all information about the product and build the model
     *
     * @param string $id
     */
    function __construct(string $id) {
        $this->id = $id;
        $responseData = Helpers::queryApi('info', ['id' => $id]);
        $this->name = $responseData->{$id}->name ?? '';
        $this->description = $responseData->{$id}->description ?? '';
        $this->type = $responseData->{$id}->type ?? '';
        $this->suppliers = $responseData->{$id}->suppliers ?? '';
    }
    
    /**
     * Fetch a list of all available products
     *
     * @param boolean $parse When true, returned data will be parsed into Product models
     * @return Array
     */
    public static function list(bool $parse = true) : Array {
        $responseData = Helpers::queryApi('list');
        $productList = (array)$responseData->products ?? [];
        if ($parse) {
            return array_map(function($id) {
                return new Product($id);
            }, array_keys($productList));
        } else {
            return $productList;
        }
    }

}