function closeModal(){
    $('#validationModal').modal('hide');
    document.getElementById('errorMsgs').innerHTML = '';
}

function openModal(){
    $('#validationModal').modal('show');
}

function addMsgsToModal(msgCodes){
    var div = document.getElementById('errorMsgs');
    msgCodes.forEach(msgCode => {
        var p = document.createElement('li');
        p.innerHTML = translations[msgCode];
        div.appendChild(p);
    });
}
  
function checkNotEmpty(args){
    return args["value"]!= undefined && args["value"].length != "";
}

function checkLength(args){
    if (args["value"] == ""){
        return false;
    } else {
        return args["value"].length >= args["min"] && args["value"].length <= args["max"];
    }
}

function checkRegex(args){
    return args["regex"].test(args["value"]);
}

function checkNumberRange(args){
    var value = parseInt(args["value"]);
    return !isNaN(value) && value <= args["max"] && value >= args["min"];
}


function checkDateRange(args){
    if (args["startDate"] == undefined || args["startDate"] == undefined ) {
        return false;
    }
    var start = formatDate(args["startDate"]);
    var end = formatDate(args["endDate"]);
    return new Date(start) <= new Date(end);
}

function checkTimeRange(args){
    if (args["startTime"] == undefined || args["endTime"] == undefined ) {
        return false;
    }
    var start = "1960-01-01T" + args["startTime"];
    console.log(start);
    var end = "1960-01-01T" + args["endTime"];
    console.log(end);
    return new Date(start) < new Date(end);
}

function doChecks(form, toCheck){
    // Reset
    document.getElementById('errorMsgs').innerHTML = '';

    // Check
    var errorMsgs = [];
    for (var key in toCheck){
        document.getElementById(key).style.borderColor = "";
        var checks = toCheck[key];
        var value = form[key].value;
        for (check in checks) {
            var args = checks[check]["args"];
            args["value"] = value;
            if (!window[check](args)) {
                errorMsgs.push(checks[check]["code"]);
                document.getElementById(key).style.borderColor = "#ED4A4A";
            }
        }
    }
    if (errorMsgs.length > 0) {
        addMsgsToModal(errorMsgs);
        openModal();
        return false;
    } else {
        return true;
    }
}

function checkLoginForm(){
    var form = document.loginForm;
    var toCheck = {
        LOGIN_USUARIO: {
            checkNotEmpty : {
                args: {},
                code: "i18n-emptyLogin"
            }
        },
        PASSWD_USUARIO: {
            checkNotEmpty : {
                args: {},
                code: "i18n-emptyPassword"
            }
        }
    };
    return doChecks(form, toCheck);
}

function checkRegisterForm(){
    var form = document.registerForm;
    var toCheck = {
        LOGIN_USUARIO: {
            checkLength: {
                args: {
                    min: 3,
                    max: 15
                },
                code: "i18n-loginLength"
            },
            checkRegex: {
                args: {
                    regex: /^[a-zA-Z0-9_]+$/
                },
                code: "i18n-loginRegex"
            }
        },
        NOMBRE_USUARIO: {
            checkLength: {
                args: {
                    min: 0,
                    max: 60
                },
                code: "i18n-usernameLength"
            },
            checkRegex: {
                args: {
                    regex: /^[a-zA-Z ]+$/
                },
                code: "i18n-usernameRegex"
            }
        },
        EMAIL_USUARIO: {
            checkRegex: {
                args: {
                    regex: /^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/
                },
                code: "i18n-mailRegex"
            }
        },
    };
    return doChecks(form, toCheck);
}

function checkCalendarEditForm(){
    var form = document.editForm;
    return checkCalendarForm(form);
}

function checkCalendarAddForm(){
    var form = document.addForm;
    return checkCalendarForm(form);
}

function checkCalendarForm(form){
    var toCheck = {
        NOMBRE_CALENDARIO: {
            checkLength: {
                args: {
                    min: 0,
                    max: 40
                },
                code: "i18n-nameLength"
            },
            checkRegex: {
                args: {
                    regex: /^[a-zA-Z ]+$/
                },
                code: "i18n-nameRegex"
            }
        },
        DESCRIPCION_CALENDARIO: {
            checkLength: {
                args: {
                    min: 0,
                    max: 100
                },
                code: "i18n-descrLength"
            },
            checkRegex: {
                args: {
                    regex: /^[a-zA-Z ]+$/
                },
                code: "i18n-descrRegex"
            }
        },
        FECHA_INICIO_CALENDARIO: {
            checkNotEmpty: {
                args: {},
                code: "i18n-noStartDate"
            }
        },
        FECHA_FIN_CALENDARIO: {
            checkNotEmpty: {
                args: {},
                code: "i18n-noEndDate"
            },
            checkDateRange: {
                args: {
                    startDate: $('#FECHA_INICIO_CALENDARIO').data('date'),
                    endDate: $('#FECHA_FIN_CALENDARIO').data('date')
                },
                code: "i18n-badDateRange"
            }
        },
        HORA_INICIO_CALENDARIO: {
            checkNotEmpty: {
                args: {},
                code: "i18n-noStartTime"
            }
        },
        HORA_FIN_CALENDARIO: {
            checkNotEmpty: {
                args: {},
                code: "i18n-noEndTime"
            },
            checkTimeRange: {
                args: {
                    startTime: $('#HORA_INICIO_CALENDARIO').data('date'),
                    endTime: $('#HORA_FIN_CALENDARIO').data('date')
                },
                code: "i18n-badTimeRange"
            }
        }
    };
    return doChecks(form, toCheck);
}

function checkResourceEditForm(){
    var form = document.editForm;
    return checkResourceForm(form);
}

function checkResourceAddForm(){
    var form = document.addForm;
    return checkResourceForm(form);
}

function checkResourceForm(form){
    var toCheck = {
        NOMBRE_RECURSO: {
            checkLength: {
                args: {
                    min: 0,
                    max: 40
                },
                code: "i18n-nameLength"
            },
            checkRegex: {
                args: {
                    regex: /^[a-zA-Z ]+$/
                },
                code: "i18n-nameRegex"
            }
        },
        DESCRIPCION_RECURSO: {
            checkLength: {
                args: {
                    min: 0,
                    max: 100
                },
                code: "i18n-descrLength"
            },
            checkRegex: {
                args: {
                    regex: /^[a-zA-Z ]+$/
                },
                code: "i18n-descrRegex"
            }
        },
        TARIFA_RECURSO: {
            checkNumberRange: {
                args: {
                    min: 0,
                    max: 999
                },
                code: "i18n-badPrice"
            }
        }
    };
    return doChecks(form, toCheck);
}

function checkResourceStatsForm(){
    var form = document.statsForm;
    var toCheck = {
        FECHA_INICIO_INFORME: {
            checkNotEmpty: {
                args: {},
                code: "i18n-noStartDate"
            }
        },
        FECHA_FIN_INFORME: {
            checkNotEmpty: {
                args: {},
                code: "i18n-noEndDate"
            },
            checkDateRange: {
                args: {
                    startDate: $('#FECHA_INICIO_INFORME').data('date'),
                    endDate: $('#FECHA_FIN_INFORME').data('date')
                },
                code: "i18n-badDateRange"
            }
        }
    };
    return doChecks(form, toCheck);
}

function checkAddIntervalForm(){
    var form = document.addIntervalForm;
    var toCheck = {
        FECHA_INICIO_SUBRESERVA: {
            checkNotEmpty: {
                args: {},
                code: "i18n-noStartDate"
            }
        },
        FECHA_FIN_SUBRESERVA: {
            checkNotEmpty: {
                args: {},
                code: "i18n-noEndDate"
            },
            checkDateRange: {
                args: {
                    startDate: $('#FECHA_INICIO_SUBRESERVA').data('date'),
                    endDate: $('#FECHA_FIN_SUBRESERVA').data('date')
                },
                code: "i18n-badDateRange"
            }
        },
        HORA_INICIO_SUBRESERVA: {
            checkNotEmpty: {
                args: {},
                code: "i18n-noStartTime"
            }
        },
        HORA_FIN_SUBRESERVA: {
            checkNotEmpty: {
                args: {},
                code: "i18n-noEndTime"
            },
            checkTimeRange: {
                args: {
                    startTime: $('#HORA_INICIO_SUBRESERVA').data('date'),
                    endTime: $('#HORA_FIN_SUBRESERVA').data('date')
                },
                code: "i18n-badTimeRange"
            }
        }
    };
    return doChecks(form, toCheck);
}