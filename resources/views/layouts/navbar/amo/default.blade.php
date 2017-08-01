<style>
  .panel{
    box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
  }

  .nav > li > a {
    margin-top: 7px;
  }
</style>
<nav class="navbar navbar-default" style="background-color: white; color: #800000;">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-2">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <div class="navbar-brand" style="margin-bottom: 10px;">
          <!-- Branding Image -->
          <a class="" href="{{ url('/') }}">
              <div style="color: #800000;">
                  <div class="col-md-4" style="margin: 0px;padding: 0px;width: 32px;">
                      <img src="{{ asset('images/logo.png') }}" style="height: 32px;width:32px;" />
                  </div>
                  <div class="col-md-8" style="font-size: 12px;white-space:nowrap;">
                      <div class="row">
                          <h5><strong>{{ config('app.name', 'Supplies Inventory Management System') }}</strong></h5>
                      </div>
                  </div>  
              </div>
          </a>
      </div>
    </div><!-- end of brand toggle -->

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="navbar-collapse-2">

      <!-- navbar -->
      <ul class="nav navbar-nav" style="color: #800000;">
        <li>{{ link_to('inventory/supply','Supply Inventory') }}</li>
        <li>{{ link_to('inventory/supply/rsmi','RSMI') }}</li>

        <!-- maintenance dropdown tab -->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            Information System <span class="caret"></span></a>
          <!-- dropdown items -->
          <ul class="dropdown-menu">
            <!-- maintenance tab -->
            <li>{{ link_to('maintenance/supply','Supply') }}</li>
            <li>{{ link_to('maintenance/office','Office') }}</li>
          </ul> <!-- end of dropdown items -->
        </li> <!-- end of maintenance dropdown tab -->

      </ul><!-- end of navbar -->
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle text-capitalize" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            {{{ Auth::user()->firstname }}} {{{ Auth::user()->lastname }}}<span class="caret"></span></a>
          <!-- dropdown items -->
          <ul class="dropdown-menu">
            <li>{{ link_to('settings','Settings') }}</li>
            <li role="separator" class="divider"></li>
            <li>{{ link_to('logout','Logout') }}</li>
          </ul> <!-- end of dropdown items -->
        </li> <!-- end of maintenance dropdown tab -->

      </ul><!-- end of navbar right -->
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container -->
</nav><!-- /.navbar -->
