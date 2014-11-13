<?php
/**
 * @author michael
 * @copyright 2014
 */
class CatalogueGeneric{
    const IDGENERICO = "id";
    private $_idCatalogo;
    private $_descripcionCatalogo;    
    private $_iconCatalogo;
    
    public function setIdCatalogo($clave=null){
        $this->_idCatalogo = $clave;
    }
    
    public function getIdCatalogo(){
        return $this->_idCatalogo;
    }
    
    
    public function setDescripionCatalogo($clave=null){
        $this->_descripcionCatalogo = $clave;
    }
    
    public function getDescripcionCatalogo(){
        return $this->_descripcionCatalogo;
    }
    
    public function setIconCatalogo($clave=null){
        $this->_iconCatalogo = $clave;
    }
    
    public function getIconCatalogo(){
        return $this->_iconCatalogo;
    }
            
}
?>