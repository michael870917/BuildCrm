<?php
class DaoAbstract{
    var $link;
    var $usuq;
    
    public function __construct(){
        $dbConfig = new DBConfig();
        $this->link = mysqli_init();
        mysqli_real_connect($this->link,$dbConfig->server,$dbConfig->username,$dbConfig->password,$dbConfig->databasename,$dbConfig->port);
        mysqli_set_charset($this->link,"utf8");
        $this->throwExceptionOnError($this->link);
    }
    
    public function __destruct(){
        mysqli_close($this->link);
    }
    
    public function getBySqlQuery(&$link, $query, $fieldNames, $fieldTitles=null){
        $sqlStatement = $query;
        $stmt=mysqli_prepare($link,$sqlStatement);
        $this->throwExceptionOnError();
        mysqli_stmt_execute($stmt);
        $this->throwExceptionOnError();
        $rows=array();
        $strBinResults = "mysqli_stmt_bind_result(\$stmt";
        if($fieldTitles == null){
            foreach($fieldNames as $field){
                $strBinResults.= ", \$row->".$field;
            }
        }else{
            foreach($fieldTitles as $field){
                $strBinResults.= ", \$row->".$field;
            }
        }
        $strBinResults .= ");";
        eval($strBinResults);
        $this->throwExceptionOnError();
        while(mysqli_stmt_fetch($stmt)){
            $rows[]=$row;
            $row= new stdClass();
            eval($strBinResults);
        }
        mysqli_stmt_free_result($stmt);
        if(count($rows) > 0){
            return $rows;
        }else{
            return false;
        }
    }
    
    public function getBySqlQueryTwo(&$link,$query){
        $sqlStatement = $query;
        $stmt = mysqli_prepare($link, $sqlStatement);
        $this->throwExceptionOnError();
        mysqli_stmt_execute($stmt);
        $this->throwExceptionOnError();
        $metadata=mysqli_stmt_result_metadata($stmt);
        $fieldNames = array();
        while($field = mysqli_fetch_field($metadata)){
            $fieldNames[] = $field->name;
        }
        $this->throwExceptionOnError();
        $rows = array();
        $strBindResults = "mysqli_stmt_bind_result(\$stmt";
        if(count($fieldNames) > 0){
            foreach($fieldNames as $field){
                $strBindResults .= ", \$row->".$field;
            }            
        }        
        $strBindResults .= ");";
        eval($strBindResults);
        $this->throwExceptionOnError();
        
        while(mysqli_stmt_fetch($stmt)){            
            $rows[] = $row;
            $row = new stdClass();
            eval($strBindResults);
        }
        mysqli_stmt_free_result($stmt);
        if(count($rows) > 0){            
            return $rows;
        }else{            
            return false;
        }
        
    }
    public function insert(&$link, $table, $fieldList, $bindTypes, $bindValues){
        $sqlStatement = "INSERT INTO $table (" . implode(",", $fieldList) . ") VALUES (";
        for($i=0; $i < count($fieldList); $i++){
            if($i > 0){
                $sqlStatement .= ",";
            }
            $sqlStatement .= "?";
        }
        $sqlStatement .= ")";
        $stmt= mysqli_prepare($link,$sqlStatement);
        $this->throwExceptionOnError();
        if($bindTypes != null && $bindValues != null){
            $strBinParams = "mysqli_stmt_bind_param(\$stmt, \$bindTypes";
            for($i=0; $i < count($bindValues); $i++){
                $strBinParams .= ", \$bindValues[".$i."]";
            }
            $strBinParams .= ");";
            eval($strBinParams);
            $this->throwExceptionOnError();
        }
        mysqli_stmt_execute($stmt);
        $this->throwExceptionOnError();
        $autoid = mysqli_stmt_insert_id($stmt);
        mysqli_stmt_free_result($stmt);
        return $autoid;
    }
    
    public function update(&$link, $table, $fieldList, $fieldIdList, $bindTypes, $bindValues) {
        $sqlStatement = "UPDATE $table SET ";
        for ($i = 0; $i < count($fieldList); $i++) {
            if ($i > 0) {
                $sqlStatement .= ",";
            }
            $sqlStatement .= $fieldList[$i] . " = ?";
        }
        $sqlStatement .=" WHERE ";
        for ($i = 0; $i < count($fieldIdList); $i++) {
            if ($i > 0) {
                $sqlStatement .= " AND ";
            }
            $sqlStatement .= $fieldIdList[$i] . " = ?";
        }
        $stmt = mysqli_prepare($link, $sqlStatement);
        $this->throwExceptionOnError();
        if ($bindTypes != null && $bindValues != null) {
            $strBinParams = "mysqli_stmt_bind_param(\$stmt, \$bindTypes";
            for ($i = 0; $i < count($bindValues); $i++) {
                $strBinParams .= ", \$bindValues[" . $i . "]";
            }
            $strBinParams .= ");";
            eval($strBinParams);
            $this->throwExceptionOnError();
        }
        mysqli_stmt_execute($stmt);
        $valorModificado=mysqli_stmt_affected_rows($stmt);
        $this->throwExceptionOnError();
        mysqli_stmt_free_result($stmt);
        return $valorModificado;
        
    }
    
    public function delete(&$link, $table ,$fieldIdList, $bindTypes, $bindValues){
        $sqlStatement = "DELETE FROM $table WHERE ";
        for($i=0; $i < count($fieldIdList); $i++){
            if($i > 0){
                $sqlStatement .= " AND ";
            }
            $sqlStatement .= $fieldIdList[$i]. " = ?";
        }
        $stmt = mysqli_prepare($link,$sqlStatement);
        $this->throwExceptionOnError();
        if($bindTypes != null && $bindValues != null){
            $strBindParams = "mysqli_stmt_bind_param(\$stmt, \$bindTypes";
            for($i=0; $i < count($bindValues); $i++){
                $strBindParams .= ", \$bindValues[".$i."]";
            }
            $strBindParams .= ");";
            eval($strBindParams);
            $this->throwExceptionOnError();
        }
        mysqli_stmt_execute($stmt);
        $valorEliminado=mysqli_stmt_affected_rows($stmt);
        $this->throwExceptionOnError();
        mysqli_stmt_free_result($stmt);
        return $valorEliminado;        
    }
    /**
     * Funcion que identifica si existe un error al momento de regresar una ejecucion dentro de la base de datos
     * @param $link -> link utilizado para instancear la conexion a la base de datos
     * 
     */ 
    public function throwExceptionOnError($link = null){
        if($link == null){
            $link = $this->link;
        }
        if(mysqli_error($link)){
            $msg = mysqli_errno($link).": ".mysqli_error($link);
            throw new Exception("Mysql error -".$msg);
        }
    }
    
    
}


?>