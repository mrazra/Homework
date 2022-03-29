import $ from "jquery";
import Chart from 'chart.js/auto';

$(document).ready(function() {

  const ctx = $('#chart');

  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ctx.attr('data-date').split(","),
      datasets: [{
        label: ctx.attr('data-currency'),
        data: ctx.attr('data-rate').split(","),
        borderColor: 'rgb(0, 96, 245)',
        tension: 0.1
      }],
    },
  });
} );
