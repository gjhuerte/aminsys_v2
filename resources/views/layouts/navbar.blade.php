@if(Auth::user()->accesslevel == 0)
@include('layouts.navbar.amo.default')
@elseif(Auth::user()->accesslevel == 1)
@include('layouts.navbar.accounting.default')
@else
@include('layouts.navbar.admin.default')
@endif