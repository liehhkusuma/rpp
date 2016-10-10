@extends('layout.backoffice')

@section('vendor_css')
<link rel="stylesheet" href="<?php echo assets('bo.css');?>/select2.css" />
<link rel="stylesheet" href="<?php echo assets('bo.css');?>/jquery-ui.css" />
<link rel="stylesheet" href="<?php echo assets('bo.css');?>/icheck/flat/blue.css" />
<link rel="stylesheet" href="<?php echo assets('bo.css');?>/unicorn.css" />
<link rel="stylesheet" href="<?php echo assets('bo.css');?>/colorpicker.css " />
<link rel="stylesheet" href="<?php echo assets('bo.css');?>/datepicker.css" />
@stop

@section('vendor_js')
<script src="<?php echo assets('bo.js');?>/select2.min.js"></script>
<script src="<?php echo assets('bo.js');?>/jquery.icheck.min.js"></script>
<script src="<?php echo assets('bo.js');?>/unicorn.form_common.js"></script>
<script src="<?php echo assets('bo.js');?>/jquery.nicescroll.min.js"></script>
<script src="<?php echo assets('bo.js');?>/unicorn.js"></script>
@stop

@section('content')
<div id="content-header">
<h1>{{ $page['name'] }} - Add</h1>
<div class="btn-group">
  <a href="{{ session('list_page') }}" class="btn btn-large" title="Manage Files"><i class="fa fa-arrow-left"></i> Back</a>
</div>
</div>
<div id="breadcrumb">
  <a href="{{ route($ctrl.':list') }}" title="{{ $page['name'] }}" class="tip-bottom"><i class="fa fa-home"></i> {{ $page['name'] }}</a>
  <a href="javascript:;" class="current">Add</a>
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <div class="notif"></div>

      <div class="widget-box">
        <div class="widget-title">
          <span class="icon">
            <i class="fa fa-align-justify"></i>                 
          </span>
          <h5>Add Content</h5>
        </div>
        <div class="widget-content nopadding">
          <form id="theform" action="{{ route($ctrl.':store') }}" class="form-horizontal" ajax-form="true">
            <div class="form-group">
              <label class="col-sm-3 col-md-3 col-lg-2 control-label">Registran</label>
              <div class="col-sm-9 col-md-9 col-lg-10">
                {{ select("p_no_regis", $module_access_select, '', 'class="select2"') }}
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 col-md-3 col-lg-2 control-label">Perkembangan 1</label>
              <div class="col-sm-9 col-md-9 col-lg-10">
                <input type="text" name="p_a1" class="form-control input-sm" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 col-md-3 col-lg-2 control-label">Perkembangan 2</label>
              <div class="col-sm-9 col-md-9 col-lg-10">
                <input type="text" name="p_a2" class="form-control input-sm" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 col-md-3 col-lg-2 control-label">Perkembangan 3</label>
              <div class="col-sm-9 col-md-9 col-lg-10">
                <input type="text" name="p_a3" class="form-control input-sm" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 col-md-3 col-lg-2 control-label">Perkembangan 4</label>
              <div class="col-sm-9 col-md-9 col-lg-10">
                <input type="text" name="p_a4" class="form-control input-sm" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 col-md-3 col-lg-2 control-label">Perkembangan 5</label>
              <div class="col-sm-9 col-md-9 col-lg-10">
                <input type="text" name="p_a5" class="form-control input-sm" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 col-md-3 col-lg-2 control-label">Perkembangan 6</label>
              <div class="col-sm-9 col-md-9 col-lg-10">
                <input type="text" name="p_a6" class="form-control input-sm" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 col-md-3 col-lg-2 control-label"></label>
              <div class="col-sm-9 col-md-9 col-lg-10">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>            
    </div>
  </div>
</div>
@stop

@section('footer_script')
<script type="text/javascript">
$(function(){
  $("#theform").validate({{ $validation }});
});
</script>
@stop