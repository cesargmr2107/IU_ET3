<?php

include_once './CONTROLLER/BaseController.php';

class ReservasController extends BaseController{	
    
    function searchOwn(){
        $reservasSearch = new ReservasModel();
        $data = $reservasSearch->SEARCH_OWN($_SESSION["LOGIN_USUARIO"]);
        new ReservasPropiasView($data);
    }

    function searchPending(){
        $reservasSearch = new ReservasModel();
        $data = $reservasSearch->SEARCH_PENDING();
        new ReservasPendientesSearchView($data);
    }

    function addForm(){
        // Get resources
        include_once './MODEL/RecursosModel.php';
        $resourcesSearch = new RecursosModel();
        $data["resources"] = $resourcesSearch->getIdAndNameArray("ID_RECURSO", "NOMBRE_RECURSO");

        if(array_key_exists("ID_RECURSO", $_POST)){
            $resourcesSearch->setAtributes(array("ID_RECURSO" => $_POST["ID_RECURSO"] ));
            $data["resource_info"] = $resourcesSearch->SHOW();
        }

        new $this->addView($data);
    }

    function add(){
        
        $reserva = new ReservasModel();

        $atributesToSet = array(
            "LOGIN_USUARIO" => $_SESSION["LOGIN_USUARIO"],
            "ID_RECURSO" => $_POST["ID_RECURSO"],
            "FECHA_SOLICITUD_RESERVA" => date_format(new DateTime(), 'd/m/Y')
        );

        $reserva->setAtributes($atributesToSet);
        $reserva->setInfoSubreservas($_POST["INFO_SUBRESERVAS"]);
                
		$data["result"] = $reserva->ADD();
		$data["controller"] = $this->controller;
        $data["action"] = "searchOwn";
        
        $this->redirectToMsg($data);
    }

    function managePendingForm(){
        $reservasSearch = new ReservasModel();
        $reservasSearch->patchEntity();
        $data["pending"] = $reservasSearch->SHOW_PENDING();
        new ReservasPendientesManageView($data);
    }

    function acceptPending(){
        
        // Accept pending and reject overlappings
        $reserva = new ReservasModel();
        $reserva->setAtributes(
            array(
                "ID_RESERVA" => $_POST["ID_RESERVA"],
                "ID_RECURSO" => $_POST["ID_RECURSO"],
                "ESTADO_RESERVA" => 'ACEPTADA',
                "FECHA_RESPUESTA_RESERVA" => date_format(new DateTime(), 'd/m/Y'),
            )
        );
        $data["accept"] = $reserva->ACCEPT_PENDING();

        // Get other view info
        $reservasSearch = new ReservasModel();
        $reservasSearch->patchEntity();
        $data["pending"] = $reservasSearch->SHOW_PENDING();

        // Load view
        new ReservasPendientesManageView($data);
    }

    function rejectPending(){
       
        // Reject pending
       $reserva = new ReservasModel();
       $reserva->setAtributes(
           array(
               "ID_RESERVA" => $_POST["ID_RESERVA"],
               "ID_RECURSO" => $_POST["ID_RECURSO"],
               "MOTIVO_RECHAZO_RESERVA" => $_POST["MOTIVO_RECHAZO_RESERVA"],
               "ESTADO_RESERVA" => 'RECHAZADA',
               "FECHA_RESPUESTA_RESERVA" => date_format(new DateTime(), 'd/m/Y'),
           )
       );
       $data["reject"] = $reserva->EDIT();

       // Get other view info
       $reservasSearch = new ReservasModel();
       $reservasSearch->patchEntity();
       $data["pending"] = $reservasSearch->SHOW_PENDING();

       // Load view
       new ReservasPendientesManageView($data);
    }

    function confirmUse(){
        $this->confirm("RECURSO_USADO");
    }

    function confirmNoUse(){
        $this->confirm("RECURSO_NO_USADO");
    }

    function confirm($confirmation = null){

        if($confirmation !== null){
            $reserva = new ReservasModel();
            $reserva->setAtributes(array(
                "ID_RESERVA" => $_POST["ID_RESERVA"],
                "ESTADO_RESERVA" => $confirmation
            ));
            $reserva->EDIT();
        }

        $reservas = new ReservasModel();
        $data = $reservas->SEARCH_CONFIRM();

        new ReservasConfirmView($data);
    }

    function cancel(){
        $reserva = new ReservasModel();
        $reserva->setAtributes(array(
            "ID_RESERVA" => $_POST["ID_RESERVA"],
            "ESTADO_RESERVA" => "CANCELADA",
        ));
        $reserva->EDIT();
        parent::show();
    }

}
?>