<?php

    session_start();
    include_once ("../config.php");    
    include_once $CFG->dataroot.$CFG->dirMainClassBO.'CatalogueMenusManagerBO.php';
    include_once $CFG->dataroot.$CFG->dirMainClassBO.'CatalogoGenerico.php';    
    include_once $CFG->dataroot.$CFG->dirMainClassUTIL;
       
    //Instanceamiento de clases
    $utilities = new Utilities();
    $objetoClassManager = new CatalogueMenuManager();
    $objetoClass = new CatalogueGeneric();            
    $utilities->limpiarEntrada(); // Ejecutar al inicio siempre la funcion para sanear la consulta    
        
    $description = $utilities->getParam("description","");   
    $icon = $utilities->getParam("icon","");
    $keyMain = $utilities->getParam("keyMain","");
    $action = $utilities->getParam("action","");
    
    $response = new stdClass();
    
    if($action == "Insert"){
        $objetoClass->setDescripionCatalogo($description);    
        $objetoClass->setIconCatalogo($icon);
        $id=$objetoClassManager->InsertCatalogueMenu($objetoClass);                                
    }elseif($action == "Update"){
        $objetoClass->setDescripionCatalogo($description);    
        $objetoClass->setIconCatalogo($icon);
        $objetoClass->setIdCatalogo($keyMain);
        $id=$objetoClassManager->UpdateCatalogueMenu($objetoClass);        
    }elseif($action == "Delete"){
        $objetoClass->setIdCatalogo($keyMain);
        $id=$objetoClassManager->DeleteCatalogueMenu($objetoClass);
    }
    
    $response->result = ($id != false ? true : false);
    
    echo json_encode($response);
    
?>