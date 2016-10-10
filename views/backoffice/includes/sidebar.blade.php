<div id="search">
	<form class="searchform" method="get" action="{{ request_url() }}" ajax-form="table">
		<input type="text" name="keyword" placeholder="Search here..." value="{{ get('keyword') }}" /><button type="submit"><i class="fa fa-search"></i></button>
	</form>
</div>	
<ul>
	<li class="<?php if($sidebar == 'dashboard') { echo 'active'; }else{ echo ''; } ?>"><a href="{{ route('DashboardCtrl:list') }}"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>

	@if(AuthCtrl::user()->bu_level == 0 || AuthCtrl::user()->bu_level == 1)
	<li class="<?php if($sidebar == 'user') { echo 'active'; }else{ echo ''; } ?>"><a href="{{ route('UserCtrl:list') }}">User List</a></li>
	<li class="<?php if($sidebar == 'registration') { echo 'active'; }else{ echo ''; } ?>"><a href="{{ route('RegistrationCtrl:list') }}">Registration</a></li>
	@endif
	@if(AuthCtrl::user()->bu_level == 0 || AuthCtrl::user()->bu_level == 1 || AuthCtrl::user()->bu_level == 2)
	<li class="<?php if($sidebar == 'registration-user') { echo 'active'; }else{ echo ''; } ?>"><a href="{{ route('RegistrationUserCtrl:list') }}">Registration User</a></li>
	@endif

</ul>