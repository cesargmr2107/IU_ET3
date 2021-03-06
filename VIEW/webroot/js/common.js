

var pickers = [];

function addField(form, name, value){
	var input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    input.value = value;
    form.appendChild(input);
}

function crearform(name, method){
	var form = document.createElement('form');
	document.body.appendChild(form);
    form.name = name;
    form.method = method;
    form.action = 'index.php';   
}

function sendForm(form, controller, action, check){
	if (check){
		addField(form, "controller", controller);
		addField(form, "action", action);
		form.submit();
	}
	else{
		return false;
	}
}

function sendCredentialsForm(form, controller, action, check){

	if(!check){
		return false;
	}
	
	if(action != "edit" || form["PASSWD_USUARIO"].value != ''){
		form["PASSWD_USUARIO"].value = hex_md5(form["PASSWD_USUARIO"].value);
	}
	return sendForm(form, controller, action, check);
}

function formatDate($dateStr){
	if($dateStr == undefined) {
		return undefined;
	}else{
		var d = $dateStr.split("/");
		return `${d[2]}-${d[1]}-${d[0]}`;
	}
}