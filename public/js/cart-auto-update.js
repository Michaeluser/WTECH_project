document.querySelectorAll('[data-auto-update-cart]').forEach(function (form) {
    const input = form.querySelector('input[name="quantity"]');

    if (!input) {
        return;
    }

    let timeoutId = null;
    const initialValue = input.value;

    function submitForm() {
        if (form.dataset.submitting === 'true') {
            return;
        }

        if (input.value === initialValue || input.value === '') {
            return;
        }

        form.dataset.submitting = 'true';
        form.submit();
    }

    input.addEventListener('input', function () {
        window.clearTimeout(timeoutId);
        timeoutId = window.setTimeout(submitForm, 500);
    });

    input.addEventListener('change', submitForm);
    input.addEventListener('blur', submitForm);
});
