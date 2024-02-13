"use strict" 

	//Blitly URL Shortener Clicks By Country
	var rebrandly_total_clicks_config = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: final_data_array,
                backgroundColor: [
                    '#ff5e57'
                ],
                
            }],
            labels: label_final_data_array
        },
        options: {
            responsive: true,
            legend: {
                display: false,
            },
            
            animation: {
                animateScale: true,
                animateRotate: true
            },

        }
    };

   
   var rebrandly_ctx = document.getElementById('clicks_top_country').getContext('2d');
   var rebrandly_ctx_chart = new Chart(rebrandly_ctx, rebrandly_total_clicks_config);

