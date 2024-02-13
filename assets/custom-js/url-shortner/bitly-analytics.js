"use strict"
	//Bitly URL Shortener Clicks Report
    var bitly_clicks_canvas_id = document.getElementById("bitly_click_report").getContext('2d');
    
	var bitly_clicks_data = {
				labels: link_clicks_date_label,
				datasets: [{
					backgroundColor: '#6777ef',
					borderColor: 'transparent',
					data: link_clicks_click,
					hidden: false,
					label: Click
				}, 

				]
			};

	var bitly_clicks_options = {
		legend: {
		  display: false
		},
		animation: {
			animateScale: true,
			animateRotate: true
		},
		maintainAspectRatio: false,
		elements: {
			line: {
				tension: 0.4
			}
		},
		scales: {
			yAxes: [{
				stacked: true,
				gridLines: {
				  display: false,
				  drawBorder: false,
				}

			}],
			xAxes: [{
			  gridLines: {
			    color: '#fbfbfb',
			    lineWidth: 2
			  },
			  type: 'time',
			     time: {
			     		
			         displayFormats: {
			             quarter: 'MMM YYYY'
			         }
			     },

			}]
		},
		plugins: {
			filler: {
				propagate: true
			},

		}
	};

	var bitly_clicks_chart = new Chart(bitly_clicks_canvas_id, {
		type: 'line',
		data: bitly_clicks_data,
		options: bitly_clicks_options
	});


	//Blitly URL Shortener Clicks By Country
	var bitly_clicks_demographics_country_config = {
	  	type: 'doughnut',
	  	data: {
	  		datasets: [{
	  			data: final_data_country ,
	  			backgroundColor: [
	  				'#ff5e57',
	  				'#ff6384',
	  				'#6777ef',
	  				'#ffa426',
	  				'#c32849',
	  				'#fe8886',
	  				'#63ed7a',
	  				'#655dd0',
	  				'#273c75',
	  				'#fd79a8'
	  			],
	  			
	  		}],
	  		labels: label_final_data_country
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

	 
	 var bitly_clicks_demographics_country_ctx = document.getElementById('clicks_top_country').getContext('2d');
	 var bitly_clicks_demographics_country_chart = new Chart(bitly_clicks_demographics_country_ctx, bitly_clicks_demographics_country_config);

   //Bitly URL Shortener Refarar Domains

   
   var click_referring_domains_config = {
    	type: 'doughnut',
    	data: {
    		datasets: [{
    			data: final_data_domain,
    			backgroundColor: [
    				'#ff5e57',
    				'#ff6384',
    				'#6777ef',
    				'#ffa426',
    				'#c32849',
    				'#fe8886',
    				'#63ed7a',
    				'#655dd0',
    				'#273c75',
    				'#fd79a8'
    			],
    			
    		}],
    		labels: label_final_data_domain
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

   
   var click_referring_domains_ctx = document.getElementById('click_referring_domains').getContext('2d');
   var click_referring_domains_my_chart = new Chart(click_referring_domains_ctx, click_referring_domains_config);

