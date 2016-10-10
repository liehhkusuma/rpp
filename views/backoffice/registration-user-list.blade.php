@extends('layout.backoffice')

@section('table')
<?php ob_start(); ?>
<div class="table-responsive">
<table class="table table-bordered table-striped table-hover sortable" data-table="BoMenuElq">
  <thead>
    <tr>
      <td width="40px">No</td>
      <td>Registran</td>
      <td>Perkembangan 1</td>
      <td>Perkembangan 2</td>
      <td>Perkembangan 3</td>
      <td>Perkembangan 4</td>
      <td>Perkembangan 5</td>
      <td>Perkembangan 6</td>
    </tr>
  </thead>
  <tbody>
  @if ($data->count())
    @foreach ($data as $no => $row)
    <tr>
      <td>{{ $data->numPage() + $no+1 }}</td>
      <td>{{ $row['registran_code'] }}</td>
      <td>{{ $row['p_a1'] }}</td>
      <td>{{ $row['p_a2'] }}</td>
      <td>{{ $row['p_a3'] }}</td>
      <td>{{ $row['p_a4'] }}</td>
      <td>{{ $row['p_a5'] }}</td>
      <td>{{ $row['p_a6'] }}</td>
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
  <!-- <a href="javascript:;" class="btn btn-large btn-sorting"><span class="fa fa-sort"></span> Sorting </a> -->
  <a href="{{ route($ctrl.':list') }}" class="btn btn-large" title="Manage Files"><i class="fa fa-list"></i> List &nbsp; <span class="label label-danger">{{ $count_data }}</span></a>
  <!-- <a href="{{ route($ctrl.':add') }}" class="btn btn-large" title="Manage Files"><i class="fa fa-plus"></i> Add</a> -->
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