<?php

include_once './MODEL/BaseModel.php';

class UsuariosModel extends BaseModel {

    // Define atributes
    public static $atributeNames = array(
        "LOGIN_USUARIO",
        "PASSWD_USUARIO",
        "NOMBRE_USUARIO",
        "EMAIL_USUARIO",
        "TIPO_USUARIO",
        "ES_ACTIVO"
    );

    // Define which atributes will be selected in search
    protected static $atributesForSearch = array (  "LOGIN_USUARIO",
                                                    "NOMBRE_USUARIO",
                                                    "EMAIL_USUARIO",
                                                    "TIPO_USUARIO");

    // Set different user types
    public static $userTypes = array("NORMAL", "ADMINISTRADOR", "RESPONSABLE");

    function __construct (){
        
        // Call parent constructor
        parent::__construct();
               
        // Overwrite action codes
        
        $this->actionCodes[parent::ADD_SUCCESS]["code"] = "AC161";
        $this->actionCodes[parent::ADD_FAIL]["code"] = "AC061";
        
        $this->actionCodes[parent::EDIT_SUCCESS]["code"] = "AC162";
        $this->actionCodes[parent::EDIT_FAIL]["code"] = "AC062";
        
        $this->actionCodes[parent::DELETE_SUCCESS]["code"] = "AC163";
        $this->actionCodes[parent::EDIT_FAIL]["code"] = "AC063";
                
        $this->tableName = "USUARIOS";      
          
        $this->primary_key = "LOGIN_USUARIO";

        $this->defaultValues = array( "TIPO_USUARIO" => "NORMAL" , "ES_ACTIVO" => "SI");


        // Subscribe atributes to validations
        $this->checks = array (
            "LOGIN_USUARIO" => array(
                "checkSize" => array('LOGIN_USUARIO', 3, 15, 'AT601'),
                "checkRegex" => array('LOGIN_USUARIO', '/^[a-zA-Z0-9_-]*$/', 'AT602')
            ),
            "PASSWD_USUARIO" => array(
                "checkSize" => array('PASSWD_USUARIO', 32, 32, 'AT611')
            ),
            "NOMBRE_USUARIO" => array(
                "checkSize" => array('NOMBRE_USUARIO', 3, 60, 'AT621'),
                "checkRegex" => array('NOMBRE_USUARIO', '/^[a-zA-Z -]+$/', 'AT622')
            ),
            "EMAIL_USUARIO" => array(
                "checkRegex" => array('EMAIL_USUARIO', '/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix', 'AT631')
            ),
            "TIPO_USUARIO" => array(
                "checkEnum" => array('TIPO_USUARIO', static::$userTypes, 'AT641')
            ),
            "ES_ACTIVO" => array(
                "checkYesOrNo" => array('ES_ACTIVO', 'AT651')
            )
        );
    }

    public function checkCredentials(){

        $error = array("code" => '222', "msg" => 'Credenciales de usuario incorrectas');
        
        if($this->atributes["LOGIN_USUARIO"] == ""){
            return $error;
        }
        
        $userSearch= $this->SEARCH();
        
        if(!count($userSearch)){
            return $error;
        }

        $this->atributes = $userSearch[0];

        return true;
    }


    public function SHOW(){
        $result["normal_info"] = parent::SHOW();

        if($result["normal_info"]["TIPO_USUARIO"] === "RESPONSABLE"){
            include_once './MODEL/ResponsablesModel.php';
            $responsableInfo = new ResponsablesModel();
            $atributesToSet = array("LOGIN_RESPONSABLE" => $this->atributes["LOGIN_USUARIO"]);
            $responsableInfo->setAtributes($atributesToSet);
            $result["resp_info"] = $responsableInfo->SHOW();
        }

        // DEBUG
        // echo "<pre>" . var_export($result, true) . "</pre>";
        return $result;
    }

}

?>