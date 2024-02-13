"use strict";



var month_chart = document.getElementById("month-chart").getContext('2d');

var month_chart_bg_color = month_chart.createLinearGradient(0, 0, 0, 70);
month_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
month_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');

var myChart = new Chart(month_chart, {
  type: 'line',
  data: {
    labels: array_month_label,
    datasets: [{
      label: lang_Earning,
      data: array_month_data,
      backgroundColor: month_chart_bg_color,
      borderWidth: 3,
      borderColor: 'rgba(63,82,227,1)',
      pointBorderWidth: 0,
      pointBorderColor: 'transparent',
      pointRadius: 3,
      pointBackgroundColor: 'transparent',
      pointHoverBackgroundColor: 'rgba(63,82,227,1)',
    }]
  },
  options: {
    layout: {
      padding: {
        bottom: -1,
        left: -1
      }
    },
    legend: {
      display: false
    },
    scales: {
      yAxes: [{
        gridLines: {
          display: false,
          drawBorder: false,
        },
        ticks: {
          beginAtZero: true,
          display: false
        }
      }],
      xAxes: [{
        gridLines: {
          drawBorder: false,
          display: false,
        },
        ticks: {
          display: false
        }
      }]
    },
  }
});

var year_chart = document.getElementById("year-chart").getContext('2d');

var year_chart_bg_color = year_chart.createLinearGradient(0, 0, 0, 80);
year_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
year_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');

var myChart = new Chart(year_chart, {
  type: 'line',
  data: {
    labels:array_year_label,
    datasets: [{
      label:  lang_Earning,
      data: array_year_data,
      borderWidth: 2,
      backgroundColor: year_chart_bg_color,
      borderWidth: 3,
      borderColor: 'rgba(63,82,227,1)',
      pointBorderWidth: 0,
      pointBorderColor: 'transparent',
      pointRadius: 3,
      pointBackgroundColor: 'transparent',
      pointHoverBackgroundColor: 'rgba(63,82,227,1)',
    }]
  },
  options: {
    layout: {
      padding: {
        bottom: -1,
        left: -1
      }
    },
    legend: {
      display: false
    },
    scales: {
      yAxes: [{
        gridLines: {
          display: false,
          drawBorder: false,
        },
        ticks: {
          beginAtZero: true,
          display: false
        }
      }],
      xAxes: [{
        gridLines: {
          drawBorder: false,
          display: false,
        },
        ticks: {
          display: false
        }
      }]
    },
  }
});

var ctx = document.getElementById("comparison-chart").getContext('2d');
var myChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: array_month_names_label,
    datasets: [{
      label: year_label,
      data: array_this_year_earning_data,
      borderWidth: 2,
      backgroundColor: 'rgba(63,82,227,.8)',
      borderWidth: 0,
      borderColor: 'transparent',
      pointBorderWidth: 0,
      pointRadius: 3.5,
      pointBackgroundColor: 'transparent',
      pointHoverBackgroundColor: 'rgba(63,82,227,.8)',
    },
    {
      label: last_year_label,
      data: array_last_year_earning_data,
      borderWidth: 2,
      backgroundColor: 'rgba(254,86,83,.7)',
      borderWidth: 0,
      borderColor: 'transparent',
      pointBorderWidth: 0 ,
      pointRadius: 3.5,
      pointBackgroundColor: 'transparent',
      pointHoverBackgroundColor: 'rgba(254,86,83,.8)',
    }]
  },
  options: {
    legend: {
      display: false
    },
    scales: {
      yAxes: [{
        gridLines: {
          // display: false,
          drawBorder: false,
          color: '#f2f2f2',
        },
        ticks: {
          beginAtZero: true,
          stepSize: js_steps,
          callback: function(value, index, values) {
            return value;
          }
        }
      }],
      xAxes: [{
        gridLines: {
          display: false,
          tickMarkLength: 15,
        }
      }]
    },
  }
});
