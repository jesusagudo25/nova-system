let formulario = document.querySelector('form');
let span = document.querySelector('#informar');
let titulo = document.querySelector('#titulo');
let entrada = document.querySelector('#entrada');
let submit = document.querySelector('#recover');
let regresar = document.querySelector('#regresar');

formulario.addEventListener('submit', e =>{
    e.preventDefault();

    let datos = new FormData(formulario);

    let regexEmail = /^[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~](\.?[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~])*@[a-zA-Z0-9](-*\.?[a-zA-Z0-9])*\.[a-zA-Z](-?[a-zA-Z0-9])+$/;

    let errores = {};

    if(datos.get('email').trim().length === 0){
        errores.trim = true;
    }
    else if(!regexEmail.test(datos.get('email'))){
        errores.email = true;
    }

    if(Object.keys(errores).length > 0){
        span.textContent = '';
        if(errores.trim){
            span.textContent = 'Rellene los campos!';
        }
        else if(errores.email){
            span.textContent = 'Ingresa un correo valido!';
        }
    }
    else{
        fetch('./enviaremail.php',{
            method : 'POST',
            body: datos
        })
        .then(res => {
            if(!res.ok)
                throw new Error(res.status);
        });
        Swal.fire(
            'El mensaje ha sido enviado!',
            'Revisa tu correo y sigue las instrucciones.',
            'success'
        )

        titulo.textContent = 'The mail has been sent!';
        entrada.classList.remove('block');
        entrada.classList.add('hidden');
        submit.classList.remove('block');
        submit.classList.add('hidden');
        regresar.classList.remove('hidden');
        regresar.classList.add('block');
        
    }

});