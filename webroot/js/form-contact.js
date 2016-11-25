// FONCTIONS

    // Masquer la scollbar
    function disableScrollBar(){
        var html = document.getElementsByTagName("html")[0];
        html.style.overflow = "hidden";
    }
    // Afficher la scrollbar
    function enableScrollBar(){
        var html = document.getElementsByTagName("html")[0];
        html.style.overflow = "auto";
    }

// ACTION MODAL

    // Declaration des variables
    var modal = document.getElementById('modal'),
        lien = document.getElementById('contactUsLink'),
        cross = document.getElementById('close'),
        form = document.getElementById('angularForm'),
        req = document.querySelectorAll('[required]');

    // Au clic sur le lien contactez-nous
    lien.onclick = function() {
        modal.className = "is-visible";
        disableScrollBar();
    }

    // Au clic sur le modal
    modal.onclick = function(evt) {

        // Ne pas fermer le modal si on interagi avec
        if (evt.target == this) {
            evt.preventDefault();
            modal.className = "no-visible";
            enableScrollBar();
        }
    }

    // Au clic sur le croix
    cross.onclick = function() {
        modal.className = "no-visible";
        enableScrollBar();
    }

    // Validation du formulaire
    form.onsubmit = function() {

        for (var i=0; i<req.length; i++) {
            if (req[i] == "") {
                return false;
            } else {
                return true;
            }
        }
    }


// ANGULAR APP

var App = angular.module("mainApp", []);

App.controller('formController', ['$scope', function($scope) {

    $scope.disabled = function(){
        if($scope.name = '') {
            this.disabled;
        }

    };

}]);