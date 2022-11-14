document.addEventListener('DOMContentLoaded', function (event) {
    const predictionFields = document.querySelectorAll('input[data-prediction-field]')
    predictionFields.forEach(function (field) {
        const tabIndex = field.getAttribute('tabindex');
        field.addEventListener('keyup', function (event) {
            const newField = document.querySelector('input[data-prediction-field][tabindex="' + (parseInt(tabIndex) + 1) + '"]')
            if (newField) {
                newField.focus()
            }
        });
    });
});