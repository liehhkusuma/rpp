@extends('layout.backoffice')

@section('vendor_css')
<link rel="stylesheet" href="<?php echo assets('bo.css');?>/select2.css" />
<link rel="stylesheet" href="<?php echo assets('bo.css');?>/jquery-ui.css" />
<link rel="stylesheet" href="<?php echo assets('bo.css');?>/icheck/flat/blue.css" />
<link rel="stylesheet" href="<?php echo assets('bo.css');?>/unicorn.css" />
<link rel="stylesheet" href="<?php echo assets('bo.css');?>/datepicker.css" />
<link rel="stylesheet" href="<?php echo assets('bo.vendor');?>/Jcrop/jquery.Jcrop.min.css">
<link href="<?php echo assets('bo.vendor');?>/fancybox/fancybox.css" rel="stylesheet">
@stop

@section('vendor_js')
<script src="<?php echo assets('bo.js');?>/select2.min.js"></script>
<script src="<?php echo assets('bo.js');?>/jquery.icheck.min.js"></script>
<script src="<?php echo assets('bo.js');?>/jquery.nicescroll.min.js"></script>
<script src="<?php echo assets('bo.js');?>/unicorn.js"></script>
<script src="<?php echo assets('bo.vendor');?>/fancybox/fancybox.min.js"></script>
<script src="<?php echo assets('bo.vendor');?>/uploader/plupload.js"></script>
<script src="<?php echo assets('bo.vendor');?>/uploader/plupload.html5.js"></script>
<script src="<?php echo assets('bo.vendor');?>/uploader/plupload.html4.js"></script>
<script src="<?php echo assets('bo.vendor');?>/uploader/plupload.flash.js"></script>
<script src="<?php echo assets('bo.vendor');?>/Jcrop/jquery.Jcrop.min.js"></script>

@stop

@section('content')
<div id="content-header">
<h1>Users - Add</h1>
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
              <label class="col-sm-3 col-md-3 col-lg-2 control-label">{{ $lang['bu_real_name'] }}</label>
              <div class="col-sm-9 col-md-9 col-lg-10">
                <input type="text" name="bu_real_name" class="form-control input-sm" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 col-md-3 col-lg-2 control-label">{{ $lang['bu_no_regis'] }}</label>
              <div class="col-sm-9 col-md-9 col-lg-10">
                <input type="text" name="bu_no_regis" class="form-control input-sm" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 col-md-3 col-lg-2 control-label">{{ $lang['bu_init'] }}</label>
              <div class="col-sm-9 col-md-9 col-lg-10">
                <input type="text" name="bu_init" class="form-control input-sm" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 col-md-3 col-lg-2 control-label">{{ $lang['bu_email'] }}</label>
              <div class="col-sm-9 col-md-9 col-lg-10">
                <input type="text" name="bu_email" class="form-control input-sm" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 col-md-3 col-lg-2 control-label">{{ $lang['bu_name'] }}</label>
              <div class="col-sm-9 col-md-9 col-lg-10">
                <input type="text" name="bu_name" class="form-control input-sm" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 col-md-3 col-lg-2 control-label">{{ $lang['bu_passwd'] }}</label>
              <div class="col-sm-9 col-md-9 col-lg-10">
                <input type="password" name="bu_passwd" class="form-control input-sm" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 col-md-3 col-lg-2 control-label">{{ $lang['bu_level'] }}</label>
              <div class="col-sm-9 col-md-9 col-lg-10">
                {{ select("bu_level", $module_access_select, '', 'class="select2"') }}
              </div>
            </div>

            <!-- <div class="form-group">
                <label class="col-sm-2 control-label">{{ $lang['bu_pic'] }} <span class="asterisk">*</span></label>
                <div class="col-sm-3 uploader" data-page="{{ $pagename }}" data-btn="Photo" data-type="cropping" data-lang="">
                  <div id="uploaderContainer1" class="gen-btn">
                      <span class="filemsg"></span>
                      <a href="#" id="uploadBtn1" title="Upload Foto" class="btn btn-primary text-upper">Upload</a>
                  </div>
                  
                  <input type="hidden" class="filename" name="bu_pic" />
                  <input type="hidden" class="x" name="x" />
                  <input type="hidden" class="y" name="y" />
                  <input type="hidden" class="w" name="w" />
                  <input type="hidden" class="h" name="h" />
                  
                  <div class="modal fade JCrop_preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title" class="myModalLabel">Croping Image</h4>
                        </div>
                        <div class="modal-body">
                          
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-primary JC-upload">Upload</button>
                          <button type="button" class="btn btn-default JC-cancel">Cancel</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div> -->

            <div class="form-group">
              <label class="col-sm-3 col-md-3 col-lg-2 control-label">Status</label>
              <div class="col-sm-9 col-md-9 col-lg-10">
                <label><input type="radio" name="status" value="y" checked/> Active</label>
                <label><input type="radio" name="status" value="n" /> InActive</label>
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