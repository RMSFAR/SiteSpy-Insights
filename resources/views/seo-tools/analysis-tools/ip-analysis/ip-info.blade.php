<?php 
if($ip_info["status"]!="success") 
{
  $ip_info['city']="";
  $ip_info['country']="";
  $ip_info['postal']="";
  $ip_info['org']="";
  $ip_info['hostname']="";
  $ip_info['region']="";
  $ip_info['latitude']="";
  $ip_info['longitude']="";
}
?>
<link rel="stylesheet" href="{{ asset('assets/custom-css/analysis-tools/myipinfo.css') }}">

<div class="row">

  <div class="col-12 ">
    <div class="card main_card">
        <div class="card-body">
            <div class="row">
              <div class="col-12 col-md-12">
                     <h4 class="text-center">{{ __('IP Information')}}</h4>
                      <div class="row">

                        <!-- domain name -->
                        <div class="col-12 col-md-6 mt-2">
                          <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                              <i class="fas fa-bullseye"></i>
                            </div>
                            <div class="card-wrap">
                              <div class="card-header">
                                <h4>{{ __('IP Address')}}</h4>
                              </div>
                              <div class="card-body"><h6 class="mt-2">{{ $my_ip}}</h6></div>
                            </div>
                          </div>
                        </div>

                        <!-- global rank -->
                        <div class="col-12 col-md-6 mt-2">
                          <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                              <i class="far fa-map"></i>
                            </div>
                            <div class="card-wrap">
                              <div class="card-header">
                                <h4>{{ __('Latitude')}}</h4>
                              </div>
                              <div class="card-body"><h6 class="mt-2">{{ $ip_info["latitude"]}}</h6></div>
                            </div>
                          </div>
                        </div>                        
                        <div class="col-12 col-md-6 mt-2">
                          <div class="card card-statistic-1">
                            <div class="card-icon bg-info">
                              <i class="far fa-map"></i>
                            </div>
                            <div class="card-wrap">
                              <div class="card-header">
                                <h4>{{ __('Longitude')}}</h4>
                              </div>
                              <div class="card-body"><h6 class="mt-2">{{ $ip_info["longitude"]}}</h6></div>
                            </div>
                          </div>
                        </div>

                        <!-- time on site -->
                        <div class="col-12 col-md-6 mt-2">
                          <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                              <i class="far fa-building"></i>
                            </div>
                            <div class="card-wrap">
                              <div class="card-header">
                                <h4>{{ __('Organization')}}</h4>
                              </div>
                              <div class="card-body"><h6 class="mt-2">{{ $ip_info["org"]}}</h6></div>
                            </div>
                          </div>
                        </div>
                        
        

                        <!-- bounce rate  -->
                        <div class="col-12 col-md-6 mt-2">
                          <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                              <i class="fas fa-server"></i>
                            </div>
                            <div class="card-wrap">
                              <div class="card-header">
                                <h4>{{ __('Hostname')}}</h4>
                              </div>
                              <div class="card-body">{{ $ip_info["hostname"]}}</div>
                            </div>
                          </div>
                        </div>

                        <!-- total sites linking -->
                        <div class="col-12 col-md-6 mt-2">
                          <div class="card card-statistic-1">
                            <div class="card-icon bg-direction">
                              <i class="fas fa-flag"></i>
                            </div>
                            <div class="card-wrap">
                              <div class="card-header">
                                <h4>{{ __('Country')}}</h4>
                              </div>
                              <div class="card-body">{{ $ip_info["country"]}} </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-12 col-md-6 mt-2">
                          <div class="card card-statistic-1">
                            <div class="card-icon bg-region">
                              <i class="fas fa-chart-area"></i>
                            </div>
                            <div class="card-wrap">
                              <div class="card-header">
                                <h4>{{ __('Region')}}</h4>
                              </div>
                              <div class="card-body">{{ $ip_info["region"]}} </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-12 col-md-6 mt-2">
                          <div class="card card-statistic-1">
                            <div class="card-icon bg-city">
                             <i class="fas fa-city"></i>
                            </div>
                            <div class="card-wrap">
                              <div class="card-header">
                                <h4>{{ __('City')}}</h4>
                              </div>
                              <div class="card-body">{{ $ip_info["city"]}} </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-12 col-md-6 mt-2">
                          <div class="card card-statistic-1">
                            <div class="card-icon bg-post">
                              <i class="fas fa-address-card"></i>
                            </div>
                            <div class="card-wrap">
                              <div class="card-header">
                                <h4>{{ __('Postal Code')}}</h4>
                              </div>
                              <div class="card-body">{{ $ip_info["postal"]}} </div>
                            </div>
                          </div>
                        </div>

                      </div>
                    
                 
              </div>
              <div class="col-12 col-md-12 mt-5">
                  <h4 class="text-center">{{ __('Google Map')}}</h4>
                  <div id="map"> </div>
              </div>
            </div>
        </div>
    </div>          
  </div>


</div>


<script>



    function success(input_possition) {
    
    
    
      var mapcanvas = document.createElement('div');
    
      mapcanvas.id = 'mapcontainer';
    
      mapcanvas.style.height = '400px';
    
      mapcanvas.style.width = '100%';
    
      
    
      document.getElementById('map').appendChild(mapcanvas);
    
      
    
      var possition_array=input_possition.split(",",2);
    
      var coords = new google.maps.LatLng(possition_array[0],possition_array[1]);
    
      var options = {
    
        zoom: 5,
    
        center: coords,
    
        mapTypeControl: false,
    
        navigationControlOptions: {
    
          style: google.maps.NavigationControlStyle.SMALL
    
        },
    
        mapTypeId: google.maps.MapTypeId.ROADMAP
    
      };
    
      var map = new google.maps.Map(document.getElementById("mapcontainer"), options);
    
    
    
      var marker = new google.maps.Marker({
    
          position: coords,
    
          map: map,
    
          title:"You are here!"
    
      });
    
    }
    
    
    
    var lat_long="<?php echo $ip_info['latitude']; ?>,"+"<?php echo $ip_info['longitude']; ?>";
    
    
    
    success(lat_long);
    
    
    
</script>




