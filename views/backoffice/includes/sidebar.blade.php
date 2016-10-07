<div id="search">
	<form class="searchform" method="get" action="{{ request_url() }}" ajax-form="table">
		<input type="text" name="keyword" placeholder="Search here..." value="{{ get('keyword') }}" /><button type="submit"><i class="fa fa-search"></i></button>
	</form>
</div>	
<ul>
	<li class="<?php if($sidebar == 'dashboard') { echo 'active'; }else{ echo ''; } ?>"><a href="{{ route('DashboardCtrl:list') }}"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>

	<li class="<?php if($sidebar == 'user') { echo 'active'; }else{ echo ''; } ?>"><a href="{{ route('UserCtrl:list') }}">User List</a></li>
	<li class="<?php if($sidebar == 'registran') { echo 'active'; }else{ echo ''; } ?>"><a href="{{ route('RegistranCtrl:list') }}">Registration</a></li>

</ul>