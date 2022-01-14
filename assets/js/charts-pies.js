/**
 * For usage, visit Chart.js docs https://www.chartjs.org/docs/latest/
 */

/* Consumir API */

const categorias = 'solicitudTOPCategorias';

fetch('./charts_pies.php',{
  method: "POST",
  mode: "same-origin",
  credentials: "same-origin",
  headers: {
    "Content-Type": "application/json"
  },
  body: JSON.stringify({categorias: categorias})
})
.then(res => res.json())
.then(data => {
if(data.length == 3){
  let spans = document.querySelectorAll('.category');

  spans[0].textContent=data[1].nombre;
  spans[1].textContent=data[0].nombre;
  spans[2].textContent=data[2].nombre;
  
  const pieConfig = {
    type: 'doughnut',
    data: {
      datasets: [
        {
          data: [data[0].ventas,data[1].ventas,data[2].ventas],
          /**
           * These colors come from Tailwind CSS palette
           * https://tailwindcss.com/docs/customizing-colors/#default-color-palette
           */
          backgroundColor: ['#0694a2', '#1c64f2', '#7e3af2'],
          label: 'Dataset 1',
        },
      ],
      labels: [data[0].nombre,data[1].nombre, data[2].nombre],
    },
    options: {
      responsive: true,
      cutoutPercentage: 80,
      /**
       * Default legends are ugly and impossible to style.
       * See examples in charts.html to add your own legends
       *  */
      legend: {
        display: false,
      },
    },
  }
  
  // change this to the id of your chart element in HMTL
  const pieCtx = document.getElementById('pie')
  window.myPie = new Chart(pieCtx, pieConfig)
}

  
});

