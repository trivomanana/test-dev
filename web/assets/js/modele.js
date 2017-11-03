$(document).ready(function() {
    var $container = $('div#slife_backbundle_events_eventsmailing');
    var $lienAjout = $('<a href="#" id="ajout_categorie" class="btn">Ajouter une mailing</a>');
    $container.append($lienAjout);
    $lienAjout.click(function(e) {
        ajouterCategorie($container);
        e.preventDefault();
        return false;
    });
    var index = $container.find(':input').length;
    if (index == 0) {
        ajouterCategorie($container);
    } else {
        $container.children('div').each(function() {
            ajouterLienSuppression($(this));
        });
    }
    function ajouterCategorie($container) {
        var $prototype = $($container.attr('data-prototype').replace(/__name__label__/g, 'Catégorie n°' + (index+1)).replace(/__name__/g, index));
        ajouterLienSuppression($prototype);
        $container.append($prototype);
        index++;
    }
    function ajouterLienSuppression($prototype) {
        $lienSuppression = $('<a href="#" class="btn btndanger">Supprimer</a>');
        $prototype.append($lienSuppression);
        $lienSuppression.click(function(e) {
        $prototype.remove();
        e.preventDefault();
        return false;
        });
    }
});