
let formulario = document.querySelector('form');
let span = document.querySelector('#informar');

formulario.addEventListener('submit', e =>{
    e.preventDefault();

    let datos = new FormData(formulario);

    let regexPassword = /^[a-zA-Z0-9\s](?=.*[#$\-_@&%]).*$/;

    const errores = {};

    if(datos.get('pass1') !== datos.get('pass2')){
        errores.diferente = "Los dos campos de password no coinciden";
    }
    else{
        if(datos.get('pass1').trim().length === 0){
            errores.trim =  "La contraseña es obligatoria";
        }
        else if(!regexPassword.test(datos.get('pass1'))){
            errores.password =  "La contraseña debe contener números, letras y al menos un caracter especial (#,$,-,_,&,%,@)";
        }
    
        if(datos.get('pass2').trim().length === 0){
            errores.trim =  "La contraseña es obligatoria";
        }
        else if(!regexPassword.test(datos.get('pass2'))){
            errores.password =  "La contraseña debe contener números, letras y al menos un caracter especial (#,$,-,_,&,%,@)";
        }
    }

    if(Object.keys(errores).length > 0){
        span.textContent = '';

        if(errores.trim){
            span.textContent = errores.password;
        }
        else if(errores.password){
            span.textContent = errores.password;
        }
        else{
            span.textContent = errores.diferente;
        }
    }
    else{

        fetch('./validarcambio.php',{
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

            if(data.length === 0){
                console.log(data);
                if(data.password){
                    span.textContent = data.password;
                }
                if(data.token){
                    span.textContent = data.token;
                }
            }
            else{
                location.replace(data);
            }
        });
    }
});