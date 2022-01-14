let formulario = document.querySelector('form');
let span = document.querySelector('#informar');

formulario.addEventListener('submit', e =>{
    
    e.preventDefault();

    let datos = new FormData(formulario);

    let regexEmail = /^[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~](\.?[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~])*@[a-zA-Z0-9](-*\.?[a-zA-Z0-9])*\.[a-zA-Z](-?[a-zA-Z0-9])+$/;

    const errores = {};

    if(datos.get('email').trim().length === 0){
        errores.trim = true;
    }
    else if(!regexEmail.test(datos.get('email'))){
        errores.email = true;
    }

    if(datos.get('password').trim().length === 0){
        errores.trim = true;
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

        fetch('./validarlogin.php',{
            method : 'POST',
            body: datos
        })
        .then(res => {
            if(res.ok)
                return res.json()
            else
                throw new Error(res.status);
        })
        .then(data => {
            if(data === 'error'){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El email o contraseña es inválido!',
                    footer: '<a href="./forgot-password.php" class="text-blue-500">Forgot your password?</a>'
                });
            }
            else{
                location.replace(data);
            }
            
        });
    }
    
});