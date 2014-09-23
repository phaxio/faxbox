<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
<div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    
    
    @if($logo = Setting::get('faxbox.logo', true))
    <img src="{{ asset('userdata/images/'.$logo) }}" width="100px" class="pull-left">
    @endif
    
    <a class="navbar-brand" href="{{ route('home') }}">
    {{ Setting::get('faxbox.name', true) ?: 'Faxbox' }}
    </a>
</div>
<!-- /.navbar-header -->

<ul class="nav navbar-top-links navbar-right">
<li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#"> {{ Sentry::getUser()->first_name }} {{ Sentry::getUser()->last_name }}
        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
    </a>
    <ul class="dropdown-menu dropdown-user">
        <li><a href="{{ action('UserController@edit', [Sentry::getUser()->getId()]) }}"><i class="fa fa-user fa-fw"></i> User Profile</a>
        </li>
        <li class="divider"></li>
        <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
        </li>
    </ul>
    <!-- /.dropdown-user -->
</li>
<!-- /.dropdown -->
</ul>
<!-- /.navbar-top-links -->