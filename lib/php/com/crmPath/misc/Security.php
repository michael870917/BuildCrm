<?php


class Security {
    
    /**
     * 
     * @param String $key
     * @return boolean
     */
    public function checkSessionKFB(){        
        if (isset($_SESSION["userData"]) && $_SESSION["userData"] ){
            return true;
        }
        return false;
    }
    /**
     * 
     * @param String $key
     * @return boolean
     */
    public function checkSessionAFB(){        
        if (isset($_SESSION["userLogedFB"]) && $_SESSION["userLogedFB"] ){
            return true;
        }
        return false;
    }
    /**
     * 
     * @param String $key
     * @return boolean
     */
    public function checkSession(){        
        if (isset($_SESSION["userLoged"]) && $_SESSION["userLoged"] ){
            return true;
        }
        return false;
    }
    /**
     * 
     * @param String $key
     * @return boolean
     */
    public function checkAdminSession(){
        //$thisKey = $this->buildKey($_SESSION["userData"]->role, $_SESSION["userData"]->username); 
        if ((isset($_SESSION["userAdminLoged"]) &&$_SESSION["userAdminLoged"]) && $_SESSION["isAdmin"] ){
            return true;
        }
        return false;
    }
    /**
     * 
     * Toma las primeras 15 letras o numeros  
     * y la convierte a md5
     * 
     */
    public function buildKey($p1,$p2){
        $key = substr(md5($p1 . $p2), 0, 15);
        return $key;
    }
    
     /**
     * Método para encriptar cadenas basados en una llave privada
     * @param String $string Cadena a encriptar
     * @param String $key Cadena con la llave a utilizar para la encripción
     * @return String Cadena con la cadena encriptada
     */
    public function encrypt($string, $key) {
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result.=$char;
        }
        return base64_encode($result);
    }

    /**
     * Método para desencriptar cadenas basados en una llave privada
     * @param type $string Cadena a desencriptar
     * @param type $key Cadena con la llave a utilizar para la desencripción
     * @return type Cadena con la cadena desencriptada
     */
    public function decrypt($string, $key) {
        $result = '';
        $string = base64_decode($string);
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result.=$char;
        }
        return $result;
    }
}

?>
