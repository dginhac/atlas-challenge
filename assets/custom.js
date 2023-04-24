(function () {

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })

    // Check if the user has accepted the terms and conditions for downloading the dataset
    let AgreeTermsForDataset = document.getElementById('AgreeTermsForDataset');
    let downloadDataset = document.getElementById('downloadDataset');
    AgreeTermsForDataset.addEventListener('click', function () {
        downloadDataset.disabled = !downloadDataset.disabled;
    })
})();