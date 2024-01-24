$(document).ready(function () {
    for (let i = 0; i <= 9; i++) {
        let checkbox = $('<label/>', {
            class: 'number-checkbox'
        }).append($('<input/>', {
            type: 'checkbox', name: 'excludeDigits[]', value: i
        })).append(i);

        $('.number-row').append(checkbox);
    }

    $('form').submit(function (e) {
        e.preventDefault();

        let formData = $(this).serializeArray();
        let result = $('.result');

        $.ajax({
            type: "POST", url: "codeCombinations.php", data: formData
        }).done(function (response) {
            let data = JSON.parse(response);

            result.html('<h2>Combinations for sum ' + $('#sum').val() + ' in ' + $('#numDigits').val() + ' digits:</h2>');

            if (data.length === 0) {
                result.append('<p>No combinations found</p>');
            } else {
                $.each(data, function (index, value) {
                    result.append(value.join(', ') + '<br>');
                });
            }

            result.removeClass('hidden');
        });
    });
});

function toggleSumInputs() {
    $('.otherSums').each(function () {
        if ($('#numDigits').val() >= 4) {
            $(this).removeClass('hidden');
        } else {
            $(this).addClass('hidden');
        }
    });
}