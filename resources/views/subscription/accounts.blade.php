{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',$page_title)
@section('content')
<style>
    .custom-switch {
        padding-left: 0 !important;
    }
    .form-check {
        padding-left: .5rem !important;
    }
</style>
<section class="section section_custom">
  <div class="section-header">
    <h1><i class="far fa-credit-card"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-breadcrumb">
      {{-- <div class="breadcrumb-item"><a href="#"><?php echo __("Integration"); ?></a></div> --}}
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

        @if (session('save_payment_accounts_status')=='1')
            <div class="alert alert-success">
                <h4 class="alert-heading">{{__('Successful')}}</h4>
                <p> {{ __('Payment accounts have been saved successfully.') }}</p>
            </div>
        @endif
        @if (session('paypal_error'))
            <div class="alert alert-warning mb-0 no-radius">
                <h4 class="alert-heading">{{__('Error in paypal credentials')}}</h4>
                <p> {{ session('paypal_error') }}</p>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-warning mb-0 no-radius">
                <h4 class="alert-heading">{{__('Something Missing')}}</h4>
                <p> {{ __('Something is missing. Please check the the required inputs.') }}</p>
            </div>
            <?php
            if(!empty($errors->all()))
                echo '<ul class="list-group mb-4">';
                foreach ($errors->all() as $err){
                    echo '<li class="list-group-item fw-bold text-warning no-radius"><i class="fas fa-exclamation-circle"></i> '.$err.'</li>';
                }
                echo '</ul>';
            ?>
        @endif

        @if (session('save_payment_accounts_minimun_one_required')=='1')
            <div class="alert alert-warning">
                <h4 class="alert-heading">{{__('No Data')}}</h4>
                <p> {{ __('You must enable at least one payment account.') }}</p>
            </div>
        @endif

        <form  class="form form-vertical" enctype="multipart/form-data" method="POST" action="{{ route('payment-settings-action') }}">
            @csrf
            <div class="row">
                <div class="col-12 <?php echo isset($iframe) && $iframe ? 'col-md-3' : 'col-md-4'?>">
                    <div class="card mb-4 <?php echo isset($iframe) && $iframe ? '' : 'h-min-480px'?>">
                        <div class="card-header">
                            <h4>{{__('Currency')}}</h4>
                        </div>
                        <div class="card-body">
                            <?php
                            $manual_payment_instruction = $xdata->manual_payment_instruction ?? '';
                            $manual_payment_status = $xdata->manual_payment_status ?? '0';
                            $decimal_point = $xdata->decimal_point ?? '2';
                            $currency_position = $xdata->currency_position ?? 'left';
                            $thousand_comma = $xdata->thousand_comma ?? '0';
                            $currency = $xdata->currency ?? 'USD';
                            ?>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="">{{ __("Currency") }} </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-wallet"></i></span>
                                            <?php echo Form::select('currency',get_country_iso_phone_currency_list('currency_name'),old('currency', $currency),array('class'=>'form-control select2'));?>
                                        </div>
                                        @if ($errors->has('currency'))
                                            <span class="text-danger"> {{ $errors->first('currency') }} </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="">{{ __("Currency Position") }} </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-adjust"></i></span>
                                            <?php echo Form::select('currency_position',['left'=>__('Left'),'right'=>__('Right')],old('currency_position', $currency_position),array('class'=>'form-control'));?>
                                        </div>
                                        @if ($errors->has('currency_position'))
                                            <span class="text-danger"> {{ $errors->first('currency_position') }} </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="">{{ __("Decimal Place") }} </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-dot-circle"></i></span>
                                            <input type="number" name="decimal_point" id="decimal_point" min="0" class="form-control" value="{{old('decimal_point',$decimal_point)}}">
                                        </div>
                                        @if ($errors->has('decimal_point'))
                                            <span class="text-danger"> {{ $errors->first('decimal_point') }} </span>
                                        @endif
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="thousand_comma" >{{ __('Thousand Comma') }}</label>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-text pt-2 w-100 bg-white px-0">
                                                    <div class="form-check form-switch">
                                                        {{-- <input class="form-check-input custom-control-input" id="thousand_comma" name="thousand_comma" type="checkbox" value="1" <?php echo old('thousand_comma',$thousand_comma)=='1' ? 'checked' : ''; ?>>
                                                        <span class="custom-switch-indicator"></span>
                                                        <label class="form-check-label" for="thousand_comma">{{__("Enable")}}</label> --}}
                                                        <label class="custom-switch mt-2">
                                                            <input type="checkbox" name="thousand_comma" id="thousand_comma" value="1" class="custom-switch-input"  @if($thousand_comma=='1') {{ 'checked' }} @else {{ '' }} @endif>
                                                            <span class="custom-switch-indicator"></span>
                                                            <span class="custom-switch-description"><?php echo __('Enable');?></span>
                                                        </label>
                                                    </div>

                                                </span>
                                            </div>
                                            @if ($errors->has('thousand_comma'))
                                                <span class="text-danger"> {{ $errors->first('thousand_comma') }} </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 <?php echo isset($iframe) && $iframe ? 'col-md-9' : 'col-md-8'?>">
                    <div class="card mb-4 h-min-480px">
                        <div class="card-header">
                            <h4>{{__('Payment APIs')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                    <div class="col-8">
                                        <div class="tab-content" id="v-pills-tabContent">

                                            <div class="tab-pane fade active show" id="paypal-block" role="tabpanel" aria-labelledby="">
                                                <?php
                                                $paypal_data = isset($xdata->paypal) ? json_decode($xdata->paypal) : [];
                                                $paypal_client_id = $paypal_data->paypal_client_id ?? '';
                                                $paypal_client_secret = $paypal_data->paypal_client_secret ?? '';
                                                $paypal_app_id = $paypal_data->paypal_app_id ?? '';
                                                $paypal_payment_type = $paypal_data->paypal_payment_type ?? 'manual';
                                                $paypal_mode = $paypal_data->paypal_mode ?? 'live';
                                                $paypal_status = $paypal_data->paypal_status ?? '0';
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Paypal Client Id") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-at"></i></span>
                                                                <input name="paypal_client_id" value="{{old('paypal_client_id',$paypal_client_id)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('paypal_client_id'))
                                                                <span class="text-danger"> {{ $errors->first('paypal_client_id') }} </span>
                                                            @endif
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">{{ __("Paypal Client Secret") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="paypal_client_secret" value="{{old('paypal_client_secret',$paypal_client_secret)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('paypal_client_secret'))
                                                                <span class="text-danger"> {{ $errors->first('paypal_client_secret') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12 d-none">
                                                        <div class="form-group">
                                                            <label for="paypal_payment_type" >{{ __('Recurring Payment') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            <input class="form-check-input" id="paypal_payment_type" name="paypal_payment_type" type="checkbox" value="recurring" <?php echo Request::segment(3) == "0" ? 'checked' : '';?>>
                                                                            <label class="form-check-label" for="paypal_payment_type">{{__("Enable")}}</label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('paypal_payment_type'))
                                                                    <span class="text-danger"> {{ $errors->first('paypal_payment_type') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="paypal_mode" >{{ __('Sandbox Mode') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="paypal_mode" name="paypal_mode" type="checkbox" value="sandbox" <?php echo old('paypal_mode',$paypal_mode)=='sandbox' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="paypal_mode">{{__("Enable")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="paypal_mode" id="paypal_mode" value="1" class="custom-switch-input" <?php echo old('paypal_mode',$paypal_mode)=='sandbox' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Enable');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('paypal_mode'))
                                                                    <span class="text-danger"> {{ $errors->first('paypal_mode') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="paypal_status" >{{ __('Status') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="paypal_status" name="paypal_status" type="checkbox" value="1" <?php echo old('paypal_status',$paypal_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="paypal_status">{{__("Active")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="paypal_status" id="paypal_status" value="1" class="custom-switch-input"  <?php echo old('paypal_status',$paypal_status)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Active');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('paypal_status'))
                                                                    <span class="text-danger"> {{ $errors->first('paypal_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="stripe-block" role="tabpanel" aria-labelledby="">
                                                <?php
                                                $stripe_data = isset($xdata->stripe) ? json_decode($xdata->stripe) : [];
                                                $stripe_secret_key = $stripe_data->stripe_secret_key ?? '';
                                                $stripe_publishable_key = $stripe_data->stripe_publishable_key ?? '';
                                                $stripe_status= $stripe_data->stripe_status ?? '0';
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Stripe Secret Key") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="stripe_secret_key" value="{{old('stripe_secret_key',$stripe_secret_key)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('stripe_secret_key'))
                                                                <span class="text-danger"> {{ $errors->first('stripe_secret_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Stripe Publishable Key") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fab fa-keycdn"></i></span>
                                                                <input name="stripe_publishable_key" value="{{old('stripe_publishable_key',$stripe_publishable_key)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('stripe_publishable_key'))
                                                                <span class="text-danger"> {{ $errors->first('stripe_publishable_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="stripe_status" >{{ __('Status') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="stripe_status" name="stripe_status" type="checkbox" value="1" <?php echo old('stripe_status',$stripe_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="stripe_status">{{__("Active")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="stripe_status" id="stripe_status" value="1" class="custom-switch-input"  <?php echo old('stripe_status',$stripe_status)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Active');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('stripe_status'))
                                                                    <span class="text-danger"> {{ $errors->first('stripe_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                             <div class="tab-pane fade" id="yoomoney-block" role="tabpanel" aria-labelledby="">
                                                <?php

                                                $yoomoney_data = isset($xdata->yoomoney) ? json_decode($xdata->yoomoney) : [];
                                                $yoomoney_shop_id = $yoomoney_data->yoomoney_shop_id ?? '';
                                                $yoomoney_secret_key = $yoomoney_data->yoomoney_secret_key ?? '';
                                                $yoomoney_status= $yoomoney_data->yoomoney_status ?? '0';
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("yoomoney Shop ID") }} </label>
                                                            <div class="input-group">

                                                                <span class="input-group-text"><i class="fab fa-keycdn"></i></span>
                                                                <input name="yoomoney_shop_id" value="{{old('yoomoney_shop_id',$yoomoney_shop_id)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('yoomoney_shop_id'))
                                                                <span class="text-danger"> {{ $errors->first('yoomoney_shop_id') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("yoomoney Secret Key") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="yoomoney_secret_key" value="{{old('yoomoney_secret_key',$yoomoney_secret_key)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('yoomoney_secret_key'))
                                                                <span class="text-danger"> {{ $errors->first('yoomoney_secret_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="yoomoney_status" >{{ __('Status') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="yoomoney_status" name="yoomoney_status" type="checkbox" value="1" <?php echo old('yoomoney_status',$yoomoney_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="yoomoney_status">{{__("Active")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="yoomoney_status" id="yoomoney_status" value="1" class="custom-switch-input"  <?php echo old('yoomoney_status',$yoomoney_status)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Active');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('yoomoney_status'))
                                                                    <span class="text-danger"> {{ $errors->first('yoomoney_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="fastspring-block" role="tabpanel" aria-labelledby="">
                                                <?php
                                                $fastspring_data = isset($xdata->fastspring) ? json_decode($xdata->fastspring) : [];
                                                $fastspring_api_src = $fastspring_data->fastspring_api_src ?? '';
                                                $fastspring_store_front = $fastspring_data->fastspring_store_front ?? '';
                                                $fastspring_status= $fastspring_data->fastspring_status ?? '0';
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("FastSpring API Source") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="fastspring_api_src" value="{{old('fastspring_api_src',$fastspring_api_src)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('fastspring_api_src'))
                                                                <span class="text-danger"> {{ $errors->first('fastspring_api_src') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("FastSpring Store Front") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fab fa-keycdn"></i></span>
                                                                <input name="fastspring_store_front" value="{{old('fastspring_store_front',$fastspring_store_front)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('fastspring_store_front'))
                                                                <span class="text-danger"> {{ $errors->first('fastspring_store_front') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>


                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="fastspring_status" >{{ __('Status') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="fastspring_status" name="fastspring_status" type="checkbox" value="1" <?php echo old('fastspring_status',$fastspring_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="fastspring_status">{{__("Active")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="fastspring_status" id="fastspring_status" value="1" class="custom-switch-input"  <?php echo old('fastspring_status',$fastspring_status)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Active');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('fastspring_status'))
                                                                    <span class="text-danger"> {{ $errors->first('fastspring_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            {{-- <div class="tab-pane fade" id="paypro-block" role="tabpanel" aria-labelledby="">
                                                <?php
                                                $paypro_data = isset($xdata->paypro) ? json_decode($xdata->paypro) : [];
                                                $paypro_vendor_id = $paypro_data->paypro_vendor_id ?? '';
                                                $paypro_secret_key = $paypro_data->paypro_secret_key ?? '';
                                                $paypro_api_secret_key = $paypro_data->paypro_api_secret_key ?? '';
                                                $paypro_validation_key = $paypro_data->paypro_validation_key ?? '';
                                                $paypro_mode = $paypro_data->paypro_mode ?? 'live';
                                                $paypro_template_id = $paypro_data->paypro_template_id ?? '';
                                                $paypro_status= $paypro_data->paypro_status ?? '0';
                                                ?>
                                                <div class="row">

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("PayPro Vendor Account ID") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                                                                <input name="paypro_vendor_id" value="{{old('paypro_vendor_id',$paypro_vendor_id)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('paypro_vendor_id'))
                                                                <span class="text-danger"> {{ $errors->first('paypro_vendor_id') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("PayPro Secret Key") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fab fa-keycdn"></i></span>
                                                                <input name="paypro_secret_key" value="{{old('paypro_secret_key',$paypro_secret_key)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('paypro_secret_key'))
                                                                <span class="text-danger"> {{ $errors->first('paypro_secret_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("PayPro Validation Key") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="paypro_validation_key" value="{{old('paypro_validation_key',$paypro_validation_key)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('paypro_validation_key'))
                                                                <span class="text-danger"> {{ $errors->first('paypro_validation_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("PayPro API Secret Key") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fab fa-keycdn"></i></span>
                                                                <input name="paypro_api_secret_key" value="{{old('paypro_api_secret_key',$paypro_api_secret_key)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('paypro_api_secret_key'))
                                                                <span class="text-danger"> {{ $errors->first('paypro_api_secret_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("PayPro Page Template ID") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                                                <input name="paypro_template_id" value="{{old('paypro_template_id',$paypro_template_id)}}"  class="form-control" type="text" placeholder="{{__('Blank means default template')}}">
                                                            </div>
                                                            @if ($errors->has('paypro_template_id'))
                                                                <span class="text-danger"> {{ $errors->first('paypro_template_id') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="paypro_mode" >{{ __('Sandbox Mode') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            <input class="form-check-input" id="paypro_mode" name="paypro_mode" type="checkbox" value="sandbox" <?php echo old('paypro_mode',$paypro_mode)=='sandbox' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="paypro_mode">{{__("Active")}}</label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('paypro_mode'))
                                                                    <span class="text-danger"> {{ $errors->first('paypro_mode') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="paypro_status" >{{ __('Status') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            <input class="form-check-input" id="paypro_status" name="paypro_status" type="checkbox" value="1" <?php echo old('paypro_status',$paypro_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="paypro_status">{{__("Active")}}</label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('paypro_status'))
                                                                    <span class="text-danger"> {{ $errors->first('paypro_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="alert alert-light-info">
                                                            <b>{{__('PayPro IPN URL')}}</b> : <br><i>{{route('paypro-ipn')}}</i>
                                                            @if($is_admin)
                                                            <br><i>{{route('paypro-ipn-ppu')}}</i>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div> --}}

                                            <div class="tab-pane fade" id="razorpay-block" role="tabpanel" aria-labelledby="">
                                                <?php
                                                $razorpay_data = isset($xdata->razorpay) ? json_decode($xdata->razorpay) : [];
                                                $razorpay_key_id = $razorpay_data->razorpay_key_id ?? '';
                                                $razorpay_key_secret = $razorpay_data->razorpay_key_secret ?? '';
                                                $razorpay_status = $razorpay_data->razorpay_status ?? '0';
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Razorpay Key ID") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="razorpay_key_id" value="{{old('razorpay_key_id',$razorpay_key_id)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('razorpay_key_id'))
                                                                <span class="text-danger"> {{ $errors->first('razorpay_key_id') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Razorpay Key Secret") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fab fa-keycdn"></i></span>
                                                                <input name="razorpay_key_secret" value="{{old('razorpay_key_secret',$razorpay_key_secret)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('razorpay_key_secret'))
                                                                <span class="text-danger"> {{ $errors->first('razorpay_key_secret') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="razorpay_status" >{{ __('Status') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="razorpay_status" name="razorpay_status" type="checkbox" value="1" <?php echo old('razorpay_status',$razorpay_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="razorpay_status">{{__("Active")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="razorpay_status" id="razorpay_status" value="1" class="custom-switch-input"  <?php echo old('razorpay_status',$razorpay_status)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Active');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('razorpay_status'))
                                                                    <span class="text-danger"> {{ $errors->first('razorpay_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="paystack-block" role="tabpanel" aria-labelledby="">
                                                <?php
                                                $paystack_data = isset($xdata->paystack) ? json_decode($xdata->paystack) : [];
                                                $paystack_secret_key = $paystack_data->paystack_secret_key ?? '';
                                                $paystack_public_key = $paystack_data->paystack_public_key ?? '';
                                                $paystack_status = $paystack_data->paystack_status ?? '0';
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Paystack Secret Key") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="paystack_secret_key" value="{{old('paystack_secret_key',$paystack_secret_key)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('paystack_secret_key'))
                                                                <span class="text-danger"> {{ $errors->first('paystack_secret_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Razorpay Key Secret") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fab fa-keycdn"></i></span>
                                                                <input name="paystack_public_key" value="{{old('paystack_public_key',$paystack_public_key)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('paystack_public_key'))
                                                                <span class="text-danger"> {{ $errors->first('paystack_public_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="paystack_status" >{{ __('Status') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="paystack_status" name="paystack_status" type="checkbox" value="1" <?php echo old('paystack_status',$paystack_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="paystack_status">{{__("Active")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="paystack_status" id="paystack_status" value="1" class="custom-switch-input"  <?php echo old('paystack_status',$paystack_status)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Active');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('paystack_status'))
                                                                    <span class="text-danger"> {{ $errors->first('paystack_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="mercadopago-block" role="tabpanel" aria-labelledby="">
                                                <?php
                                                $mercadopago_data = isset($xdata->mercadopago) ? json_decode($xdata->mercadopago) : [];
                                                $mercadopago_public_key = $mercadopago_data->mercadopago_public_key ?? '';
                                                $mercadopago_access_token = $mercadopago_data->mercadopago_access_token ?? '';
                                                $mercadopago_country = $mercadopago_data->mercadopago_country ?? '';
                                                $mercadopago_status = $mercadopago_data->mercadopago_status ?? '0';
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Mercado Pago Public Key") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="mercadopago_public_key" value="{{old('mercadopago_public_key',$mercadopago_public_key)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('mercadopago_public_key'))
                                                                <span class="text-danger"> {{ $errors->first('mercadopago_public_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Mercado Pago Access Token") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fab fa-keycdn"></i></span>
                                                                <input name="mercadopago_access_token" value="{{old('mercadopago_access_token',$mercadopago_access_token)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('mercadopago_access_token'))
                                                                <span class="text-danger"> {{ $errors->first('mercadopago_access_token') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="mercadopago_country" >{{ __('Country') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                                    <?php echo Form::select('mercadopago_country',get_mercadopago_country_list(),old('mercadopago_country', $mercadopago_country),array('class'=>'form-control select'));?>

                                                                </div>
                                                                @if ($errors->has('mercadopago_country'))
                                                                    <span class="text-danger"> {{ $errors->first('mercadopago_country') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="mercadopago_status" >{{ __('Status') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="mercadopago_status" name="mercadopago_status" type="checkbox" value="1" <?php echo old('mercadopago_status',$mercadopago_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="mercadopago_status">{{__("Active")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="mercadopago_status" id="mercadopago_status" value="1" class="custom-switch-input"  <?php echo old('mercadopago_status',$mercadopago_status)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Active');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('mercadopago_status'))
                                                                    <span class="text-danger"> {{ $errors->first('mercadopago_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="flutterwave-block" role="tabpanel" aria-labelledby="">
                                                <?php
                                                $flutterwave_data =  isset($xdata->flutterwave) ? json_decode($xdata->flutterwave) : [];
                                                if(config('app.is_demo')=='1') $flutterwave_data = [];
                                                $flutterwave_api_key = $flutterwave_data->flutterwave_api_key ?? '';
                                                $flutterwave_status = $flutterwave_data->flutterwave_status ?? '0';
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Flutterwave Public Key") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="flutterwave_api_key" value="{{old('flutterwave_api_key',$flutterwave_api_key)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('flutterwave_api_key'))
                                                                <span class="text-danger"> {{ $errors->first('flutterwave_api_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="flutterwave_status" >{{ __('Status') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-4 w-100">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="flutterwave_status" name="flutterwave_status" type="checkbox" value="1" <?php echo old('flutterwave_status',$flutterwave_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="flutterwave_status">{{__("Active")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="flutterwave_status" id="flutterwave_status" value="1" class="custom-switch-input" <?php echo old('flutterwave_status',$flutterwave_status)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Active');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('flutterwave_status'))
                                                                    <span class="text-danger"> {{ $errors->first('flutterwave_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="mollie-block" role="tabpanel" aria-labelledby="">
                                                <?php
                                                $mollie_data =  isset($xdata->mollie) ? json_decode($xdata->mollie) : [];
                                                $mollie_api_key = $mollie_data->mollie_api_key ?? '';
                                                $mollie_status = $mollie_data->mollie_status ?? '0';
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Mollie API Key") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="mollie_api_key" value="{{old('mollie_api_key',$mollie_api_key)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('mollie_api_key'))
                                                                <span class="text-danger"> {{ $errors->first('mollie_api_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="mollie_status" >{{ __('Status') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="mollie_status" name="mollie_status" type="checkbox" value="1" <?php echo old('mollie_status',$mollie_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="mollie_status">{{__("Active")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="mollie_status" id="mollie_status" value="1" class="custom-switch-input"  <?php echo old('mollie_status',$mollie_status)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Active');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('mollie_status'))
                                                                    <span class="text-danger"> {{ $errors->first('mollie_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="sslcommerz-block" role="tabpanel" aria-labelledby="">
                                                <?php
                                                $sslcommerz_data = isset($xdata->sslcommerz) ? json_decode($xdata->sslcommerz) : [];
                                                $sslcommerz_store_id = $sslcommerz_data->sslcommerz_store_id ?? '';
                                                $sslcommerz_store_password = $sslcommerz_data->sslcommerz_store_password ?? '';
                                                $sslcommerz_mode = $sslcommerz_data->sslcommerz_mode ?? 'live';
                                                $sslcommerz_status = $sslcommerz_data->sslcommerz_status ?? '0';
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("SSLCommerz Store ID") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="sslcommerz_store_id" value="{{old('sslcommerz_store_id',$sslcommerz_store_id)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('sslcommerz_store_id'))
                                                                <span class="text-danger"> {{ $errors->first('sslcommerz_store_id') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("SSLCommerz Store Password") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fab fa-keycdn"></i></span>
                                                                <input name="sslcommerz_store_password" value="{{old('sslcommerz_store_password',$sslcommerz_store_password)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('sslcommerz_store_password'))
                                                                <span class="text-danger"> {{ $errors->first('sslcommerz_store_password') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="sslcommerz_mode" >{{ __('Sandbox Mode') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="sslcommerz_mode" name="sslcommerz_mode" type="checkbox" value="sandbox" <?php echo old('sslcommerz_mode',$sslcommerz_mode)=='sandbox' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="sslcommerz_mode">{{__("Enable")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="sslcommerz_mode" id="sslcommerz_mode" value="1" class="custom-switch-input"  <?php echo old('sslcommerz_mode',$sslcommerz_mode)=='sandbox' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Enable');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('sslcommerz_mode'))
                                                                    <span class="text-danger"> {{ $errors->first('sslcommerz_mode') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="sslcommerz_status" >{{ __('Status') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="sslcommerz_status" name="sslcommerz_status" type="checkbox" value="1" <?php echo old('sslcommerz_status',$sslcommerz_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="sslcommerz_status">{{__("Active")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="sslcommerz_status" id="sslcommerz_status" value="1" class="custom-switch-input" <?php echo old('sslcommerz_status',$sslcommerz_status)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Active');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('sslcommerz_status'))
                                                                    <span class="text-danger"> {{ $errors->first('sslcommerz_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="senangpay-block" role="tabpanel" aria-labelledby="">
                                                <?php
                                                $senangpay_data = isset($xdata->senangpay) ? json_decode($xdata->senangpay) : [];
                                                $senangpay_merchent_id = $senangpay_data->senangpay_merchent_id ?? '';
                                                $senangpay_secret_key = $senangpay_data->senangpay_secret_key ?? '';
                                                $senangpay_mode = $senangpay_data->senangpay_mode ?? 'live';
                                                $senangpay_status = $senangpay_data->senangpay_status ?? '0';
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("senangPay Merchent ID") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="senangpay_merchent_id" value="{{old('senangpay_merchent_id',$senangpay_merchent_id)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('senangpay_merchent_id'))
                                                                <span class="text-danger"> {{ $errors->first('senangpay_merchent_id') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("senangPay Secret Key") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fab fa-keycdn"></i></span>
                                                                <input name="senangpay_secret_key" value="{{old('senangpay_secret_key',$senangpay_secret_key)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('senangpay_secret_key'))
                                                                <span class="text-danger"> {{ $errors->first('senangpay_secret_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="senangpay_mode" >{{ __('Sandbox Mode') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="senangpay_mode" name="senangpay_mode" type="checkbox" value="sandbox" <?php echo old('senangpay_mode',$senangpay_mode)=='sandbox' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="senangpay_mode">{{__("Enable")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="senangpay_mode" id="senangpay_mode" value="1" class="custom-switch-input" <?php echo old('senangpay_mode',$senangpay_mode)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Enable');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('senangpay_mode'))
                                                                    <span class="text-danger"> {{ $errors->first('senangpay_mode') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="senangpay_status" >{{ __('Status') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="senangpay_status" name="senangpay_status" type="checkbox" value="1" <?php echo old('senangpay_status',$senangpay_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="senangpay_status">{{__("Active")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="senangpay_status" id="senangpay_status" value="1" class="custom-switch-input" <?php echo old('senangpay_status',$senangpay_status)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Active');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('senangpay_status'))
                                                                    <span class="text-danger"> {{ $errors->first('senangpay_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="alert alert-light-info">
                                                            @if(Request::segment(3) != "0" && !empty(Request::segment(3)))
                                                            <b>{{__('Senangpay return URL')}}</b> : <br><i>{{route('ecommerce-store-proceed-checkout-senangpay')}}</i>
                                                            @elseif(Request::segment(4) != "0" && !empty(Request::segment(4)))                      
                                                            <b>{{__('Senangpay return URL')}}</b> : <br><i>{{route('whatsapp-catalog-store-proceed-checkout-senangpay')}}</i>
                                                            @else
                                                            <b>{{__('Senangpay return URL')}}</b> : <br><i>{{route('senangpay-action')}}</i>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="instamojo-block" role="tabpanel" aria-labelledby="">
                                                <?php
                                                $instamojo_data = isset($xdata->instamojo) ? json_decode($xdata->instamojo) : [];
                                                $instamojo_api_key = $instamojo_data->instamojo_api_key ?? '';
                                                $instamojo_auth_token = $instamojo_data->instamojo_auth_token ?? '';
                                                $instamojo_mode = $instamojo_data->instamojo_mode ?? 'live';
                                                $instamojo_status = $instamojo_data->instamojo_status ?? '0';
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Instamojo API Key") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="instamojo_api_key" value="{{old('instamojo_api_key',$instamojo_api_key)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('instamojo_api_key'))
                                                                <span class="text-danger"> {{ $errors->first('instamojo_api_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Instamojo Auth Token") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fab fa-keycdn"></i></span>
                                                                <input name="instamojo_auth_token" value="{{old('instamojo_auth_token',$instamojo_auth_token)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('instamojo_auth_token'))
                                                                <span class="text-danger"> {{ $errors->first('instamojo_auth_token') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="instamojo_mode" >{{ __('Sandbox Mode') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="instamojo_mode" name="instamojo_mode" type="checkbox" value="sandbox" <?php echo old('instamojo_mode',$instamojo_mode)=='sandbox' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="instamojo_mode">{{__("Enable")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="instamojo_mode" id="instamojo_mode" value="1" class="custom-switch-input" <?php echo old('instamojo_mode',$instamojo_mode)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Enable');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('instamojo_mode'))
                                                                    <span class="text-danger"> {{ $errors->first('instamojo_mode') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="instamojo_status" >{{ __('Status') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="instamojo_status" name="instamojo_status" type="checkbox" value="1" <?php echo old('instamojo_status',$instamojo_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="instamojo_status">{{__("Active")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="instamojo_status" id="instamojo_status" value="1" class="custom-switch-input" <?php echo old('instamojo_status',$instamojo_status)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Active');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('instamojo_status'))
                                                                    <span class="text-danger"> {{ $errors->first('instamojo_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>


                                            <div class="tab-pane fade" id="instamojo_v2-block" role="tabpanel" aria-labelledby="">
                                                <?php
                                                $instamojo_v2_data = isset($xdata->instamojo_v2) ? json_decode($xdata->instamojo_v2) : [];
                                                $instamojo_client_id = $instamojo_v2_data->instamojo_client_id ?? '';
                                                $instamojo_client_secret = $instamojo_v2_data->instamojo_client_secret ?? '';
                                                $instamojo_v2_mode = $instamojo_v2_data->instamojo_v2_mode ?? 'live';
                                                $instamojo_v2_status = $instamojo_v2_data->instamojo_v2_status ?? '0';
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Instamojo Client ID") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="instamojo_client_id" value="{{old('instamojo_client_id',$instamojo_client_id)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('instamojo_client_id'))
                                                                <span class="text-danger"> {{ $errors->first('instamojo_client_id') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Instamojo Client Secret") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fab fa-keycdn"></i></span>
                                                                <input name="instamojo_client_secret" value="{{old('instamojo_client_secret',$instamojo_client_secret)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('instamojo_client_secret'))
                                                                <span class="text-danger"> {{ $errors->first('instamojo_client_secret') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="instamojo_v2_mode" >{{ __('Sandbox Mode') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="instamojo_v2_mode" name="instamojo_v2_mode" type="checkbox" value="sandbox" <?php echo old('instamojo_v2_mode',$instamojo_v2_mode)=='sandbox' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="instamojo_v2_mode">{{__("Enable")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="instamojo_v2_mode" id="instamojo_v2_mode" value="1" class="custom-switch-input" <?php echo old('instamojo_v2_mode',$instamojo_v2_mode)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Enable');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('instamojo_v2_mode'))
                                                                    <span class="text-danger"> {{ $errors->first('instamojo_v2_mode') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="instamojo_v2_status" >{{ __('Status') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="instamojo_v2_status" name="instamojo_v2_status" type="checkbox" value="1" <?php echo old('instamojo_v2_status',$instamojo_v2_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="instamojo_v2_status">{{__("Active")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="instamojo_v2_status" id="instamojo_v2_status" value="1" class="custom-switch-input" <?php echo old('instamojo_v2_status',$instamojo_v2_status)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Active');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('instamojo_v2_status'))
                                                                    <span class="text-danger"> {{ $errors->first('instamojo_v2_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="toyyibpay-block" role="tabpanel" aria-labelledby="">
                                                <?php
                                                $toyyibpay_data = isset($xdata->toyyibpay) ? json_decode($xdata->toyyibpay) : [];
                                                $toyyibpay_secret_key = $toyyibpay_data->toyyibpay_secret_key ?? '';
                                                $toyyibpay_category_code = $toyyibpay_data->toyyibpay_category_code ?? '';
                                                $toyyibpay_mode = $toyyibpay_data->toyyibpay_mode ?? 'live';
                                                $toyyibpay_status = $toyyibpay_data->toyyibpay_status ?? '0';
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("toyyibPay Secret Key") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="toyyibpay_secret_key" value="{{old('toyyibpay_secret_key',$toyyibpay_secret_key)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('toyyibpay_secret_key'))
                                                                <span class="text-danger"> {{ $errors->first('toyyibpay_secret_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("toyyibPay Category Code") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fab fa-keycdn"></i></span>
                                                                <input name="toyyibpay_category_code" value="{{old('toyyibpay_category_code',$toyyibpay_category_code)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('toyyibpay_category_code'))
                                                                <span class="text-danger"> {{ $errors->first('toyyibpay_category_code') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="toyyibpay_mode" >{{ __('Sandbox Mode') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="toyyibpay_mode" name="toyyibpay_mode" type="checkbox" value="sandbox" <?php echo old('toyyibpay_mode',$toyyibpay_mode)=='sandbox' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="toyyibpay_mode">{{__("Enable")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="toyyibpay_mode" id="toyyibpay_mode" value="1" class="custom-switch-input" <?php echo old('toyyibpay_mode',$toyyibpay_mode)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Enable');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('toyyibpay_mode'))
                                                                    <span class="text-danger"> {{ $errors->first('toyyibpay_mode') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="toyyibpay_status" >{{ __('Status') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="toyyibpay_status" name="toyyibpay_status" type="checkbox" value="1" <?php echo old('toyyibpay_status',$toyyibpay_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="toyyibpay_status">{{__("Active")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="toyyibpay_status" id="toyyibpay_status" value="1" class="custom-switch-input" <?php echo old('toyyibpay_status',$toyyibpay_status)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Active');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('toyyibpay_status'))
                                                                    <span class="text-danger"> {{ $errors->first('toyyibpay_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="xendit-block" role="tabpanel" aria-labelledby="">
                                                <?php
                                                $xendit_data = isset($xdata->xendit) ? json_decode($xdata->xendit) : [];
                                                $xendit_secret_api_key = $xendit_data->xendit_secret_api_key ?? '';
                                                $xendit_status = $xendit_data->xendit_status ?? '0';
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Xendit Secret API Key") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="xendit_secret_api_key" value="{{old('xendit_secret_api_key',$xendit_secret_api_key)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('xendit_secret_api_key'))
                                                                <span class="text-danger"> {{ $errors->first('xendit_secret_api_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="xendit_status" >{{ __('Status') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="xendit_status" name="xendit_status" type="checkbox" value="1" <?php echo old('xendit_status',$xendit_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="xendit_status">{{__("Active")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="xendit_status" id="xendit_status" value="1" class="custom-switch-input" <?php echo old('xendit_status',$xendit_status)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Active');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('xendit_status'))
                                                                    <span class="text-danger"> {{ $errors->first('xendit_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="myfatoorah-block" role="tabpanel" aria-labelledby="">
                                                <?php
                                                $myfatoorah_data = isset($xdata->myfatoorah) ? json_decode($xdata->myfatoorah) : [];
                                                $myfatoorah_api_key = $myfatoorah_data->myfatoorah_api_key ?? '';
                                                $myfatoorah_mode = $myfatoorah_data->myfatoorah_mode ?? 'live';
                                                $myfatoorah_status = $myfatoorah_data->myfatoorah_status ?? '0';
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Myfatoorah API Key") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="myfatoorah_api_key" value="{{old('myfatoorah_api_key',$myfatoorah_api_key)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('myfatoorah_api_key'))
                                                                <span class="text-danger"> {{ $errors->first('myfatoorah_api_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="myfatoorah_mode" >{{ __('Sandbox Mode') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="myfatoorah_mode" name="myfatoorah_mode" type="checkbox" value="sandbox" <?php echo old('myfatoorah_mode',$myfatoorah_mode)=='sandbox' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="myfatoorah_mode">{{__("Enable")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="myfatoorah_mode" id="myfatoorah_mode" value="1" class="custom-switch-input" <?php echo old('myfatoorah_mode',$myfatoorah_mode)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Enable');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('myfatoorah_mode'))
                                                                    <span class="text-danger"> {{ $errors->first('myfatoorah_mode') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="myfatoorah_status" >{{ __('Status') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="myfatoorah_status" name="myfatoorah_status" type="checkbox" value="1" <?php echo old('myfatoorah_status',$myfatoorah_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="myfatoorah_status">{{__("Active")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="myfatoorah_status" id="myfatoorah_status" value="1" class="custom-switch-input" <?php echo old('myfatoorah_status',$myfatoorah_status)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Active');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('myfatoorah_status'))
                                                                    <span class="text-danger"> {{ $errors->first('myfatoorah_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="paymaya-block" role="tabpanel" aria-labelledby="">
                                                <?php
                                                $paymaya_data = isset($xdata->paymaya) ? json_decode($xdata->paymaya) : [];
                                                $paymaya_public_key = $paymaya_data->paymaya_public_key ?? '';
                                                $paymaya_secret_key = $paymaya_data->paymaya_secret_key ?? '';
                                                $paymaya_mode = $paymaya_data->paymaya_mode ?? 'live';
                                                $paymaya_status = $paymaya_data->paymaya_status ?? '0';
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("PayMaya Public Key") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="paymaya_public_key" value="{{old('paymaya_public_key',$paymaya_public_key)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('paymaya_public_key'))
                                                                <span class="text-danger"> {{ $errors->first('paymaya_public_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("PayMaya Secret Key") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fab fa-keycdn"></i></span>
                                                                <input name="paymaya_secret_key" value="{{old('paymaya_secret_key',$paymaya_secret_key)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('paymaya_secret_key'))
                                                                <span class="text-danger"> {{ $errors->first('paymaya_secret_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="paymaya_mode" >{{ __('Sandbox Mode') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                            <span class="input-group-text pt-2 w-100 bg-white">
                                                                <div class="form-check form-switch">
                                                                    {{-- <input class="form-check-input" id="paymaya_mode" name="paymaya_mode" type="checkbox" value="sandbox" <?php echo old('paymaya_mode',$paymaya_mode)=='sandbox' ? 'checked' : ''; ?>>
                                                                    <label class="form-check-label" for="paymaya_mode">{{__("Enable")}}</label> --}}
                                                                    <label class="custom-switch mt-2">
                                                                        <input type="checkbox" name="paymaya_mode" id="paymaya_mode" value="1" class="custom-switch-input" <?php echo old('paymaya_mode',$paymaya_mode)=='1' ? 'checked' : ''; ?>>
                                                                        <span class="custom-switch-indicator"></span>
                                                                        <span class="custom-switch-description"><?php echo __('Enable');?></span>
                                                                    </label>
                                                                </div>
                                                            </span>
                                                                </div>
                                                                @if ($errors->has('paymaya_mode'))
                                                                    <span class="text-danger"> {{ $errors->first('paymaya_mode') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="paymaya_status" >{{ __('Status') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="paymaya_status" name="paymaya_status" type="checkbox" value="1" <?php echo old('paymaya_status',$paymaya_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="paymaya_status">{{__("Active")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="paymaya_status" id="paymaya_status" value="1" class="custom-switch-input" <?php echo old('paymaya_status',$paymaya_status)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Active');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('paymaya_status'))
                                                                    <span class="text-danger"> {{ $errors->first('paymaya_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="manual-block" role="tabpanel" aria-labelledby="">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="manual_payment_status" >{{ __('Manual Payment') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="manual_payment_status" name="manual_payment_status" type="checkbox" value="1" <?php echo old('manual_payment_status',$manual_payment_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="manual_payment_status">{{__("Active")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="manual_payment_status" id="manual_payment_status" value="1" class="custom-switch-input" <?php echo old('manual_payment_status',$manual_payment_status)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Active');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('manual_payment_status'))
                                                                    <span class="text-danger"> {{ $errors->first('manual_payment_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12" id="manual_payment_instruction_block">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Manual Payment Instruction") }} </label>
                                                            <textarea name="manual_payment_instruction" id="summernote" class="summernote form-control h-min-200px w-max-200px">{{old('manual_payment_instruction',$manual_payment_instruction)}}</textarea>
                                                            @if ($errors->has('manual_payment_instruction'))
                                                                <span class="text-danger"> {{ $errors->first('manual_payment_instruction') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="cod-block" role="tabpanel" aria-labelledby="">
                                                <?php
                                                $cod_enabled = isset($xdata->cod_enabled) ? json_decode($xdata->cod_enabled) : 0;
                                                ?>
                                                <div class="row">


                                                    <div class="col-12">
                                                        <div class="form-check form-switch">
                                                          {{-- <input class="form-check-input mt-1" type="checkbox" id="cod_enabled" name="cod_enabled" value="1" @if($cod_enabled=="1") {{ "checked" }} @endif>
                                                          <label class="form-check-label" for="cod_enable">Enable</label> --}}
                                                          <label class="custom-switch mt-2">
                                                            <input type="checkbox" name="cod_enabled" id="cod_enabled" value="1" class="custom-switch-input" <?php echo old('cod_enabled',$cod_enabled)=='1' ? 'checked' : ''; ?>>
                                                            <span class="custom-switch-indicator"></span>
                                                            <span class="custom-switch-description"><?php echo __('Enable');?></span>
                                                        </label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="tap-block" role="tabpanel" aria-labelledby="">
                                                <?php
                                                $tap_data =  isset($xdata->tap) ? json_decode($xdata->tap) : [];
                                                if(config('app.is_demo')=='1') $tap_data = [];
                                                $tap_secret_key = $tap_data->tap_secret_key ?? '';
                                                $tap_status = $tap_data->tap_status ?? '0';
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Tap Secret Key") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="tap_secret_key" value="{{old('tap_secret_key',$tap_secret_key)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('tap_secret_key'))
                                                                <span class="text-danger"> {{ $errors->first('tap_secret_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="tap_status" >{{ __('Status') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-4 w-100">
                                                                        <div class="form-check form-switch">
                                                                            {{-- <input class="form-check-input" id="tap_status" name="tap_status" type="checkbox" value="1" <?php echo old('tap_status',$tap_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="tap_status">{{__("Active")}}</label> --}}
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" name="tap_status" id="tap_status" value="1" class="custom-switch-input" <?php echo old('tap_status',$tap_status)=='1' ? 'checked' : ''; ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description"><?php echo __('Active');?></span>
                                                                            </label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('tap_status'))
                                                                    <span class="text-danger"> {{ $errors->first('tap_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            {{-- <div class="tab-pane fade" id="phonepe-block" role="tabpanel" aria-labelledby="">
                                                <?php
                                                    $phonepe_data =  isset($xdata->phonepe) ? json_decode($xdata->phonepe) : [];
                                                    if(config('app.is_demo')=='1') $phonepe_data = [];
                                                    $phonepe_marchant_id = $phonepe_data->phonepe_marchant_id ?? '';
                                                    $phonepe_salt_key = $phonepe_data->phonepe_salt_key ?? '';
                                                    $phonepe_salt_key_index = $phonepe_data->phonepe_salt_key_index ?? '';
                                                    $phonepe_mode = $phonepe_data->phonepe_mode ?? 'live';
                                                    $phonepe_status = $phonepe_data->phonepe_status ?? '0';
                                                ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("MarchantId") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-at"></i></span>
                                                                <input name="phonepe_marchant_id" value="{{old('phonepe_marchant_id',$phonepe_marchant_id)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('phonepe_marchant_id'))
                                                                <span class="text-danger"> {{ $errors->first('phonepe_marchant_id') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("SaltKey") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="phonepe_salt_key" value="{{old('phonepe_salt_key',$phonepe_salt_key)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('phonepe_salt_key'))
                                                                <span class="text-danger"> {{ $errors->first('phonepe_salt_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("SaltKeyIndex") }} </label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-at"></i></span>
                                                                <input name="phonepe_salt_key_index" value="{{old('phonepe_salt_key_index',$phonepe_salt_key_index)}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('phonepe_salt_key_index'))
                                                                <span class="text-danger"> {{ $errors->first('phonepe_salt_key_index') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="phonepe_mode" >{{ __('Sandbox Mode') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                            <span class="input-group-text pt-2 w-100 bg-white">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" id="phonepe_mode" name="phonepe_mode" type="checkbox" value="sandbox" <?php echo old('phonepe_mode',$phonepe_mode)=='sandbox' ? 'checked' : ''; ?>>
                                                                    <label class="form-check-label" for="phonepe_mode">{{__("Enable")}}</label>
                                                                </div>
                                                            </span>
                                                                </div>
                                                                @if ($errors->has('phonepe_mode'))
                                                                    <span class="text-danger"> {{ $errors->first('phonepe_mode') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="phonepe_status" >{{ __('Status') }}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text pt-2 w-100 bg-white">
                                                                        <div class="form-check form-switch">
                                                                            <input class="form-check-input" id="phonepe_status" name="phonepe_status" type="checkbox" value="1" <?php echo old('phonepe_status',$phonepe_status)=='1' ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label" for="phonepe_status">{{__("Active")}}</label>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                @if ($errors->has('phonepe_status'))
                                                                    <span class="text-danger"> {{ $errors->first('phonepe_status') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div> --}}

                                        </div>
                                    </div>
{{-- 
                                    <div class="col-4">
                                        <div class="nav d-block nav-pills h-max-350px overflow-y" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                            <a class="nav-link active" data-bs-toggle="pill" href="#paypal-block" role="tab" aria-controls="" aria-selected="true">PayPal</a>
                                            <a class="nav-link" data-bs-toggle="pill"  href="#stripe-block" role="tab" aria-controls="" aria-selected="true">Stripe</a>
                                              <a class="nav-link" data-bs-toggle="pill"  href="#yoomoney-block" role="tab" aria-controls="" aria-selected="true">YooMoney</a>
                                            @if(Request::segment(3) == "0")
                                                <a class="nav-link <?php echo !$is_admin ? 'd-none' : '';?>" data-bs-toggle="pill" href="#fastspring-block" role="tab" aria-controls="" aria-selected="true">FastSpring</a>
                                                <a class="nav-link <?php echo !$is_admin ? 'd-none' : '';?>" data-bs-toggle="pill" href="#paypro-block" role="tab" aria-controls="" aria-selected="true">PayPro</a>
                                            @endif
                                                <a class="nav-link" data-bs-toggle="pill" href="#razorpay-block" role="tab" aria-controls="" aria-selected="true">Razorpay</a>
                                                <a class="nav-link" data-bs-toggle="pill" href="#paystack-block" role="tab" aria-controls="" aria-selected="true">Paystack</a>
                                                <a class="nav-link" data-bs-toggle="pill"href="#mollie-block" role="tab" aria-controls="" aria-selected="true">Mollie</a>
                                                <a class="nav-link" data-bs-toggle="pill"href="#toyyibpay-block" role="tab" aria-controls="" aria-selected="true">toyyibPay</a>
                                                <a class="nav-link" data-bs-toggle="pill" href="#paymaya-block" role="tab" aria-controls="" aria-selected="true">PayMaya</a>
                                                <a class="nav-link" data-bs-toggle="pill" href="#instamojo-block" role="tab" aria-controls="" aria-selected="true">Instamojo</a>
                                                <a class="nav-link" data-bs-toggle="pill" href="#instamojo_v2-block" role="tab" aria-controls="" aria-selected="true">Instamojo v2</a>
                                                <a class="nav-link" data-bs-toggle="pill" href="#senangpay-block" role="tab" aria-controls="" aria-selected="true">senangPay</a>
                                                <a class="nav-link" data-bs-toggle="pill" href="#xendit-block" role="tab" aria-controls="" aria-selected="true">Xendit</a>
                                                <a class="nav-link" data-bs-toggle="pill" href="#myfatoorah-block" role="tab" aria-controls="" aria-selected="true">Myfatoorah</a>
                                                <a class="nav-link" data-bs-toggle="pill" href="#mercadopago-block" role="tab" aria-controls="" aria-selected="true">Mercado Pago</a>
                                                <a class="nav-link" data-bs-toggle="pill" href="#flutterwave-block" role="tab" aria-controls="" aria-selected="true">Flutterwave</a>
                                                <a class="nav-link" data-bs-toggle="pill" href="#tap-block" role="tab" aria-controls="" aria-selected="true">Tap</a>
                                                <a class="nav-link" data-bs-toggle="pill" href="#phonepe-block" role="tab" aria-controls="" aria-selected="true">PhonePe</a>
                                                <a class="nav-link d-none" data-bs-toggle="pill"  href="#sslcommerz-block" role="tab" aria-controls="" aria-selected="true">SSLCommerz</a>
                                                @if(Request::segment(3) == "0" && (Request::segment(4) != "0"))
                                                    <a class="nav-link" data-bs-toggle="pill" href="#manual-block" role="tab" aria-controls="" aria-selected="true">{{__('Manual')}}</a>
                                                @endif
                                                @if(Request::segment(3) != "0" || Request::segment(4) != "0")
                                                    <a class="nav-link" data-bs-toggle="pill"  href="#cod-block" role="tab" aria-controls="" aria-selected="true">{{__('Cash On Delivery')}}</a>
                                                @endif
                                        </div>
                                    </div> --}}
                                    <div class="col-4 custom-nav-pills">
                                        <div class="nav d-block nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                            @if(check_build_version()=='double')
                                            <a class="nav-link active" data-toggle="pill" href="#paypal-block" role="tab" aria-controls="" aria-selected="true">PayPal</a>
                                            <a class="nav-link" data-toggle="pill"  href="#stripe-block" role="tab" aria-controls="stripe-block" aria-selected="true">Stripe</a>
                                            <a class="nav-link" data-toggle="pill"  href="#yoomoney-block" role="tab" aria-controls="yoomoney-block" aria-selected="true">YooMoney</a>
                                            <a class="nav-link" data-toggle="pill" href="#razorpay-block" role="tab" aria-controls="razorpay-block" aria-selected="true">Razorpay</a>
                                            <a class="nav-link" data-toggle="pill" href="#paystack-block" role="tab" aria-controls="paystack-block" aria-selected="true">Paystack</a>
                                            <a class="nav-link" data-toggle="pill"href="#mollie-block" role="tab" aria-controls="mollie-block" aria-selected="true">Mollie</a>
                                            <a class="nav-link" data-toggle="pill"href="#toyyibpay-block" role="tab" aria-controls="toyyibpay-block" aria-selected="true">toyyibPay</a>
                                            <a class="nav-link" data-toggle="pill" href="#paymaya-block" role="tab" aria-controls="" aria-selected="true">PayMaya</a>
                                            <a class="nav-link" data-toggle="pill" href="#instamojo-block" role="tab" aria-controls="" aria-selected="true">Instamojo</a>
                                            <a class="nav-link" data-toggle="pill" href="#instamojo_v2-block" role="tab" aria-controls="" aria-selected="true">Instamojo v2</a>
                                            <a class="nav-link" data-toggle="pill" href="#senangpay-block" role="tab" aria-controls="" aria-selected="true">senangPay</a>
                                            <a class="nav-link" data-toggle="pill" href="#xendit-block" role="tab" aria-controls="" aria-selected="true">Xendit</a>
                                            <a class="nav-link" data-toggle="pill" href="#myfatoorah-block" role="tab" aria-controls="" aria-selected="true">Myfatoorah</a>
                                            <a class="nav-link" data-toggle="pill" href="#mercadopago-block" role="tab" aria-controls="" aria-selected="true">Mercado Pago</a>
                                            <a class="nav-link" data-toggle="pill" href="#flutterwave-block" role="tab" aria-controls="" aria-selected="true">Flutterwave</a>
                                            <a class="nav-link d-none" data-toggle="pill"  href="#sslcommerz-block" role="tab" aria-controls="" aria-selected="true">SSLCommerz</a>
                                            @endif
                                            <a class="nav-link <?php if(check_build_version()!='double') echo 'active';?>" data-toggle="pill" href="#manual-block" role="tab" aria-controls="" aria-selected="true">{{__('Manual')}}</a >
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary me-1"><i class="fas fa-save"></i> {{__('Save')}}</button>
                </div>
            </div>


        </form>



</section>

{{-- <script>
    "use strict";

    $(document).ready(function() {

      $('#summernote').summernote();

    });
</script> --}}




@endsection
