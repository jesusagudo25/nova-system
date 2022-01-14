/**
 * For usage, visit Chart.js docs https://www.chartjs.org/docs/latest/
 */

/* Consumir API */

const ventas = 'solicitudSales';

fetch('./charts-line.php',{
  method: "POST",
  mode: "same-origin",
  credentials: "same-origin",
  headers: {
    "Content-Type": "application/json"
  },
  body: JSON.stringify({ventas: ventas})
})
.then(res => res.json())
.then(data => {
  if(data.length == 7){
    const lineConfig = {
      type: 'line',
      data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [
          {
            label: 'Sales',
            /**
             * These colors come from Tailwind CSS palette
             * https://tailwindcss.com/docs/customizing-colors/#default-color-palette
             */
            backgroundColor: '#0694a2',
            borderColor: '#0694a2',
            data: [data[0].total, data[1].total, data[2].total, data[3].total, data[4].total, data[5].total, data[6].total],
            fill: false,
          }
        ],
      },
      options: {
        responsive: true,
        /**
         * Default legends are ugly and impossible to style.
         * See examples in charts.html to add your own legends
         *  */
        legend: {
          display: false,
        },
        tooltips: {
          mode: 'index',
          intersect: false,
        },
        hover: {
          mode: 'nearest',
          intersect: true,
        },
        scales: {
          x: {
            display: true,
            scaleLabel: {
              display: true,
              labelString: 'Month',
            },
          },
          y: {
            display: true,
            scaleLabel: {
              display: true,
              labelString: 'Value',
            },
          },
        },
      },
    }
  
    // change this to the id of your chart element in HMTL
    const lineCtx = document.getElementById('line')
    window.myLine = new Chart(lineCtx, lineConfig)
  }

});
