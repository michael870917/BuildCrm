<?php

/**
 * @author michael
 * @copyright 2014
 */
class AccionesValidacion {

    public function __construct() {
        
    }

    public function __destruct() {
        
    }

    private $_string;
    private $_reemplazaBusqueda;

    public function WhereAlternativo($arreglo, $arreglo2) {
        $this->_string = (count($arreglo2) > 0 ? " AND " : "(");
        $m = 0;
        foreach ($arreglo as $key => $value) {
            if (is_array($value)) {
                $key = trim(str_replace("help", "", $key));
                foreach ($value as $key2 => $value2) {

                    if ($m == 1) {
                        $this->_string .= $key . $this->ReemplazaBusqueda($key2) . "'" . $value2 . "' || ";
                    } else {
                        $this->_string .= $key . $this->ReemplazaBusqueda($key2) . "'" . $value2 . "' AND ";
                    }
                    $m++;
                }
            }
        }
        $meta = substr(trim($this->_string), 0, -4);
        $meta .= ")";
        return $meta;
    }

    public function WhereFechado($arreglo, $arreglo2) {
        $this->_string = (count($arreglo2) > 0 ? " AND " : "");
        foreach ($arreglo as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key2 => $value2) {
                    $this->_string .= $key . $this->ReemplazaBusqueda($key2) . "'" . $value2 . "' AND ";
                }
            }
        }
        $meta = substr($this->_string, 0, -4);
        ;
        return $meta;
    }
    
    public function WhereFechadomy($arreglo, $arreglo2) {
        
        $this->_string = "";
        $this->_string = (count($arreglo2) > 0 ? " AND ((" : "");
        
        foreach ($arreglo as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key2 => $value2) {
                    $this->_string .= $key . $this->ReemplazaBusqueda($key2) . "'" . $value2 . "' AND ";
                }
            }
        }
        $meta = substr($this->_string, 0, -4);
        $meta .= ")";
        ;
        return $meta;
    }
    
    public function WhereFechadomy2($arreglo, $arreglo2) {
        
        $this->_string = "";
        $this->_string = (count($arreglo2) > 0 ? " OR (" : "");
        
        foreach ($arreglo as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key2 => $value2) {
                    $this->_string .= $key . $this->ReemplazaBusqueda($key2) . "'" . $value2 . "' AND ";
                }
            }
        }
        $meta = substr($this->_string, 0, -4);
        $meta .= "))";
        ;
        return $meta;
    }
    
    public function WhereOr($arreglo, $arreglo2) {
        $this->_string = (count($arreglo2) > 0 ? " AND " : "");
        foreach ($arreglo as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key2 => $value2) {
                    $this->_string .= $key . $this->ReemplazaBusqueda($key2) . "'" . $value2 . "' || ";
                }
            }
        }
        $meta = substr($this->_string, 0, -4);
        ;
        return $meta;
    }

    public function Where($arreglo) {
        $this->_string = "";
        $m = 0;
        foreach ($arreglo as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key2 => $value2) {
                    if ($m == 0) {
                        if (is_string($value2)) {
                            $this->_string .= $key . $this->ReemplazaBusqueda($key2) . "'" . $value2 . "'";
                        } else {
                            $this->_string .= $key . $this->ReemplazaBusqueda($key2) . $value2;
                        }
                    } else {
                        if (is_string($value2)) {
                            $this->_string .= " AND " . $key . $this->ReemplazaBusqueda($key2) . "'" . $value2 . "'";
                        } else {
                            $this->_string .= " AND " . $key . $this->ReemplazaBusqueda($key2) . $value2;
                        }
                    }
                }
            }
            $m++;
        }
        return $this->_string;
    }

    public function WhereReports($arreglo) {
        $this->_string = "";
        $m = 0;
        foreach ($arreglo as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key2 => $value2) {
                    if ($m == 0) {
                        if (is_string($value2)) {
                            $this->_string .= " AND " . $key . $this->ReemplazaBusqueda($key2) . "'" . $value2 . "'";
                        } else {
                            $this->_string .= " AND " . $key . $this->ReemplazaBusqueda($key2) . $value2;
                        }
                    } else {
                        if (is_string($value2)) {
                            $this->_string .= " AND " . $key . $this->ReemplazaBusqueda($key2) . "'" . $value2 . "'";
                        } else {
                            $this->_string .= " AND " . $key . $this->ReemplazaBusqueda($key2) . $value2;
                        }
                    }
                }
            }
            $m++;
        }
        return $this->_string;
    }

    public function WhereReportsComparacion($arreglo) {
        $this->_string = "";
        $m = 0;
        foreach ($arreglo as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key2 => $value2) {
                    if ($m == 0) {
                        $this->_string .= $key . $this->ReemplazaBusqueda($key2) . $value2;
                    } else {
                        $this->_string .= " AND " . $key . $this->ReemplazaBusqueda($key2) . $value2;
                    }
                }
            }
            $m++;
        }
        return $this->_string;
    }

    public function WhereReportsGByDate($arreglo, $action = null) {
        $this->_string = ($action != "" ? $action . "(" : "");
        $m = 0;
        foreach ($arreglo as $key => $value) {
            if ($m == 0) {
                $this->_string .= $value;
            } else {
                $this->_string .= "," . $value;
            }
            $m++;
        }
        $this->_string .= ($action != "" ? ")" : "");
        return $this->_string;
    }

    public function WhereReportsOGComparacion($arreglo) {
        $this->_string = "";
        $m = 0;
        foreach ($arreglo as $key => $value) {
            if ($m == 0) {
                $this->_string .= $value;
            } else {
                $this->_string .= "," . $value;
            }
            $m++;
        }
        return $this->_string;
    }

    public function CondicionalIn($arreglo2, $nameCondicional) {
        $this->_string = "";
        $m = 0;
        if (is_array($arreglo2)) {
            $this->_string = $nameCondicional . " in (";
            foreach ($arreglo2 as $key => $value) {
                if ($m == 0) {
                    if (is_string($value)) {
                        $this->_string .= "'" . $value . "'";
                    } else {
                        $this->_string .= $value;
                    }
                } else {
                    if (is_string($value)) {
                        $this->_string .= "'" . $value . "'";
                    } else {
                        $this->_string .= "," . $value;
                    }
                }
                $m++;
            }
            $this->_string .= ")";
        } else {
            $this->_string = '';
        }
        return $this->_string;
    }

    public function WhereUnique($nameObj, $compareBd, $valueObj) {
        if (is_string($valueObj)) {
            $this->_string = $nameObj . $this->ReemplazaBusqueda($compareBd) . "'" . $valueObj . "'";
        } else {
            $this->_string = $nameObj . $this->ReemplazaBusqueda($compareBd) . $valueObj;
        }
        return $this->_string;
    }

    public function ReemplazaBusqueda($reemplazar) {
        $array = array("igual" => "=", "diferente" => "<>", "menor" => "<", "mayor" => ">", "menorigual" => "<=", "mayorigual" => ">=", "like" => "like");
        foreach ($array as $key => $value) {
            if (strtolower($reemplazar) == $key) {
                $this->_reemplazaBusqueda = $value;
            }
        }
        return $this->_reemplazaBusqueda;
    }

    public function comprobandoEnteros($value) {
        $this->_string = "";
        if (ctype_digit($value)) {
            $this->_string = ($value == 0 ? $value : (int) $value);
        } elseif (is_string($value)) {
            if (is_numeric($value)) {
                $this->_string = (float) $value;
            } else {
                $this->_string = $value;
            }
        } else {
            $this->_string = $value;
        }
        return $this->_string;
    }

}

?>