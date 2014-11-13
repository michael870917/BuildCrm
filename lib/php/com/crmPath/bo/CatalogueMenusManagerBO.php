<?php
/**
 * @author michael
 * @copyright 2014
 */
include_once $CFG->dataroot.$CFG->dirMainClassBO.'ManagerInterface.php';
include_once $CFG->dataroot.$CFG->dirMainClassBO.'accionesValidacion.php';
include_once $CFG->dataroot.$CFG->dirMainClassDAO.'CatalogueMenusDAO.php';
include_once $CFG->dataroot.$CFG->dirMainClassBO.'CatalogoGenerico.php';
include_once $CFG->dataroot.$CFG->dirMainClassUTIL;

class CatalogueMenuManager implements ManagerInterface{
    
    private $_objetoAccionesValidacion;
    private $_objetoUsuarioBO;
    private $_objetoUsuarioDAO;
    private $_properties = array();
    private $_value;
    private $_id;
    private $_dataEntry;
    private $_retorno;                
    private $_utilities;
    
    public function __construct(){
        $this->_objetoAccionesValidacion = new AccionesValidacion();
        $this->_objetoCatalogoDAO = new CatalogueMenusDAO();
        $this->_objetoCatalogoBO = new CatalogueGeneric();
        $this->_utilities = new Utilities();
    }
    
    public function __destruct(){
        unset($this->_objetoAccionesValidacion);
        unset($this->_objetoCatalogoDAO);
        unset($this->_objetoCatalogoBO);
        unset($this->_utilities);
    }
    
    public function BuildCondition($nombreCampo,$condicional,$valorEvaluacion){
        $this->_properties[$nombreCampo]=array($condicional => $valorEvaluacion);
    }
            
    public function SearchCatalogue($tipoSalida=null){
        if(($tipoSalida == Constantes::OUTPUTSIMPLE && count($this->_properties) == 0) || ($tipoSalida == "" && count($this->_properties) > 0)){
            $controlObjeto=false;
        }else{
            if(($tipoSalida == "" && count($this->_properties) == 0) || ($tipoSalida == Constantes::OUTPUTSLISTA && count($this->_properties) == 0)){
                $Rs=$this->_objetoCatalogoDAO->SearchCatalogueApp();
            }elseif($tipoSalida != "" && count($this->_properties) > 0){
                $condicional=$this->_objetoAccionesValidacion->Where($this->_properties);
                $Rs=$this->_objetoCatalogoDAO->SearchCatalogueApp($condicional);
            }
            
            if($tipoSalida == Constantes::OUTPUTSLISTA || $tipoSalida == ""){
                $controlObjeto = ($Rs !== false ? $this->ObjectListBuild($Rs) : false);    
            }elseif($tipoSalida == Constantes::OUTPUTSIMPLE && count($this->_properties) > 0){
                $controlObjeto = ($Rs !== false ? $this->ObjectSimpleBuild($Rs) : false);    
            }    
        }
        return $controlObjeto;
    }
        
    public function ObjectSimpleBuild($array){
        $objManager= $this->_objetoCatalogoBO;
        foreach($array AS $key => $value){
            $objManager->setIdCatalogo($value->idCatalogue);
            $objManager->setDescripionCatalogo($value->descriptionCatalogue);
            $objManager->setIconCatalogo($value->iconCatalogue);                        
        }
        return $objManager;
    }
        
    public function  ObjectListBuild($array){
        $read = new LecturaCatalogues();
        foreach($array as $key => $value){            
            $objManager = new CatalogueGeneric();                        
            $objManager->setIdCatalogo($value->idCatalogue);
            $objManager->setDescripionCatalogo($value->descriptionCatalogue);
            $objManager->setIconCatalogo($value->iconCatalogue);
            $read->setNewObject($objManager);
            $read->loadObject();             
        }        
        $coleccion=$read->getListObject();
        return $coleccion;
    }
    
    public function InsertCatalogueMenu($obj){
        if($obj != "" || $obj != NULL){
            if(is_object($obj)){
                $this->_dataEntry=array($obj->getDescripcionCatalogo(),$obj->getIconCatalogo());
                $lastId=$this->_objetoCatalogoDAO->InsertCatalogueMenuApp($this->_dataEntry);
                $this->_retorno=($lastId > 0 ? $lastId : false);
            }else{
                $this->_retorno=false;
            }    
        }else{
            $this->_retorno=false;
        }
        return $this->_retorno;
    }
    
    public function UpdateCatalogueMenu($obj){
        if($obj != "" || $obj != NULL){
            if(is_object($obj)){
                $this->_dataEntry=array($obj->getDescripcionCatalogo(),$obj->getIconCatalogo(),$obj->getIdCatalogo());
                $this->_value = $this->_objetoCatalogoDAO->UpdateCatalogueMenuApp($this->_dataEntry);
            }else{
                $this->_id=false;
            }
        }else{
            $this->_id=false;
        }
        $this->_id = ($this->_value > 0 ? $this->_value : false);
        return $this->_id;
    }
    
    public function DeleteCatalogueMenu($obj){
        if($obj != "" || $obj != NULL){
            if(is_object($obj)){
                $this->_dataEntry=array($obj->getIdCatalogo());
                $this->_value = $this->_objetoCatalogoDAO->DeleteCatalogueMenuApp($this->_dataEntry);
            }else{
                $this->_id = false;
            }
        }else{
            $this->_id = false;
        }
        $this->_id = ($this->_value > 0 ? $this->_value : false);
        return $this->_id;
    }
}

class LecturaCatalogues{
    private $_arrayObjects = array();    
    public function loadObject(){
        array_push($this->_arrayObjects,$this->newArrayForm);
    }
    
    public function getListObject(){
        return $this->_arrayObjects;
    }
    
    public function setNewObject(CatalogueGeneric $objectAdd){
        $this->newArrayForm=$objectAdd;
    }
}
?>