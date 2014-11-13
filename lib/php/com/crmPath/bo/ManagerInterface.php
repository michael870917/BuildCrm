<?php

/**
 * @author michael
 * @copyright 2014
 */

interface ManagerInterface{
    public function BuildCondition($nombreCampo,$condicional,$valorEvaluacion);    
    public function ObjectSimpleBuild($array);
    public function ObjectListBuild($array);    
}


?>