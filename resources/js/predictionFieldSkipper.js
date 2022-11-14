document.addEventListener('DOMContentLoaded', function (event) {
    const predictionFields = document.querySelectorAll('input[data-prediction-field]')
    predictionFields.forEach(function (field) {
        const tabIndex = field.getAttribute('tabindex');
        field.addEventListener('keyup', function (event) {
            let newField = null

            if (event.key === 'ArrowLeft') {
                newField = document.querySelector('input[data-prediction-field][tabindex="' + (parseInt(tabIndex) - 1) + '"]')
            } else if (event.key === 'ArrowRight') {
                newField = document.querySelector('input[data-prediction-field][tabindex="' + (parseInt(tabIndex) + 1) + '"]')
            } else if (['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'].includes(event.key)) {
                newField = document.querySelector('input[data-prediction-field][tabindex="' + (parseInt(tabIndex) + 1) + '"]')
            }

            if (newField) {
                newField.focus()
                newField.select()
            }
        });
    });
});