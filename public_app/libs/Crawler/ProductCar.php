<?php

namespace Crawler;

class ProductCar extends Product
{
    /**
     * Additional fields
     */

    // Opel, Volkswagen
    public $brand = "n/a";

    // Corsa, Golf
    public $model = "n/a";

    // f.e. 1830 cm3
    public $engine = 0;

    // f.e. 2010
    public $year = 0;

    // f.e. 120 KM
    public $hp = 0;

    // f.e. 120529 km
    public $mileage = 0;

    // f.e. "white"
    public $color = "n/a";

    // f.e. 1 - car is damaged
    public $damaged = 0;

    // f.e. 1 - automatic gears
    public $automated = 0;

    // diesel, oil etc.
    public $fuel;

    // Germany, France
    public $countryFrom = "n/a";

    // car images
    public $images = [];

    public static $fuelTypes = 
    [
        'Benzyna' => 0,
        'Diesel' => 1,
        'LPG' => 2,
        'CNG i Hybryda' => 3
    ];

    /**
     * Save to the database method
     */
    public function save($dbHandler)
    {
        $images = implode(',', $this->images);
        $check = $dbHandler->query("SELECT * FROM cars WHERE original_id = ? LIMIT 1", [$this->id]);
        if($check->num_rows() == 0)
            $dbHandler->query("INSERT INTO cars VALUES(NULL, ?, ?, ?, ?, ?, ? ,? ,? ,? ,? ,?, ?, ?, ?, ?, ?, ?, ?, NULL)",
                [$this->brand, $this->model, $this->engine, $this->year, $this->hp, $this->mileage, $this->color, 
                $this->damaged, $this->automated, $this->fuel, $this->countryFrom, $this->url, $this->id, $this->price, 
                $this->region, $this->city, $this->description, $images]);
    }

    /**
     * Parse DOM for OTOMOTO
     */
    public static function parseDetailsOtomoto($product, $label, $value) {
        if(strpos($label, "Marka pojazdu") !== false) {
            $product->brand = $value;
        } else if(strpos($label, "Model pojazdu") !== false) {
            $product->model = $value;
        } else if(strpos($label, "Rok produkcji") !== false) {
            $product->year = intval($value);
        } else if(strpos($label, "Pojemność skokowa") !== false) {
            $product->engine = intval(str_replace(' ', '', $value));
        } else if(strpos($label, "Rodzaj paliwa") !== false) {
            $product->fuel = self::$fuelTypes[$value];
        } else if(strpos($label, "Moc") !== false) {
            $product->hp = intval($value);
        } else if(strpos($label, "Przebieg") !== false) {
            $product->mileage = intval(str_replace(' ', '', $value));
        } else if(strpos($label, "Typ") !== false) {
           // $product->model = $value;
        } else if(strpos($label, "Kolor") !== false) {
            $product->color = $value;
        } else if(strpos($label, "Bezwypadkowy") !== false) {
            $product->damaged = $value === "Tak" ? 0 : 1;
        } else if(strpos($label, "Skrzynia biegów") !== false) {
            $product->automated = strpos($value, "Automatyczna") !== false ? 1 : 0;
        }
    }

    /**
     * Parse DOM for OLX
     */
    public static function parseDetailsTable($array, $index, $product)
    {
        if(strpos($array[$index], "Marka") !== false) {
            $product->brand = $array[$index + 1];
        }
        else if(strpos($array[$index], "Model") !== false) {
            $product->model = $array[$index + 1];
        }
        else if(strpos($array[$index], "Rok produkcji") !== false) {
            $product->year = intval($array[$index + 1]);
        }
        else if(strpos($array[$index], "Poj. silnika") !== false) {
            $product->engine = intval(str_replace(' ', '', $array[$index + 1]));
        }
        else if(strpos($array[$index], "Paliwo") !== false) {
            $product->fuel = self::$fuelTypes[$array[$index + 1]];
        }
        else if(strpos($array[$index], "Moc silnika") !== false) {
            $product->hp = intval($array[$index + 1]);
        }
        else if(strpos($array[$index], "Przebieg") !== false) {
            $product->mileage = intval(str_replace(' ', '', $array[$index + 1]));
        }
        else if(strpos($array[$index], "Typ nadwozia") !== false) {
            //var_dump("Nadwozie: " . $array[$index + 1]);
        }
        else if(strpos($array[$index], "Kolor") !== false) {
            $product->color = $array[$index + 1];
        }
        else if(strpos($array[$index], "Stan techniczny") !== false) {
            $product->damaged = $array[$index + 1] === "Uszkodzony" ? 1 : 0;
        }
        else if(strpos($array[$index], "Skrzynia biegów") !== false) {
            $product->automated = $array[$index + 1] === "Automatyczna" ? 1 : 0;
        }
        else if(strpos($array[$index], "Kraj pochodzenia") !== false) {
            $product->countryFrom = $array[$index + 1];
        }
    }
}