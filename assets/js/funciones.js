$( function() {
    // Single Select
    $( "#nom_cliente" ).autocomplete({
        source: function( request, response ) {
            
            $.ajax({
                url: "ajax.php",
                type: 'post',
                dataType: "json",
                data: {
                    scliente: request.term
                },
                success: function( data ) {
                    response( data );
                }
            });
        },
        select: function (event, ui) {
            $('#nom_cliente').val(ui.item.label); // display the selected text
            $('#tel_cliente').val(ui.item.telefono); // save selected id to input
            $('#cor_cliente').val(ui.item.correo); // save selected id to input
            $('#dir_cliente').val(ui.item.direccion); // save selected id to input
            id_cliente = ui.item.id;

            return false;
        },
        focus: function(event, ui){
            $('#nom_cliente').val(ui.item.label); // display the selected text
            $('#tel_cliente').val(ui.item.telefono); // save selected id to input
            $('#cor_cliente').val(ui.item.correo); // save selected id to input
            $('#dir_cliente').val(ui.item.direccion); // save selected id to input
            return false;
        }
    });

    $("#cod_producto").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "ajax.php",
                type: 'post',
                dataType: "json",
                data: {
                    sprod: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        select: function (event, ui) {
            $("#cod_producto").val(ui.item.value);
            setTimeout(
                function () {
                    e = jQuery.Event("keypress");
                    e.which = 13;
                    registrarProducto(e, ui.item.id_producto,ui.item.value,ui.item.precio, 1, ui.item.stock);
                }
            )
            return false;
        }
    });


});
let id_cliente = 0;
let producto_ag = [];
let contandorF = 20;
let html = '';
/* Funciones propias */

let sw =0;
function registrarProducto(e, id_producto, descripcion, precio,cantidad,stock) {

    if(sw === 0 ){
        sw=1;
        document.querySelector('#acciones').classList.remove('hidden');
        document.querySelector('#acciones').classList.add('block');
    }

    contandorF++;
    if (document.getElementById('cod_producto').value != '') {
        if (e.which == 13) {
            if(producto_ag.indexOf(id_producto) === -1){
                producto_ag.push(id_producto);
                
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Producto Ingresado!',
                    showConfirmButton: false,
                    timer: 1500
                })

                html+=`
                <tr class="text-gray-700 dark:text-gray-400" id="${id_producto}">
                    <td class="px-4 py-3">
                      <div class="flex items-center text-sm">
                          <p class="font-semibold">${descripcion.substring(0,20)}</p>
                      </div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                    ${precio}
                    </td>
                    <td class="px-4 py-3 text-sm">
                    <input type="number" class="dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" value="1" min="1" max="${stock}" onchange="cambiarTotal(this,${contandorF},${stock},${precio})"> / ${stock} (disponibles)
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold" id="${contandorF}">
                      ${precio}
                    </td>
                    <td class="px-4 py-3">
                      <div class="flex items-center space-x-4 text-sm">
                        <button
                          class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                          aria-label="Delete"
                          onclick="deleteProducto(${id_producto})"
                        >
                          <svg
                            class="w-5 h-5"
                            aria-hidden="true"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                          >
                            <path
                              fill-rule="evenodd"
                              d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                              clip-rule="evenodd"
                            ></path>
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                `
                document.querySelector("#cod_producto").value = '';
                document.querySelector("#cod_producto").focus();
    
                document.querySelector("#detalle_venta").innerHTML = html;
                calcular();
            }
            else{
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Ya ha sido ingresado!',
                    showConfirmButton: false,
                    timer: 1500
                })

                document.querySelector("#cod_producto").value = '';
                document.querySelector("#cod_producto").focus();
            }

        }
    }
}

function cambiarTotal(elm,id_cantidad,max,precio){
    let v = parseInt(elm.value);
    if (v < 1) elm.value = 1;
    if (v > max) elm.value = max;
    let total = document.getElementById(id_cantidad);
    total.textContent = (elm.value * precio).toFixed(2);
    calcular()
}

function deleteProducto(id_tr){
    let indice = producto_ag.indexOf(id_tr); // obtenemos el indice
    producto_ag.splice(indice, 1); // 1 es la cantidad de elemento a eliminar
    document.getElementById(id_tr).remove();
    html= document.querySelector("#detalle_venta").innerHTML;

    if(html.trim().length === 0){
        document.querySelector("#detalle_totales").innerHTML = '';
        document.querySelector('#acciones').classList.remove('block')
        document.querySelector('#acciones').classList.add('hidden')
        sw=0;
    }
    else{
        calcular();
    }
}

const ITBMS = 0.07;

function calcular() {
    // obtenemos todas las filas del tbody
    let filas = document.querySelectorAll("#detalle_venta tr");

    let subtotal = 0;
    let itbmstotal =0;

    // recorremos cada una de las filas
    filas.forEach((e) => {

        // obtenemos las columnas de cada fila
        let columnas = e.querySelectorAll("td");

        // obtenemos los valores de la cantidad y importe
        let importe = parseFloat(columnas[3].textContent);

        
        subtotal += importe;
        itbmstotal += importe * ITBMS;
    });

    let detalles = `
    <tr class="text-gray-700 dark:text-gray-400 border-t font-semibold">
        <td colspan="3" class="px-4 py-3">SUBTOT</td>
        <td class="px-4 py-3">${subtotal.toFixed(2)}</td>
    </tr>
    <tr class="text-gray-700 dark:text-gray-400 border-t font-semibold">
        <td colspan="3" class="px-4 py-3">ITBMS 07.00%</td>
        <td class="px-4 py-3">${itbmstotal.toFixed(2)}</td>
    </tr>
    <tr class="text-gray-700 dark:text-gray-400 border-t font-semibold">
        <td colspan="3" class="px-4 py-3">TOTAL</td>
        <td class="px-4 py-3">${(subtotal+itbmstotal).toFixed(2)}</td>
    </tr>
    `;
    // mostramos la suma total
    document.querySelector("#detalle_totales").innerHTML = detalles;
}

let btn_anular = document.querySelector('#anular');

btn_anular.addEventListener('click',e =>{
    location.reload();
});

let btn_generar = document.querySelector('#generar');

btn_generar.addEventListener('click',e =>{

    let error = false;
    
    document.querySelector('#informar').textContent = '';

    let name_client = document.querySelector('#nom_cliente');
    let tel_cliente = document.querySelector('#tel_cliente');
    let cor_cliente = document.querySelector('#cor_cliente');
    let dir_cliente = document.querySelector('#dir_cliente');

    if(name_client.value.trim().length === 0){
        error = true;
    }
    if(tel_cliente.value.trim().length === 0){
        error = true;
    }
    if(cor_cliente.value.trim().length === 0){
        error = true;
    }
    if(dir_cliente.value.trim().length === 0){
        error = true;
    }

    if(error){
        document.querySelector('#informar').textContent =  "El nombre es obligatorio";
    }
    else{
        const datos = empaquetarDatos();

        fetch('./generarventa.php',{
            method: "POST",
            mode: "same-origin",
            credentials: "same-origin",
            headers: {
              "Content-Type": "application/json"
            },
            body: JSON.stringify({datos: datos})
        })
        .then(res => res.json())
        .then(data =>{
            Swal.fire({
                title: 'La venta se ha generado!',
                allowOutsideClick: false,
                icon: 'success',
                text: 'Se ha enviado un correo electronico al cliente con los detalles de la venta. Puedes descargar la factura en formato PDF o XLSX',
                footer: `<a class="flex items-center justify-between swal2-deny swal2-styled" target="_blank" href="./downloadPDF.php?factura=${data}" id="pdf">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
        </svg>
                  <span>Descargar PDF</span>
                </a>
                <a class="flex items-center justify-between swal2-cancel swal2-styled" href="./downloadexcel.php?factura=${data}" id="excel">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
                  <span>Descargar Excel</span>
                </a>`
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        })

    }

}, { once: true });

function empaquetarDatos(){
    let filas = document.querySelectorAll("#detalle_venta tr");
    let id_producto = [];
    let precio = [];
    let cantidad = [];

    filas.forEach((e) => {
         id_producto.push(e.id);

         let columnas = e.querySelectorAll("td");
         precio.push(parseFloat(columnas[1].textContent));
         cantidad.push(parseFloat(columnas[2].querySelector("input").value));
    });

    return {
        id_cliente: id_cliente,
        num_detalle: id_producto.length,
        id_producto: id_producto,
        cantidad: cantidad,
        precio: precio
    }
}

