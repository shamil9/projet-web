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
            console.log(results);
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
                "<a href='" + data[ key ].slug + "' class='thumbnail'>" +
                "<img class='img--100x100' src='/assets/img/uploads/avatars/" + data[ key ].picture + "'>" +
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
var postComment = function ( params ) {
    var options = {
        url: params.url,
        method: 'POST',
        data: $( params.self ).serialize(),
        context: params.self
    };

    $.ajax(options)
        .done(function (data) {
            var comment =
                "<div class='panel panel-default comment-animate'>" +
                    "<div class='panel-heading'>" +
                "<img class='img--30x30 img-circle' src='/assets/img/uploads/avatars/" + params.picture + "'/>" +
                "<span class='lead' style='vertical-align: middle;'>" + params.user + "</span>" +
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

// Inscription à la newsletter
var addNewsletterSubscriber = function (url, user, self) {
    $(self).on('click', function (e) {
        e.preventDefault();

        var options = {
            url: url,
            method: 'POST',
            context: self
        };

        $.ajax(options)
            .done(function () {
                $(this)
                    .removeAttr('id')
                    .attr('id', 'unsubscribe')
                    .attr('disabled', 'true')
                    .toggleClass('btn-success')
                    .html('Succès');
            });
    });
};

// Désinscription de la newsletter
var removeNewsletterSubscriber = function (url, user, self) {
    $(self).on('click', function (e) {
        e.preventDefault();

        var options = {
            url: url,
            method: 'POST',
            context: self
        };

        $.ajax(options)
            .done(function () {
                $(this)
                    .removeAttr('id')
                    .attr('id', 'unsubscribe')
                    .attr('disabled', 'true')
                    .toggleClass('btn-success')
                    .html('Succès');
            });
    });
};
