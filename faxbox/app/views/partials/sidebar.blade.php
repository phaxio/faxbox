<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li>
                <a class="active" href="{{ route('dashboard') }}"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-fax fa-fw"></i> Faxes<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    @if (Sentry::check() && (Sentry::getUser()->hasAccess('superuser') || Sentry::getUser()->hasAccess('send_fax')))
                    <li>
                        <a href="{{ action('FaxController@create') }}">Send New Fax</a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ action('FaxController@index') }}">View Faxes</a>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>
            <li>
                <a href="#"><i class="fa fa-phone fa-fw"></i> Phone Numbers<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ action('PhoneController@create') }}">Create New</a>
                    </li>
                    <li>
                        <a href="{{ action('PhoneController@index') }}">View All</a>
                    </li>
                </ul>
            </li>
            @if (Sentry::check() && Sentry::getUser()->hasAccess('superuser'))
            <li>
                <a href="#"><i class="fa fa-sitemap fa-fw"></i> Groups<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a {{ (Request::is('groups/create') ? 'class="active"' : '') }} href="{{ action('GroupController@create') }}">Create New</a>
                    </li>
                    <li>
                        <a href="{{ action('GroupController@index') }}">View All</a>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>
            <li>
                <a href="#"><i class="fa fa-users fa-fw"></i> Users<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ action('UserController@index') }}">View All</a>
                    </li>
                    <li>
                        <a href="{{ action('UserController@create') }}">Create New</a>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>
            @endif
            <li>
                <a href="#"><i class="fa fa-wrench fa-fw"></i> Settings<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="#">Pahxio API Keys</a>
                    </li>
                    <li>
                        <a href="#">Appearance</a>
                    </li>
                    <li>
                        <a href="#">SMTP Settings</a>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->
</nav>