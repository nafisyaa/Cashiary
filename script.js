// Donut Chart
const donutChart = document.getElementById('donutChart');
if (donutChart) {
  new Chart(donutChart, {
    type: 'doughnut',
    data: {
      labels: donutLabels,
      datasets: [{
        data: donutData,
        backgroundColor: [
          '#FF6384', '#36A2EB', '#FFCE56',
          '#4BC0C0', '#9966FF', '#FF9F40',
          '#8DD1E1', '#FFB6C1', '#D3FFCE'
        ]
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'right'
        }
      }
    }
  });
}

// Line Chart
const lineChart = document.getElementById('lineChart');
if (lineChart) {
  new Chart(lineChart, {
    type: 'line',
    data: {
      labels: lineLabels,
      datasets: [{
        label: 'Pemasukan',
        data: pemasukanLine,
        borderColor: '#36A2EB',
        fill: false,
        tension: 0.3
      }, {
        label: 'Pengeluaran',
        data: pengeluaranLine,
        borderColor: '#FF6384',
        fill: false,
        tension: 0.3
      }]
    }
  });
}
