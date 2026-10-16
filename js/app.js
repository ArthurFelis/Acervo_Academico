// Validação básica de formulários no navegador (o servidor valida de novo).
document.querySelectorAll('form[data-validate]').forEach(function (form) {
    form.addEventListener('submit', function (ev) {
        var erros = [];
        form.querySelectorAll('input, textarea').forEach(function (campo) {
            campo.classList.remove('invalid');
            if (campo.type === 'hidden') return;
            var valor = campo.value.trim();

            if (campo.required && !valor) {
                erros.push(campo);
            } else if (campo.type === 'email' && valor && !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(valor)) {
                erros.push(campo);
            } else if (campo.name === 'senha' && form.querySelector('[name=senha2]') && valor.length < 6) {
                erros.push(campo);
            } else if (campo.type === 'file' && campo.files.length) {
                var arq = campo.files[0];
                if (!/\.pdf$/i.test(arq.name) || arq.size > 10 * 1024 * 1024) erros.push(campo);
            }
        });

        var s1 = form.querySelector('[name=senha]');
        var s2 = form.querySelector('[name=senha2]');
        if (s1 && s2 && s1.value !== s2.value) erros.push(s2);

        if (erros.length) {
            ev.preventDefault();
            erros.forEach(function (c) { c.classList.add('invalid'); });
            erros[0].focus();
            alert('Verifique os campos destacados em vermelho.');
        }
    });
});
