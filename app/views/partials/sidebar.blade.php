<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li>
                <a href="{{ action('FaxController@index') }}"><i class="fa fa-fax fa-fw"></i> Faxes</a>
            </li>
            @if (Sentry::check() && Sentry::getUser()->hasAccess('purchase_numbers'))
            <li>
                <a href="{{ action('PhoneController@index') }}"><i class="fa fa-phone fa-fw"></i> Phone Numbers</a>
            </li>
            @endif
            
            @if (Sentry::check() && Sentry::getUser()->hasAccess('superuser'))
            <li>
                <a href="{{ action('GroupController@index') }}"><i class="fa fa-sitemap fa-fw"></i> Groups</a>
            </li>
            
            <li>
                <a href="{{ action('UserController@index') }}"><i class="fa fa-users fa-fw"></i> Users</a>
            </li>
            @endif
            
            @if (Sentry::check() && Sentry::getUser()->hasAccess('update_settings'))
            <li>
                <a href="#"><i class="fa fa-wrench fa-fw"></i> Settings<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ action('SettingController@editFaxApi') }}">Phaxio API Keys</a>
                    </li>
                    <li>
                        <a href="{{ action('SettingController@editAppearance') }}">Appearance</a>
                    </li>
                    <li>
                        <a href="{{ action('SettingController@editMail') }}">Mail Server</a>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>
            @endif
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->
</nav>