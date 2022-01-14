
let formulario = document.querySelectorAll('form');
let spans = document.querySelectorAll('.informar');

formulario[0].addEventListener('submit', e =>{
    e.preventDefault();

    let datos = new FormData(formulario[0]);

    let regexNombre = /^[a-zA-Z\s]+$/;
    let regexEmail = /^[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~](\.?[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~])*@[a-zA-Z0-9](-*\.?[a-zA-Z0-9])*\.[a-zA-Z](-?[a-zA-Z0-9])+$/;

    const errores = {};

    if(datos.get('nombre').trim().length === 0){
        errores.nombre =  "El nombre es obligatorio";
    }
    else if(!regexNombre.test(datos.get('nombre'))){
        errores.nombre =  "Por favor agregue correctamente su nombre";
    }

    if(datos.get('apellido').trim().length === 0){
        errores.apellido =  "El apellido es obligatorio";
    }
    else if(!regexNombre.test(datos.get('apellido'))){
        errores.apellido =  "Por favor agregue correctamente su apellido";
    }
    
    if(datos.get('email').trim().length === 0){
        errores.email =  "El email es obligatorio";
    }
    else if(!regexEmail.test(datos.get('email'))){
        errores.email =  "Agregue un email valido";
    }


    if(Object.keys(errores).length > 0){
        spans.forEach( e=>{
            e.textContent= '';
        });

        if(errores.nombre){
            spans[0].textContent = errores.nombre;
        }
        if(errores.apellido){
            spans[1].textContent = errores.apellido;
        }
        if(errores.email){
            spans[2].textContent = errores.email;
        }

        //Se puede mejorar con un bucle y comparando
    }
    else{

        fetch('./actualizarusuario.php',{
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
            if(data === './users.php'){
                Swal.fire({
                    title: 'Se ha actualizado con exito!',
                    allowOutsideClick: false,
                    icon: 'success',
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.replace(data);
                    }
                });
            }
            else{
                if(data.nombre){
                    spans[0].textContent = data.nombre;
                }
                if(data.apellido){
                    spans[1].textContent = data.apellido;
                }
                if(data.email){
                    spans[2].textContent = data.email;
                }

            }
        });
    }
    
});

formulario[1].addEventListener('submit', e =>{
    e.preventDefault();

    let datos = new FormData(formulario[1]);

    let regexPassword = /^[a-zA-Z0-9\s](?=.*[#$\-_@&%]).*$/;

    const errores = {};
    
    if(datos.get('password').trim().length === 0){
        errores.password =  "La contraseña es obligatoria";
    }
    else if(!regexPassword.test(datos.get('password'))){
        errores.password =  "La contraseña debe contener números, letras y al menos un caracter especial (#,$,-,_,&,%,@)";
    }

    if(Object.keys(errores).length > 0){
        spans[3].textContent = '';
        if(errores.password){
            spans[3].textContent = errores.password;
        }
    }
    else{

        fetch('./actualizarusuario.php',{
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
            if(data === './users.php'){
                Swal.fire({
                    title: 'Se ha actualizado con exito!',
                    allowOutsideClick: false,
                    icon: 'success',
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.replace(data);
                    }
                });
            }
            else{
                if(data.password){
                    spans[3].textContent = data.password;
                }
            }
        });

    }


});