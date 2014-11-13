<?php
class Constantes{
    /**
     * @abstract Metodos de comparacion -> Consultas sql
     * @example  campo IGUAL_DB valor
     * 
     */ 
    const IGUAL_DB = "igual";
    const DIFERENTE_DB = "diferente";
    const MENOR_DB = "menor";
    const MAYOR_DB = "mayor";
    const MENORIGUAL_DB = "menorigual";
    const MAYORIGUAL_DB = "mayorigual";
    const ASC = "ASC";
    const DESC = "DESC";
    
    /**
     * @abstract Tipos de salida a ocupar en el procesamiento de datos sobre creacion y manejo de objetos
     * @param OUTPUTSIMPLE -> salida del query como un objeto unico
     * @param OUTPUTSLISTA -> salida del query como un arreglo de objetos
     * 
     */ 
    const OUTPUTSIMPLE = "simple";
    const OUTPUTSLISTA = "lista";
    
    const ALTERHELP = "help";
    
    const OUTPUTXML = "xml";
    const OUTPUTJSON = "json";
    const OUTPUTWEB = "web";
   
}



class Utilities{
    
    private $_retornoInformacion;
    private $_convierteArreglo;
    private $_convierteArregloUtf8;
    private $_retornoCodificado;
    private $_llenandoArrayObjeto=array();
    private $_maestro = array();
    private $_verificandoElemento = array();
    private $_filling = array();
    private $_semantica;
    private $_myParamInit;
    private $_myParamFinish;
    private $_divide;
    private $_iniciacionFecha;
    private $_recursion;
    
    public function ConvertirObjetoStdClass($matriz){        
        return json_decode(json_encode($matriz), true);
    }
    
    public function reflectionAccionTT($obj){        
        $this->_filling22 = array();
        unset($this->_filling22);
        $reflectionClass = new ReflectionClass(get_class($obj));
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            if(is_array($property->getValue($obj))){
                $arreglo = $this->verificando($property->getValue($obj));
                $this->_filling22[str_replace('_','',$property->getName())] = $arreglo;
            }else{
                $this->_filling22[str_replace('_','',$property->getName())] = $property->getValue($obj);    
            }
            $property->setAccessible(false);
        }
        
        return $this->_filling22;
    }
    
    
    public function verificando($array){
        unset($this->_verificandoElemento);
        if(is_array($array)){
            foreach($array as $key2 => $value2){

                $this->_verificandoElemento[$key2] = ($value2 instanceof stdClass ? $this->arrayCastRecursive((array)$value2) : $this->reflectionAccionTT($value2));
            }
        }        
        return $this->_verificandoElemento;
    }
    
    public function verificandoNotEmpty($array){        
        if(is_array($array)){
            foreach($array as $key2 => $value2){
                $this->_verificandoElemento[$key2] = ($value2 instanceof stdClass ? $this->arrayCastRecursive((array)$value2) : $this->reflectionAccionNotEmpty($value2));                
            }
        }        
        return $this->_verificandoElemento;
    }
    
    
    public function arrayCastRecursive($array){        
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $array[$key] = $this->arrayCastRecursive($value);                    
                }                
                if ($value instanceof stdClass) {                 
                    $array[$key] = $this->arrayCastRecursive((array)$value);
                }
            }
        }
        if ($array instanceof stdClass) {            
            return $this->arrayCastRecursive((array)$array);
        }        
        return $array;
    }         
    /***************** Devolucion informacion de la clase ****************/
    
    public function reflectionAccion($obj){
        $reflectionClass = new ReflectionClass(get_class($obj));
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            if(is_array($property->getValue($obj))){
                $arreglo = $this->verificando($property->getValue($obj));
                $this->_filling[str_replace('_','',$property->getName())] = $arreglo;
            }else{
                $this->_filling[str_replace('_','',$property->getName())] = $property->getValue($obj);    
            }
            $property->setAccessible(false);
        }
        return $this->_filling;
    }
    
    
    public function reflectionAccionNotEmpty($obj){
        $this->_semantica = "";
        $reflectionClass = new ReflectionClass(get_class($obj));
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            if(is_array($property->getValue($obj))){
                $arreglo = $this->verificandoNotEmpty($property->getValue($obj));
                $this->_filling[str_replace('_','',$property->getName())] = $arreglo;
            }else{
                if($property->getValue($obj) != ""){ 
                    $this->_filling[str_replace('_','',$property->getName())] = $property->getValue($obj);
                }    
            }
            $property->setAccessible(false);
        }
        return $this->_filling;
    }
    
    /***************** Cierre Devolucion informacion de la clase ****************/

    
    /***************** Apartado to xml ****************/
    
    public function MuestraSalidaXml($obj,$tagName=null){
        if(is_object($obj)){
            $this->_retornoInformacion=$this->ConvierteObjetoArrayToxml($obj,trim($tagName));
        }elseif(is_array($obj)){
            foreach($obj as $dataSet){
                    $this->_llenandoArrayObjeto=$this->reflectionAccion($dataSet);
                    array_push($this->_maestro,$this->_llenandoArrayObjeto);    
            }
            $this->_retornoInformacion = $this->SalidaXmlFromArray($this->_maestro,$tagName);
        }
        return $this->_retornoInformacion;
    }
    
    function ConvierteObjetoArrayToxml($obj,$tagName) {
        $array=$this->reflectionAccion($obj);
        $xml = new SimpleXMLElement("<".$tagName."/>");
        foreach($array as $key => $value){
            if(is_array($value)){
                $send = $xml->addChild($key);
                foreach($value as $key2 => $value2){
                    if(is_array($value2)){
                        $send2 =  $send->addChild($this->CambiaTextoNodoHijo($key));
                        foreach($value2 as $key3 => $value3){
                            $send2->addChild($key3,$value3);
                        }
                    }
                }
            }else{                
                $xml->addChild($key,htmlspecialchars($value));                
            }            
        }        
        return $xml->asXML();
    }
    
         
    public function SalidaXmlFromArray($array,$tagName=null){
        $xml = new SimpleXMLElement("<".$tagName."/>");
        $nodoHijo= $this->CambiaTextoNodo($tagName);
        foreach($array as $key => $value){
            if(is_array($value)){                
                $send = $xml->addChild($nodoHijo);                
                foreach($value as $key2 => $value2){
                    if(is_array($value2)){
                        $send2 = $send->addChild($key2);                                                
                        foreach($value2 as $key3 => $value3){
                            if(is_array($value3)){
                                $send3 =  $send2->addChild($this->CambiaTextoNodoHijo($key2));
                                foreach($value3 as $key4 => $value4){
                                    $send3->addChild($key4,$value4);
                                }
                            }
                        }
                    }else{                                                
                        $send->addChild($key2,htmlspecialchars($value2));
                    }                  
                }
            }
        }        
        return $xml->asXML();
    }
    
    public function CambiaTextoNodo($text){
        $this->_retornoInformacion = trim(strtolower($text),"s");
        return $this->_retornoInformacion; 
    }
    
    public function CambiaTextoNodoHijo($text){
        $this->_divide = explode("list",$text);        
        $this->_retornoInformacion = trim($this->_divide[1]);
        return $this->_retornoInformacion; 
    }
    
    /***************** Cierre Apartado to xml ****************/
        
    
    /***************** Apartado to json ****************/
    public function MuestraSalidaJsonNotEmpty($obj){
        if(is_object($obj)){
           $this->_retornoInformacion=$this->SalidaObjetoToArray($obj);
        }elseif(is_array($obj)){            
            foreach($obj as $dataSet){
                $this->_llenandoArrayObjeto[]=$this->reflectionAccionNotEmpty($dataSet);
            }
            $this->_retornoInformacion = $this->SalidaArrayToObjectos($this->_llenandoArrayObjeto);
        }
        return $this->_retornoInformacion;
    }
        
    public function MuestraSalidaJson($obj){
        if(is_object($obj)){
           $this->_retornoInformacion=$this->SalidaObjetoToArray($obj);
        }elseif(is_array($obj)){            
            foreach($obj as $dataSet){            
                $this->_llenandoArrayObjeto[]=$this->reflectionAccion($dataSet);
            }
            $this->_retornoInformacion = $this->SalidaArrayToObjectos($this->_llenandoArrayObjeto);
        }
        return $this->_retornoInformacion;
    }
    
    public function SalidaObjetoToArray($obj){
        $this->_retornoCodificado = json_encode($this->reflectionAccion($obj));
        return $this->_retornoCodificado;
    }
    
    public function SalidaArrayToObjectos($obj){                                
        $this->_retornoCodificado = json_encode($obj);
        return $this->_retornoCodificado;                
    }        
    
    /***************** Cierre Apartado to json ****************/
    
    function utf8enc($array) {
        if (!is_array($array)) return;
        $helper = array();
        foreach ($array as $key => $value) $helper[utf8_decode($key)] = is_array($value) ? utf8enc($value) : utf8_decode($value);
        return $helper;
    }
    
    /**
     * @function getParam -> devuelve el valor del elento enviado ya sea por get, post, request
     * @abstract Almacenamiento de las variables enviadas desde el navegador
     * @param $paramName -> nombre del valor recibido ya sea por get,post,request
     * @param $default -> asignar valor por default si lo deseamos asi
     */ 
    public function getParam($paramName, $default = "") {
        return (isset($_REQUEST[$paramName]) ? $_REQUEST[$paramName] : $default);
    }
    /**
     * @abstract Recorre todas las posibles entradas enviadas por el navegador
     * para hacer limpieza haciendo uso del metodo limpiarParametros()     
     * 
     */ 
    public function limpiarEntrada(){
        foreach($_POST as $key => $value){
            $_POST[$key] =addslashes($this->limpiarParametros($value));
        }
        
        foreach($_GET as $key => $value){
            $_GET[$key] =addslashes($this->limpiarParametros($value));
        }                
        
        foreach($_REQUEST as $key => $value){
            $_REQUEST[$key] =addslashes($this->limpiarParametros($value));
        }
    }
    
    /**
     * @function Encriptacion -> Encriptacion de la variable enviada
     * @abstract Metodo de encrptacion para seguridad de la variable
     * @param $valor -> variable a encriptar
     * 
     */ 
    public function Encriptacion($valor){
        return md5($valor);
    }
    
    /**
     * @abstract Limpia todas las entradas de posibles inyecciones sql 
     * asi como tambien de posible incursion de elementos script o html
     *
     */ 
    public function limpiarParametros($valor){
        $valor = str_ireplace("SELECT","",$valor);
        $valor = str_ireplace("FROM","",$valor);
    	$valor = str_ireplace("COPY","",$valor);
    	$valor = str_ireplace("DELETE","",$valor);
    	$valor = str_ireplace("DROP","",$valor);
    	$valor = str_ireplace("DUMP","",$valor);
    	$valor = str_ireplace(" OR ","",$valor);
        $valor = str_ireplace(" AND ","",$valor);
    	$valor = str_ireplace("%","",$valor);
    	$valor = str_ireplace("LIKE","",$valor);
    	$valor = str_ireplace("--","",$valor);
    	$valor = str_ireplace("^","",$valor);
    	$valor = str_ireplace("[","",$valor);
    	$valor = str_ireplace("]","",$valor);
    	$valor = str_ireplace("\\","",$valor);
    	$valor = str_ireplace("!","",$valor);
    	$valor = str_ireplace("¡","",$valor);
    	$valor = str_ireplace("?","",$valor);
    	$valor = str_ireplace("=","",$valor);
    	$valor = str_ireplace("&","",$valor);
        $valor = str_ireplace("*","",$valor);
        $valor = str_ireplace("!=","",$valor);
        $valor = str_ireplace("<","",$valor);
        $valor = str_ireplace(">","",$valor);
        $valor = str_ireplace("<>","",$valor);
        $valor = str_ireplace("<script>","",$valor);
        $valor = str_ireplace("</script>","",$valor);
        $valor = str_ireplace("<html>","",$valor);
        $valor = str_ireplace("</html>","",$valor);
        return trim($valor);    
    }
    
    public function DecodificaUtf8($valor){
        return utf8_decode($valor);
    }
    
    
    public function trataFecha($dateTrata){
        $this->_iniciacionFecha = "";
        $this->_iniciacionFecha = date( 'o-W', strtotime( $dateTrata) );
        if($this->_iniciacionFecha != ""){        
            $this->_divide = explode("-",$this->_iniciacionFecha);
            explode("-", $ddate);
        }
        $secciones = array((int)$this->_divide[0],(int)$this->_divide[1]);
        return $secciones;
    }
    
    public function mostrandolaFecha($fechaIni,$fechaFin){
        $this->_myParamInit = $this->trataFecha($fechaIni);
        $this->_myParamFinish = $this->trataFecha($fechaFin);
        $rrr = $this->_myParamFinish[0] - $this->_myParamInit[0];
        if($rrr <= 1){
            if($this->_myParamInit[1] == $this->_myParamFinish[1]){
                array_push($this->_llenandoArrayObjeto,$this->_myParamInit[1]);
            }else{
                
                if($this->_myParamInit[0] == $this->_myParamFinish[0]){
                    for($i = $this->_myParamFinish[1]; $i >= $this->_myParamInit[1]; $i--){
                        array_push($this->_llenandoArrayObjeto,$i);
                    }
                }elseif($this->_myParamInit[0] < $this->_myParamFinish[0]){
                    if($this->_myParamInit[1] >= 52 && $this->_myParamInit[1] > $this->_myParamFinish[1]){            
                        $this->_recursion = $this->_myParamInit[1] + $this->_myParamFinish[1];
                        for($i = $this->_recursion; $i >= $this->_myParamInit[1]; $i--){
                            $restando = $i - $this->_myParamInit[1];
                            if($restando > 0){
                                array_push($this->_llenandoArrayObjeto,$restando);    
                            }
                        }
                        array_push($this->_llenandoArrayObjeto,$this->_myParamInit[1]);
                                    
                    }else{
                        $total_sem_peque = $this->NumeroSemanasTieneUnAno($this->_myParamInit[0]);
                        $this->_recursion = $numerando - $this->_myParamFinish[1];
                        for($i = $this->_myParamFinish[1]; $i >= 1; $i--){
                            array_push($this->_llenandoArrayObjeto,$i);
                        }
                        for($i = $total_sem_peque; $i >= $this->_myParamInit[1]; $i--){
                            array_push($this->_llenandoArrayObjeto,$i);
                        }
                    }    
                }
            }
            $this->_retornoInformacion = $this->_llenandoArrayObjeto;    
        }else{
            $this->_retornoInformacion = false;
        }        
        return $this->_retornoInformacion;
    }

    public function NumeroSemanasTieneUnAno($year){
        $date = new DateTime;
        $date->setISODate($year, 53);
        return ($date->format("W")=="53" ? 53 : 52);
    }
    
    public function ErrorJson(){
        $response = new stdClass();
        $response->result = false;
        $response->error = true;
        $response->id = false;
        $response->totalRegistros = 0;
        return json_encode($response);
    }
    
    public function ValidaResult($id,$tot=1){
        $response = new stdClass();
        $response->result = ($id != false ? true : false);
        $response->id = ($id != false ? $id : false);
        $response->total_inserciones = ($id != false ? $tot : 0);
        $response->error = ($id != false ? false : true);
        return json_encode($response);
    }
            
    public function StringReplace($string,$reemplazo,$valor){
        return trim(str_replace($string,$reemplazo,$valor));
    }   
}  

?>