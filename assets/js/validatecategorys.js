
let formulario = document.querySelector('form');
let span = document.querySelector('.informar');

formulario.addEventListener('submit', e =>{
    e.preventDefault();

    let datos = new FormData(formulario);
    
    let error = false;

    if(datos.get('nombre').trim().length === 0){
        error =  true;
    }

    if(error){
        console.log('error')
        span.textContent='';
        span.textContent = 'El nombre es obligatorio';
       
    }
    else{
        if(datos.get('estado')){
            fetch('./actualizarcategoria.php',{
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
                if(data === './categories.php'){
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
                        span.textContent = data.nombre;
                    }
                }
            });
        }
        else{
            fetch('./registrarcategoria.php',{
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
                if(data === './categories.php'){
                    Swal.fire({
                        title: 'Se ha registrado con exito!',
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
                        span.textContent = data.nombre;
                    }
                }
            });
        }
    }

});