	@if (session('success_message')=='1')
            <div class="alert alert-success">
                <h4 class="alert-heading">{{__('Successful')}}</h4>
                <p> {{ __('Your data has been successfully stored into the database.') }}</p>
            </div>
    @endif

	@if (session('warning_message')=='1')
            <div class="alert alert-warning">
                <h4 class="alert-heading">{{__('Warning')}}</h4>
                <p> {{ __('Something went wrong, please try again.') }}</p>
            </div>
    @endif

	@if (session('error_message')=='1')
            <div class="alert alert-danger">
                <h4 class="alert-heading">{{__('Error')}}</h4>
                <p> {{ __('Your data was failed to stored into the database.') }}</p>
            </div>
    @endif

	@if (session('delete_success_message')=='1')
            <div class="alert alert-success">
                <h4 class="alert-heading">{{__('Success')}}</h4>
                <p> {{ __('Your data has been successfully deleted from the database.') }}</p>
            </div>
    @endif

	@if (session('delete_error_message')=='1')
            <div class="alert alert-danger">
                <h4 class="alert-heading">{{__('Error')}}</h4>
                <p> {{ __('Your was failed to delete from the database.') }}</p>
            </div>
    @endif

	@if (session('payment_cancel')=='1')
            <div class="alert alert-warning">
                <h4 class="alert-heading">{{__('Warning')}}</h4>
                <p> {{ __('Payment has been cancelled.') }}</p>
            </div>
			@php session()->forget('payment_cancel'); @endphp
    @endif
	
	@if (session('payment_success')=='1')
            <div class="alert alert-success">
                <h4 class="alert-heading">{{__('Successful')}}</h4>
                <p> {{ __('Payment has been processed successfully. You may need a logout to affect subscription changes. It may take few minutes to appear payment in this list.') }}</p>
            </div>
			@php session()->forget('payment_success'); @endphp
    @endif

