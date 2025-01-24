document.addEventListener('DOMContentLoaded', function () {
    const anoInput = document.getElementById('ano-lectivo-input');

    if (anoInput) {
        anoInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove todos os caracteres não numéricos
            if (value.length > 4) {
                value = value.slice(0, 4) + '/' + value.slice(4, 8); // Adiciona a barra após os primeiros 4 dígitos
            }
            e.target.value = value.slice(0, 9); // Limita a 9 caracteres
        });
    }
});
