@extends('layout.backoffice')

@section('table')
<?php ob_start(); ?>
<div class="table-responsive">
<table class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <td>No</td>
      <td>Registran</td>
      <td>Action</td>
    </tr>
  </thead>
  <tbody>
  @if ($data->count())
    @foreach ($data as $no => $row)
      <tr>
        <td>{{ $data->numPage() + $no+1 }}</td>
        <td>{{ $row['registran_code'] }}</td>
        <td>
          <a href="{{ route($ctrl.':edit', ['id' => $row['p_id']]) }}" class="btn btn-primary btn-xs tip-top" data-original-title="update"><i class="fa fa-pencil"></i></a>
          <a ajax-confirm="delete" href="{{ route($ctrl.':delete', ['id' => $row['p_id']]) }}" class="btn btn-danger btn-xs tip-top open-dialog" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
        </td>
      </tr>
    @endforeach
  @else
    @include('backoffice.includes.data-not-found')
  @endif
  </tbody>
</table>  
</div>            

<?php 
  $table = ob_get_contents();
  ob_end_clean();
  UI::ajax_table($table);
?>
@stop

@section('content')
<div id="content-header">
<h1>{{ $page['name'] }} - List</h1>
<div class="btn-group">
  <a href="{{ route($ctrl.':list') }}" class="btn btn-large" title="Manage Files"><i class="fa fa-list"></i> List &nbsp; <span class="label label-danger">{{ $count_data }}</span></a>
  <a href="{{ route($ctrl.':add') }}" class="btn btn-large" title="Manage Files"><i class="fa fa-plus"></i> Add</a>
</div>
</div>
<div id="breadcrumb">
  <a href="{{ route($ctrl.':list') }}" title="{{ $page['name'] }}" class="tip-bottom"><i class="fa fa-home"></i> {{ $page['name'] }}</a>
  <a href="javascript:;" class="current">List</a>
</div>

<div class="row">
  <div class="col-xs-12">
    <div class="widget-box">
      <div class="widget-title">
        <span class="icon">
          <i class="fa fa-th"></i>
        </span>
        <h5>{{ $page['name'] }}</h5>
      </div>
        <div class="widget-content ajax-table">
          @yield('table')
        </div>
    </div>
  </div>
</div>
@stop

@section('footer_script')
@stop