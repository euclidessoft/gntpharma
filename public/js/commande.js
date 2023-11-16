$(document).ready(function () {
    var table = $('#liste').DataTable({

        responsive: true,
        pageLength: 25,
        buttons: [{
            extend: 'excelHtml5',
            text: '<i class="fa fa-file-excel fa-lg"></i> Exporter vers Excel',
            attr: {
                class: 'btn btn-success'
            },
        }],
        language: {
            processing: "Traitement en cours...",
            search: "Rechercher&nbsp;:",
            lengthMenu: "Afficher _MENU_ &eacute;l&eacute;ments",
            info: "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            infoEmpty: "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
            infoFiltered: "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            infoPostFix: "",
            loadingRecords: "Chargement en cours...",
            zeroRecords: "Aucun &eacute;l&eacute;ment &agrave; afficher",
            emptyTable: "Aucune donnée disponible dans le tableau",
            paginate: {
                first: "Premier",
                previous: "Pr&eacute;c&eacute;dent",
                next: "Suivant",
                last: "Dernier"
            },
            aria: {
                sortAscending: ": activer pour trier la colonne par ordre croissant",
                sortDescending: ": activer pour trier la colonne par ordre décroissant"
            }
        }

    });
    table.buttons().container().appendTo('#exportexcel');
    selectAllstart();
});

function selectAllstart() { //selectionner tous les produits
    if ($("#tous").is(':checked')) {
        $("input[type=checkbox]").attr('checked', true);
        $("input[type=checkbox]").each(
            function () {
                if ($(this).is(':checked')) {

                } else {
                    $(this).trigger('click')
                }
                trselect($(this).attr("id"));
            });
    }
}

function addprod(id, mincom) {
    var reg = new RegExp("^[0-9]+$");
    if (reg.test($('#text' + id).val()) && mincom <= $('#text' + id).val()) {
        $('#btn' + id).html("<i class='fa-solid fa-spinner fa-spin-pulse fa-lg'></i>");
        $.ajax({
            type: "POST",
            url: "{{ path('commande_add') }}",
            data: "prod=" + id + "&quantite=" + $('#text' + id).val(),
            success: function (data) {
                if (data['id'] == 'ok') {
                    $('#tdprod' + id).html($('#text' + id).val());
                    $('#gest_panier').html(data['panier']);
                    $('#btn' + id).html("<i class='fa fa-cart-shopping'></i>");
                    $('#btn' + id).addClass('disabled');
                } else {
                    alert("{{ 'verifier la quantite saisie'|trans }}")
                }
            },
            error: function () {
                alert("{{ 'La requête n a pas abouti'|trans }}");
            }
        });
    } else alert("{{ 'verifier la quantite saisie'|trans }}");

}

function nofocu(id) {
    $('#tampon').val('');
}

function scrollToBottom()
{
    var height = document.body.scrollHeight;
    window.scroll(0 , height);
}

