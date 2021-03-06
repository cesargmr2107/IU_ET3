<?php

include_once './COMMON/utils.php';

class BaseController {

    protected $model;
    protected $controller;
    protected $searchView;
    protected $showView;
    protected $editView;
    protected $addView;
	
	function __construct(){

        // Initialize atributes
        $this->controller = get_class($this);
        $base = substr($this->controller, 0, -10);
        $this->model = $base . "Model";
        $this->searchView = $base . "SearchView";
        $this->showView = $base . "ShowView";
        $this->editView = $base . "EditView";
        $this->addView = $base . "AddView";

        // Include what's necessary
        include_once "./MODEL/$this->model.php";
        foreach (glob("./VIEW/entities/$base/*.php") as $filename)
        {
            include_once $filename;
        }

    }

	function redirectToMsg($data){
		// Encode data to JSON, encrypt into token and redirect
		$jsonString = json_encode($data);
		$token = encrypt($jsonString);
		header("Location: index.php?token=$token");
	}

	function search(){
		$entity = new $this->model();
		$entity->patchEntity();
		$data["atributeNames"] = $this->model::getFormattedAtributeNames();
		$data["atributesForSearch"] = $this->model::$atributesForSearch;
		$data["result"] = $entity->SEARCH();
        new $this->searchView($data);
	}

	function show(){
		$entity = new $this->model();
		$entity->patchEntity();
        new $this->showView($entity->SHOW());
	}

	function delete(){
		$entity = new $this->model();
		$entity->patchEntity();
		$data["result"] = $entity->DELETE();
		$data["controller"] = $this->controller;
		$data["action"] = "search";
		$this->redirectToMsg($data);
	}

	function addForm(){
		new $this->addView();
	}

	function add(){
		$entity = new $this->model();
		$entity->patchEntity();
		$data["result"] = $entity->ADD();
		$data["controller"] = $this->controller;
		$data["action"] = "search";
		$this->redirectToMsg($data);
	}

	function editForm(){
		$entitySearch = new $this->model();
		$entitySearch->patchEntity();
		$data = $entitySearch->SHOW();
		new $this->editView($data);
	}

	function edit(){
		$entity = new $this->model();
		$entity->patchEntity();
		$data["result"] = $entity->EDIT();
		$data["controller"] = $this->controller;
		$data["action"] = "search";
		$this->redirectToMsg($data);
	}
}
?>