{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Package details'))
@section('content')

<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-eye"></i> <?php echo __('Package details'); ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo __("Subscription"); ?></div>
      <div class="breadcrumb-item active"><a href="{{route('package_manager')}}"><?php echo __("Package Manager"); ?></a></div>
      <div class="breadcrumb-item"><?php echo __('Package details'); ?></div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-4 col-sm-4 col-12">
      <div class="card card-statistic-1">
        <div class="card-icon bg-primary">
          <i class="fas fa-shopping-bag"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4><?php echo __("Package Name")?></h4>
          </div>
          <div class="card-body">
            <?php echo $value[0]->package_name;?>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-sm-4 col-12">
      <div class="card card-statistic-1">
        <div class="card-icon bg-warning">
          <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4><?php echo __("Price")?> - <?php echo isset($payment_config[0]->currency) ? $payment_config[0]->currency : 'USD'; ?></h4>
          </div>
          <div class="card-body">
            <?php 
            if($value[0]->is_default=="1") 
            { 
               if( $value[0]->price=="Trial") echo __("Trial");
               if( $value[0]->price=="0") echo __("Free");            
            }
            else echo $value[0]->price;
            ?>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-sm-4 col-12">
      <div class="card card-statistic-1">
        <div class="card-icon bg-danger">
          <i class="fas fa-stopwatch"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4><?php echo __("Validity");?></h4>
          </div>
          <div class="card-body">
            <?php echo $validity_amount; ?> <?php echo __($validity_type[$validity_type_info]); ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">      
      <div class="card">
        <div class="card-header"><h4 class="card-title"><i class="fas fa-info-circle"></i> <?php echo __("Module Access"); ?></h4></div>
        <div class="card-body">         

           <div class="table-responsive">
              <table class="table table-bordered">
               <?php                  
                $current_modules=array();
                $current_modules=explode(',',$value[0]->module_ids); 
                $monthly_limit=json_decode($value[0]->monthly_limit,true);
                $bulk_limit=json_decode($value[0]->bulk_limit,true);

                echo "<tr>"; 
                    echo "<th class='info' width='20px'>"; 
                      echo __("#");         
                    echo "</th>";                      
                    echo "<th class='info'>"; 
                      echo __("Module");         
                    echo "</th>";
                    echo "<th class='text-center info' colspan='2'>"; 
                      echo __("Usage Limit");         
                    echo "</th>";
                    echo "<th class='text-center info' colspan='2'>"; 
                      echo __("Bulk Limit");         
                    echo "</th>";
                 echo "</tr>"; 
                
                $SL=0;
                foreach($modules as $module) 
                {  
                 $SL++;
                  if(is_array($current_modules) && !in_array($module->id, $current_modules)) continue; 
                  echo "<tr>"; 
                    echo "<td class='text-center'>".$SL."</td>";   
                    echo "<td>".$module->module_name."</td>"; 

                    $xmonthly_val=0;
                    $xbulk_val=0;
                 
                    if(in_array($module->id,$current_modules))
                    {
                      $xmonthly_val=$monthly_limit[$module->id];
                      $xbulk_val=$bulk_limit[$module->id];
                    }  

                    if($module->limit_enabled=='0')
                    {
                      $disabled=" readonly";
                      $limit=__("Unlimited");
                      $style='border:none;background:#fff;';
                    }
                    else
                    {
                        $disabled=" readonly";
                        $limit=$module->extra_text;
                        $style='border:none;background:#fff;';
                    }


                    echo "<td align='center'>".$limit."</td><td align='center'><input type='text' ".$disabled." class='form-control' value='".$xmonthly_val."' min='0' style='width:70px; ".$style."' name='monthly_".$module->id."'></td>";
                  
                    if($module->bulk_limit_enabled=="0")
                    {
                      $disabled=" readonly";
                      $limit="";
                      $style='border:none;background:#fff;';

                    }
                    else
                    {
                        $disabled=" readonly";
                        $limit="";
                        $style='border:none;background:#fff;';
                    }
                    $xval=$xbulk_val;

                    echo "<td align='center'><input type='text' class='form-control' ".$disabled." value='".$xval."'  min='0' style='width:70px; ".$style."' name='bulk_".$module->id."'></td>";
                  echo "</tr>";                 
                }                
                ?>            
              </table> 
           </div>
        </div>   
        <div class="card-footer bg-whitesmoke">
          <button  type="button" class="btn btn-secondary btn-lg" onclick='goBack("payment/package_manager",0)'><i class="far fa-arrow-alt-circle-left"></i> <?php echo __("Go back");?></button>
        </div>
      </div> 
    </div>
  </div>
</section>

          
<script src="{{asset('/assets/custom-js/subscription/details-package.js')}}"></script>



@endsection