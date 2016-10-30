'use strict';
//Ajout du prestataire dans les favoris
var addFavorite = function (url, self) {
    var options = {
        url: url,
        method: 'POST',
        context: self
    };

    $.ajax(options)
        .done(function () {
            $(this)
                .removeAttr('id')
                .attr('id', 'remove-favorite')
                .toggleClass('favorite__active');
        });
};

//Suppression du prestataire dans les favoris
var removeFavorite = function (url, self) {
    var options = {
        url: url,
        method: 'POST',
        context: self
    };

    $.ajax(options)
        .done(function () {
            $(this)
                .removeAttr('id')
                .attr('id', 'add-favorite')
                .toggleClass('favorite__active');
        });
};

//Affichage de la carte GMAPS
var showGoogleMap = function (address) {
    var map;
    map = new GMaps({
        el: '#map',
        lat: -12.043333,
        lng: -77.028333
    });
    GMaps.geocode({
        address: address,
        callback: function (results, status) {
            if (status == 'OK') {
                var latlng = results[0].geometry.location;
                map.setCenter(latlng.lat(), latlng.lng());
                map.addMarker({
                    lat: latlng.lat(),
                    lng: latlng.lng()
                });
            }
        }
    });
};

//Affichage des suggestions de prestataire
var getSuggestions = function (url) {

    //Recuperation du json avec les suggestions
    $.getJSON(url, function (data) {
        if (data.length < 1)
            return showSuggestions('<h3 class="text-center">Aucun résultat trouvé</h3>');
        var html = '';

        //Construction du HTML
        for (var key in data) {
            html += "<div class='col-lg-3'>" +
                        "<a href='" + data[key].id + "' class='thumbnail'>" +
                            "<img class='pro_avatar--size' src='data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMTcxIiBoZWlnaHQ9IjE4MCIgdmlld0JveD0iMCAwIDE3MSAxODAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzEwMCV4MTgwCkNyZWF0ZWQgd2l0aCBIb2xkZXIuanMgMi42LjAuCkxlYXJuIG1vcmUgYXQgaHR0cDovL2hvbGRlcmpzLmNvbQooYykgMjAxMi0yMDE1IEl2YW4gTWFsb3BpbnNreSAtIGh0dHA6Ly9pbXNreS5jbwotLT48ZGVmcz48c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwhW0NEQVRBWyNob2xkZXJfMTU3M2VhYjVjNTUgdGV4dCB7IGZpbGw6I0FBQUFBQTtmb250LXdlaWdodDpib2xkO2ZvbnQtZmFtaWx5OkFyaWFsLCBIZWx2ZXRpY2EsIE9wZW4gU2Fucywgc2Fucy1zZXJpZiwgbW9ub3NwYWNlO2ZvbnQtc2l6ZToxMHB0IH0gXV0+PC9zdHlsZT48L2RlZnM+PGcgaWQ9ImhvbGRlcl8xNTczZWFiNWM1NSI+PHJlY3Qgd2lkdGg9IjE3MSIgaGVpZ2h0PSIxODAiIGZpbGw9IiNFRUVFRUUiLz48Zz48dGV4dCB4PSI1OS41NTQ2ODc1IiB5PSI5NC41Ij4xNzF4MTgwPC90ZXh0PjwvZz48L2c+PC9zdmc+'>" +
                            "<h4 class='text-center'>" + data[key].name + "</h4>" +
                        "</a>" +
                    "</div>";
        }

        function showSuggestions(html) {
            $('#suggestions').html(html);
        }

        showSuggestions(html);
        }
    );
};

//Enregistrement et affichage d'un nouveau commentaire
var postComment = function (comment) {
    var options = {
        url: comment.url,
        method: 'POST',
        data: $(self).serialize(),
        context: comment.self
    };

    $.ajax(options)
        .done(function (data) {
            var comment =
                "<div class='panel panel-default comment-animate'>" +
                    "<div class='panel-heading'>" +
                        "<img src='/assets/img/uploads/avatars'" + comment.picture +"</img>" +
                        "<span class='lead'>" + comment.user + "</span>" +
                        "<span class='pull-right rating' style='width: calc(20px * " + data.rating + ");'></span>" +
                    "</div>" +
                    "<div class='panel-body'>" +
                        data.comment +
                    "</div>" +
                "</div>";
            $(self)
                .find('.btn')
                .html('')
                .attr('disabled', 'true')
                .addClass('glyphicon glyphicon-ok')
                .end()
                .find('#comment_comment')
                .attr('disabled', 'true');

            $(comment).prependTo('#comments-list');
        });
};
