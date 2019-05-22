<?php

namespace Crawler;

class Product
{
    public $id;
    public $price;
    public $url;
    public $region;
    public $city;
    public $description;

    /**
     * Save to the database method
     */
    public function save($dbHandler)
    {

    }
}