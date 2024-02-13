{{-- @extends('layouts.app') --}}
@extends('design.app')
@section('title',$page_title)
@section('content')

<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fas fa-plus-circle"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo __("Subscription"); ?></div>
      <div class="breadcrumb-item active"><a href="{{route('announcement_full_list')}}"><?php echo __("Announcement"); ?></a></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  @include('shared.message')

  <div class="section-body">

    <div class="row">
      <div class="col-12">
        <form class="form-horizontal" action="{{route('announcement_add_action')}}" method="POST">
          @csrf
          <div class="card">
            <div class="card-body">
              <div class="form-group">
                <label><?php echo __("Title"); ?> *</label><br/>
                <input type="text" id="title" name="title" class="form-control"/>
                @if ($errors->has('title'))
                <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
              </div>

              <div class="form-group">
                <label><?php echo __("Description"); ?> *</label><br/>
                <textarea name="description" style="height:200px !important;" class="form-control" id="description"></textarea>
                @if ($errors->has('description'))
                <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
              </div>

              <div class="form-group">
                <div class="form-group">
                  <label for="status" > <?php echo __('Status');?></label><br>
                  <label class="custom-switch mt-2">
                    <input type="checkbox" name="status" value="published" class="custom-switch-input" checked>
                    <span class="custom-switch-indicator"></span>
                    <span class="custom-switch-description"><?php echo __('Publish');?></span>
                    @if ($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                    @endif
                  </label>
                </div> 
              </div>
              
            </div>
            <div class="card-footer bg-whitesmoke">
              <button name="submit" type="submit" id="save_announcement" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> <?php echo __("Save");?></button>
              <button  type="button" class="btn btn-secondary btn-lg float-right" onclick='goBack("announcement/full_list",0)'><i class="fa fa-remove"></i> <?php echo __("Cancel");?></button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>


@endsection
