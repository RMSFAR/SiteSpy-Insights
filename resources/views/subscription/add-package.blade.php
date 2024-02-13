{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',__('Add package'))
@section('content')

<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fas fa-plus-circle"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo __("Subscription"); ?></div>
      <div class="breadcrumb-item active"><a href="{{ route('package_manager')}}"><?php echo __("Package Manager"); ?></a></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  {{-- <?php $this->load->view('admin/theme/message'); ?> --}}
  @include('shared.message')
  <div class="row">
    <div class="col-12">
<div class="card">
    <div class="card-body">
      <form class="form-horizontal" action="{{route('add_package_action')}}" method="POST">
        @csrf
        <div class="card">
          <div class="card-body">
             
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="name"> <?php echo __("Package Name")?> *</label>
                  <input name="name" value="<?php echo set_value('name');?>"  class="form-control" type="text">
                  @if ($errors->has('name'))
									<span class="text-danger">{{ $errors->first('name') }}</span>
									@endif
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="price"><?php echo __("Price")?> - <?php echo isset($payment_config[0]->currency) ? $payment_config[0]->currency : 'USD'; ?> *</label>              
                  <input name="price" value="<?php echo set_value('price');?>"  class="form-control" type="text">
                  @if ($errors->has('price'))
                  <span class="text-danger">{{ $errors->first('price') }}</span>
                  @endif            
                </div>
              </div>
            </div>         

             <div class="form-group">
               <label for="price"><?php echo __("Validity");?> *</label>              
                <div class="row">
                  <div class="col-6">
                    <input type="text" name="validity_amount" value="<?php echo set_value('validity_amount') ?>" class="form-control">
                  </div>
                  <div class="col-6">
                    <?php echo Form::select('validity_type', $validity_type, $validity_type,array('class'=>'form-control select2')); ?>
                  </div>
                </div>
                @if ($errors->has('validity_amount'))
                <span class="text-danger">{{ $errors->first('validity_amount') }}</span>
                @endif 
              
             </div>

             <div class="row">
               <div class="col-12 col-md-6">
                 <div class="form-group">
                   <label for="visible" ><i class="fas fa-hand-holding-usd"></i>  <?php echo __('Available to Purchase');?></label>
                     
                     <div class="form-group">
                       <?php 
                       $visible = set_value('visible');
                       if($visible == '') $visible='1';
                       ?>
                       <label class="custom-switch mt-2">
                         <input type="checkbox" name="visible" value="1" class="custom-switch-input"  <?php if($visible=='1') echo 'checked'; ?>>
                         <span class="custom-switch-indicator"></span>
                         <span class="custom-switch-description"><?php echo __('Yes');?></span>
                         @if ($errors->has('visible'))
                         <span class="text-danger">{{ $errors->first('visible') }}</span>
                         @endif
                       </label>
                     </div>
                 </div> 
               </div>

               <div class="col-12 col-md-6">
                 <div class="form-group" id="highlight_container">
                   <label for="highlight" ><i class="far fa-lightbulb"></i> <?php echo __('Highlighted Package');?></label>
                     
                     <div class="form-group">
                       <?php 
                       $highlight = set_value('highlight');
                       if($highlight == '') $highlight='0';
                       ?>
                       <label class="custom-switch mt-2">
                         <input type="checkbox" name="highlight" value="1" class="custom-switch-input"  <?php if($highlight=='1') echo 'checked'; ?>>
                         <span class="custom-switch-indicator"></span>
                         <span class="custom-switch-description"><?php echo __('Yes');?></span>
                         @if ($errors->has('highlight'))
                         <span class="text-danger">{{ $errors->first('highlight') }}</span>
                         @endif
                       </label>
                     </div>
                 </div> 
               </div>
             </div>

             <div class="form-group">
               <label for=""><?php echo __("Modules")?> *</label>   
               <?php $mandatory_modules = array(65,199,200); ?>
               <div class="table-responsive">
                  <table class="table table-bordered">
                   <?php                  

                    echo "<tr>"; 
                        echo "<th class='info' width='20px'>"; 
                          echo __("#");         
                        echo "</th>";
                        echo "<th class='text-center info' width='20px'>"; 
                          echo '<input class="regular-checkbox" id="all_modules" type="checkbox"/><label for="all_modules"></label>';         
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
                     echo "<tr>"; 
                        echo "<td class='text-center'>".$SL."</td>";   
                        echo "<td class='text-center'>";?>
                           <input  name="modules[]" id="box<?php echo $SL;?>" class="modules regular-checkbox <?php if(in_array($module->id, $mandatory_modules)) echo 'mandatory';?>" <?php if(in_array($module->id, $mandatory_modules)) echo 'checked onclick="return false;"';?>  type="checkbox" value="<?php echo $module->id; ?>"/> <?php

                            $style="style='cursor:pointer;'";
                            if(in_array($module->id, $mandatory_modules)) $style = "style='border-color:#6777EF;cursor:pointer;' title='".__('This is a mandatory module and can not be unchecked.')."' data-toggle='tooltip'";

                           echo "<label for='box".$SL."' ".$style."></label>";                
                        echo "</td>";

                        echo "<td>".$module->module_name."</td>";   

                        if($module->limit_enabled=='0')
                        {
                          $disabled=" readonly";
                          $limit=__("Unlimited");
                          $style='background:#ddd';
                        }
                        else
                        {
                            $disabled="";
                            $limit=$module->extra_text;
                            $style='';
                        }


                        echo "<td align='center'>".$limit."</td><td align='center'><input type='number' ".$disabled." class='form-control' value='0' min='0' style='width:70px; ".$style."' name='monthly_".$module->id."'></td>";
                      
                        if($module->bulk_limit_enabled=="0")
                        {
                          $disabled=" readonly";
                          $limit="";
                          $style='background:#ddd';

                        }
                        else
                        {
                            $disabled="";
                            $limit="";
                            $style='';
                        }
                        $xval=0;

                        echo "<td align='center'><input type='number' class='form-control' ".$disabled." value='".$xval."'  min='0' style='width:70px; ".$style."' name='bulk_".$module->id."'></td>";
                      echo "</tr>";                 
                    }                
                    ?>            
                  </table> 
               </div>    
              @if ($errors->has('modules'))
              <span class="text-danger"><?php echo "<br/><br/>".$errors->first('modules[]'); ?></span>
              @endif
             </div>    
          </div>
          <div class="card-footer bg-whitesmoke">
            <button name="submit" type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> <?php echo __("Save");?></button>
            <button  type="button" class="btn btn-secondary btn-lg float-right" onclick='goBack("payment/package_manager",0)'><i class="fa fa-remove"></i> <?php echo __("Cancel");?></button>
          </div>
        </div>
      </form>  
    </div>
  </div>
</section>

          
<script src="{{asset('/assets/custom-js/subscription/add-package.js')}}"></script>



@endsection