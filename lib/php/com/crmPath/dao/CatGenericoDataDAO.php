<?php

/**
 * @author michael
 * @copyright 2014
 */

class ManagerCatalogueGenericSQL{
    
    private $_arregloCampos;
    private $_tabla;
    private $_tipoCampos;
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    // Campo llave actualizacion
    public function FieldKeyUpdate(){
        $this->_arregloCampos=array("id");
        return $this->_arregloCampos;
    }
    
    public function FieldKeyDelete(){
        $this->_arregloCampos=array("id");
        return $this->_arregloCampos;
    }
    
    public function TypeFieldGenericDelete(){
        $this->_tipoCampos="i";
        return $this->_tipoCampos;
    }
    
    public function TypeFieldGenericInsert(){
        $this->_tipoCampos="iss";
        return $this->_tipoCampos;
    }
    
    public function TypeFieldGenericUpdate(){
        $this->_tipoCampos="ssi";
        return $this->_tipoCampos;
    }    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    // Tablas
    public function TableMenus(){
        $this->_tabla="menus";
        return $this->_tabla;
    }
    
   
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    // Insertar datos catalogos
    public function FieldsInsertMenus(){
        $this->_arregloCampos=array("id","descriptionMenu","iconMenu");
        return $this->_arregloCampos;
    }
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    // Actualiza datos catalogos
    public function FieldsUpdateMenus(){
        $this->_arregloCampos=array("descriptionMenu","iconMenu");
        return $this->_arregloCampos;
    }        
    
    
}

?>