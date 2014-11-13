<?php
include_once ("DBConfig.php");
include_once ("DaoAbstract.php");
include_once ("CatGenericoDataDAO.php");
include_once ("InterfacesDAO.php");
header('Content-Type: text/html; charset=UTF-8');
class CatalogueMenusDAO extends DaoAbstract implements InterfaceFunctionsCatalogueGeneric{    
    
    private $_contentArrayTabla;
    private $_clave;
    private $_id;
    private $_value;
    private $_insertField;
    private $_campos;
    private $_query;
    private $_resultSet;
        
    public function __construct(){
        $this->_contentArrayTabla = new ManagerCatalogueGenericSQL();
        parent::__construct();
    }
    
    public function __destruct(){
        unset($this->_contentArrayTabla);
        parent::__destruct();
    }
    
    public function SearchCatalogueApp($filtro=null){
        $this->_query = "SELECT id as idCatalogue,descriptionMenu as descriptionCatalogue,iconMenu as iconCatalogue FROM ".$this->_contentArrayTabla->TableMenus()." 
                ".($filtro != "" ? " WHERE ".$filtro : "");
        $this->_resultSet=$this->getBySqlQueryTwo($this->link,$this->_query);
        if(count($this->_resultSet) > 0 || $this->_resultSet != null){
            $Rs=$this->_resultSet;
        }else{
            $Rs=false;
        }
        return $Rs;
    }
    
        
    public function InsertCatalogueMenuApp($data){
        $this->_insertField = $this->_contentArrayTabla->FieldsInsertMenus();
        array_shift($this->_insertField);
        $this->_resultSet = $this->insert($this->link, $this->_contentArrayTabla->TableMenus(), $this->_insertField, substr($this->_contentArrayTabla->TypeFieldGenericInsert(), 1), $data);                
        if($this->_resultSet != null || $this->_resultSet != ""){
            $this->_id = ($this->_resultSet > 0 ? $this->_resultSet : false);
        }else{
            $this->_id = false;
        }                
        return $this->_id;
    }
        
    public function UpdateCatalogueMenuApp($data){
        $this->_campos = $this->_contentArrayTabla->FieldsUpdateMenus();
        $this->_clave = $this->_contentArrayTabla->FieldKeyUpdate();
        $this->_resultSet = $this->update($this->link,$this->_contentArrayTabla->TableMenus(),$this->_campos,$this->_clave,$this->_contentArrayTabla->TypeFieldGenericUpdate(),$data);                
        if($this->_resultSet != null || $this->_resultSet != ""){
            $this->_value=array_pop($data);
            $this->_id = ($this->_value > 0 ? $this->_value : false);
        }else{
            $this->_id = false;
        }
        return $this->_id;
    }
    
    public function DeleteCatalogueMenuApp($data){
        $this->_clave = $this->_contentArrayTabla->FieldKeyDelete();
        $this->_resultSet = $this->delete($this->link,$this->_contentArrayTabla->TableMenus(),$this->_clave,$this->_contentArrayTabla->TypeFieldGenericDelete(),$data);
        if($this->_resultSet != null || $this->_resultSet != ""){
            $this->_value=array_pop($data);
            $this->_id = ($this->_value > 0 ? $this->_value : false);    
        }else{
            $this->_id = false;
        }
        return $this->_id;
    }
    
}

?>