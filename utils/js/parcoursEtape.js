var etapeCourante = 1;
var selectedDates = [];

function nextEtape() {
    // Vérifier si au moins un spectacle est sélectionné
    if ($('.form-check-input:checked').length === 0) {
        toastr.error('Veuillez sélectionner au moins un spectacle');
        return;
    }

    $('#etape' + etapeCourante).removeClass('active');
    etapeCourante++;
    $('#etape' + etapeCourante).addClass('active');
    if (etapeCourante == 2) {
        $('#btn-suivant').hide();
        $('#btn-valider').show();
    }
}

function prevEtape() {
    $('#etape' + etapeCourante).removeClass('active');
    etapeCourante--;
    $('#etape' + etapeCourante).addClass('active');
    if (etapeCourante == 1) {
        $('#btn-valider').hide();
        $('#btn-suivant').show();
    }
}

// Fonction pour changer la bordure de la card en vert quand la case est cochée
$(document).ready(function () {
    $('.form-check-input').click(function () {
        if ($(this).prop("checked") == true) {
            $(this).closest('.card').addClass("border-success");
        } else if ($(this).prop("checked") == false) {
            $(this).closest('.card').removeClass("border-success");
        }
    });
});