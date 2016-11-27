var select = document.getElementById("addInputType"),
    input = document.getElementById("inputTagname");

select.onchange = function() {

    if( this.value == "textarea") {

        input.value = "textarea";

    } else {

        input.value = "input";

    }

}

var btnActivatePlugin = document.getElementById("activate");

btnActivatePlugin.onclick = function(){

    var enable = btnActivatePlugin.getAttribute('data-value');

    console.log(window.location.href);

    jQuery.ajax({
        url : '/wordpress/wp-content/plugins/form-contact-angular/admin/ajax/activate_form.php',
        success : function(){

            if(enable === "desactivate") {

                btnActivatePlugin.innerText = 'Activer';
                btnActivatePlugin.className = 'button button-large activate';
                btnActivatePlugin.setAttribute('data-value', 'activate');

            }

            if(enable === "activate") {

                btnActivatePlugin.innerText = 'Desactiver';
                btnActivatePlugin.className = 'button button-large desactivate';
                btnActivatePlugin.setAttribute('data-value', 'desactivate');

            }

        }
    });

}

var addFieldForm = document.getElementById("addFieldForm");

addFieldForm.onsubmit = function(evt) {

    evt.preventDefault();

    var type = document.getElementById("addInputType"),
        name = document.getElementById("addInputName"),
        flag = true;

    if(type.value === "selectionnez") {

        alert("Il faut choisir le type !");

        flag = false;

    }

    if(name.value === "") {

        alert("Il faut nomer votre champ de formulaire !");

        flag = false;

    }

    if(flag === true) {

        this.submit();

    }

}

// var modFieldForm = document.getElementById('modFieldForm'),
//     tr = modFieldForm.childNodes[3].childNodes[3].getElementsByTagName('tr');
//
// for(var i=0; i<tr.length; i++) {
//     console.log(tr[i]);
// }
