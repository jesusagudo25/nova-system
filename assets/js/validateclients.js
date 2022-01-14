let provincias = {
    "Bocas del Toro" : [
        "Bastimentos",
        "Bocas del Toro",
        "Boca del Drago",
        "San Cristóbal"
    ],
    "Coclé" : [
        "Aguadulce",
        "Antón",
        "La Pintada",
        "Penonomé"
    ],
    "Colón" : [
        "Chagres",
        "Colón",
        "Donoso",
        "Santa Isabel"
    ],
    "Chiriquí" : [
        "David",
        "Alanje",
        "Barú",
        "Boquerón"
    ],
    "Darién" : [
        "Chepigana",
        "Santa Fe",
        "Pinogana"
    ],
    "Herrera" : [
        "Chitré",
        "Ocú",
        "Pesé"

    ],
    "Los Santos" : [
        "Las Tablas",
        "Guararé",
        "Los Santos"

    ],
    "Panamá" : [
        "Ciudad de Panamá",
        "San Miguelito",
        "Chepo"

    ],
    "Veraguas" : [
        "Atalaya",
        "Santiago",
        "Mariato"

    ],
    "Panamá Oeste" : [
        "Arraiján",
        "Capira",
        "La Chorrera"

    ]
}

let provincia = document.querySelector('select[name="provincia"]');
let distrito = document.querySelector('select[name="distrito"]');

if(distrito.value != 'off'){

    provincias[provincia.value].forEach(e=>{
        if(e == distrito.value){
            distrito.innerHTML += `<option value="${e}" selected>${e}</option>`;
        }
        else{
            distrito.innerHTML += `<option value="${e}">${e}</option>`;
        }
        
    })

}

provincia.addEventListener('change', e =>{
    distrito.innerHTML = '<option value="off">-- Distritos disponibles --</option>';

    provincias[e.target.value].forEach(e=>{
        distrito.innerHTML += `<option value="${e}">${e}</option>`
    })

});

let formulario = document.querySelector('form');
let spans = document.querySelectorAll('.informar');

formulario.addEventListener('submit', e =>{
    e.preventDefault();

    let datos = new FormData(formulario);

    let regexTelefono = /^\(?([0-9]{3})\)?[-. ]?([0-9]{4})[-. ]?([0-9]{4})$/; //Se puede hacer mas fuerte
    let regexEmail = /^[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~](\.?[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~])*@[a-zA-Z0-9](-*\.?[a-zA-Z0-9])*\.[a-zA-Z](-?[a-zA-Z0-9])+$/;
    
    const errores = {};

    if(datos.get('nombre').trim().length === 0){
        errores.nombre =  "El nombre es obligatorio";
    }

    if(datos.get('telefono').trim().length === 0){
        errores.telefono =  "El telefono del producto es obligatoria";
    }
    else if(!regexTelefono.test(datos.get('telefono'))){
        errores.telefono =  "Por favor agregue correctamente el telefono";
    }

    if(datos.get('email').trim().length === 0){
        errores.email =  "El correo es obligatorio";
    }
    else if(!regexEmail.test(datos.get('email'))){
        errores.email =  "Por favor agregue correctamente el email";
    }

    if(datos.get('provincia') === 'off'){
        errores.provincia =  "La provincia es obligatoria";
    }

    if(datos.get('distrito') === 'off'){
        errores.distrito =  "El distrito es obligatorio";
    }


    if(Object.keys(errores).length > 0){

        spans.forEach( e=>{
            e.textContent= '';
        });

        if(errores.nombre){
            spans[0].textContent = errores.nombre;
        }
        if(errores.telefono){
            spans[1].textContent = errores.telefono;
        }
        if(errores.email){
            spans[2].textContent = errores.email;
        }
        if(errores.provincia){
            spans[3].textContent = errores.provincia;
        }
        if(errores.distrito){
            spans[4].textContent = errores.distrito;
        }

        //Se puede mejorar con un bucle y comparando
    }
    else{

        if(datos.get('estado')){
            fetch('./actualizarcliente.php',{
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
                if(data === './clients.php'){
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
            fetch('./registrarcliente.php',{
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
                if(data === './clients.php'){
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