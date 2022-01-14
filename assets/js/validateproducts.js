
let formulario = document.querySelector('form');
let spans = document.querySelectorAll('.informar');

formulario.addEventListener('submit', e =>{
    e.preventDefault();

    let datos = new FormData(formulario);

    let regexCodigo = /^[a-zA-Z0-9\s]{3}\-[a-zA-Z0-9\s]{3}\-[a-zA-Z0-9\s]{3}$/;
    
    const errores = {};

    if(datos.get('codigo').trim().length === 0){
        errores.codigo =  "El codigo es obligatorio";
    }
    else if(!regexCodigo.test(datos.get('codigo'))){
        errores.codigo =  "Por favor agregue correctamente el codigo";
    }

    if(datos.get('descripcion').trim().length === 0){
        errores.descripcion =  "La descripción del producto es obligatoria";
    }

    if(datos.get('categoria') === 'off'){
        errores.categoria =  "La categoria es obligatoria";
    }

    if(datos.get('precio').trim().length === 0){
        errores.precio =  "El precio es obligatorio";
    }

    if(datos.get('stock').trim().length === 0){
        errores.stock =  "El stock es obligatorio";
    }


    if(Object.keys(errores).length > 0){

        spans.forEach( e=>{
            e.textContent= '';
        });

        if(errores.codigo){
            spans[0].textContent = errores.codigo;
        }
        if(errores.descripcion){
            spans[1].textContent = errores.descripcion;
        }
        if(errores.categoria){
            spans[2].textContent = errores.categoria;
        }
        if(errores.precio){
            spans[3].textContent = errores.precio;
        }
        if(errores.stock){
            spans[4].textContent = errores.stock;
        }

        //Se puede mejorar con un bucle y comparando
    }
    else{

        if(datos.get('estado')){
            fetch('./actualizarproducto.php',{
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
                if(data === './products.php'){
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
                    if(data.codigo){
                        spans[0].textContent = data.codigo;
                    }
                    if(data.descripcion){
                        spans[1].textContent = data.descripcion;
                    }
                    if(data.categoria){
                        spans[2].textContent = data.categoria;
                    }
                    if(data.precio){
                        spans[3].textContent = data.precio;
                    }
                    if(data.stock){
                        spans[4].textContent = data.stock;
                    }
                }
            });
        }
        else{
            fetch('./registrarproducto.php',{
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
                if(data === './products.php'){
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
                    if(data.codigo){
                        spans[0].textContent = data.codigo;
                    }
                    if(data.descripcion){
                        spans[1].textContent = data.descripcion;
                    }
                    if(data.categoria){
                        spans[2].textContent = data.categoria;
                    }
                    if(data.precio){
                        spans[3].textContent = data.precio;
                    }
                    if(data.stock){
                        spans[4].textContent = data.stock;
                    }
                }
            });
        }
    }

});