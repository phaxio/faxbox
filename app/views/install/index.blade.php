<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Faxbox</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="{{ asset('css/plugins/metisMenu/metisMenu.min.css') }}" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="{{ asset('css/plugins/timeline.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="{{ asset('css/plugins/morris.css') }}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{ asset('font-awesome-4.1.0/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<div id="wrapper">
	<!-- Notifications -->
	@include('partials.notifications')
	<!-- ./ notifications -->
    <div class="container">
        <div class="row">
        	{{ Form::open(['action' => 'InstallController@store']) }}
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default" style="margin-top:100px">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{trans('install.title')}}</h3>
                    </div>
                    <div class="panel-body">
                       <div class="row">
							<div class="col-sm-12">
								<hr>
								<h4>Server Checks</h4>
								<ul class="list-unstyled" id="checks">
									<li data-url="checkVersion"><span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Checking PHP version is 5.4+</li>
									<li data-url="checkExtension" data-input='{"ext-name":"mcrypt"}'><span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Checking for <b>Mcrypt</b> extension</li>
									<li data-url="checkExtension" data-input='{"ext-name":"intl"}'><span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Checking for <b>intl</b> extension</li>
									<li data-url="checkPermissions"><span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Checking permissions</li>
								</ul>
								<hr>
							</div>
					   </div>
                        <div class="row">
							<div class="col-sm-12">
								<h4>Site Name <small>(optional)</small></h4>
								<p>This name is used in the header. Usually your business name.</p>
								<fieldset>
									<div class="form-group">
									  <label for="name">{{ trans('install.name') }}</label>
									  {{ Form::text('name', null, array('class' => 'form-control', 'placeholder' => trans('install.name'))) }}
									  {{ ($errors->has('name') ? $errors->first('name') : '') }}
									</div>
								</fieldset>
								<hr>
							</div>
                        </div>
                        
                        <div class="row">
							<div class="col-sm-12">
								<h4>Site Url <small>(Required)</small></h4>
								<p>What is the base url you will use to access the site?</p>
								<fieldset>
									<div class="form-group {{ ($errors->has('app.url')) ? 'has-error' : '' }}">
									  <label for="app[url]">{{ trans('install.url') }}</label>
									  {{ Form::text('app[url]', 'http://', array('class' => 'form-control', 'placeholder' => trans('install.url'))) }}
									  {{ ($errors->has('app.url') ? $errors->first('app.url') : '') }}
									</div>
								</fieldset>
								<hr>
							</div>
						</div>
                        
                        <div class="row">
							<div class="col-sm-12">
								<h4>Phaxio API Keys <small>(Required)</small></h4>
								<p>Your Phaxio api keys can be found in your <a href="http://www.phaxio.com/apiSettings" target="_blank">Phaxio account page</a>.</p>
								<fieldset>
									<div class="form-group {{ ($errors->has('services.phaxio.public')) ? 'has-error' : '' }}">
									  <label for="services[phaxio][public]">{{ trans('install.apiPublic') }}</label>
									  {{ Form::text('services[phaxio][public]', null, array('class' => 'form-control', 'placeholder' => trans('install.apiPublic'))) }}
									  {{ ($errors->has('services.phaxio.public') ? $errors->first('services.phaxio.public') : '') }}
									</div>
									<div class="form-group {{ ($errors->has('services.phaxio.secret')) ? 'has-error' : '' }}">
									  <label for="services[phaxio][secret]">{{ trans('install.apiSecret') }}</label>
									  {{ Form::text('services[phaxio][secret]', null, array('class' => 'form-control', 'placeholder' => trans('install.apiSecret'))) }}
									  {{ ($errors->has('services.phaxio.secret') ? $errors->first('services.phaxio.secret') : '') }}
									</div>
								</fieldset>
								<hr>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-12">
								<h4>Create Admin <small>(Required)</small></h4>
								<p>This user will have access to everything.</p>
								<fieldset>
									<div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
									  <label for="first_name">{{ trans('users.firstname') }}</label>
									  {{ Form::text('admin[first_name]', null, array('class' => 'form-control', 'placeholder' => trans('users.firstname'))) }}
									  {{ ($errors->has('first_name') ? $errors->first('first_name') : '') }}
									</div>
									<div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
									  <label for="last_name">{{ trans('users.lastname') }}</label>
									  {{ Form::text('admin[last_name]', null, array('class' => 'form-control', 'placeholder' => trans('users.lastname'))) }}
									  {{ ($errors->has('last_name') ? $errors->first('last_name') : '') }}
									</div>
									<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
									  <label for="email">{{ trans('users.email') }}</label>
									  {{ Form::text('admin[email]', null, array('class' => 'form-control', 'placeholder' => trans('users.email'))) }}
									  {{ ($errors->has('email') ? $errors->first('email') : '') }}
									</div>
									<div class="form-group {{ ($errors->has('password_confirmation')) ? 'has-error' : '' }}">
									  <label for="password">{{ trans('users.password') }}</label>
									  {{ Form::password('admin[password]', array('class' => 'form-control', 'placeholder' => trans('users.password'))) }}
									  {{ ($errors->has('password') ? $errors->first('password') : '') }}
									  {{ Form::password('admin[password_confirmation]', array('class' => 'form-control', 'placeholder' => trans('users.passwordconfirmed'))) }}
									  {{ ($errors->has('password_confirmation') ? $errors->first('password_confirmation') : '') }}
									</div>
								</fieldset>
								<hr>
							</div>
						</div>
                        
                        <div class="row">
							<div class="col-sm-12">
								<a id="advanced" href="#">Advanced <i class="fa fa-plus-square-o"></i></a>
								<fieldset id="advanced-settings" style="display:none">
									<h4>Database Settings</h4>
									<p>For simplicity we'll use SQLite by default (which requires no configuration). If you want to specify another database driver you may do that here. Please make sure you've created the database first before clicking install.</p>
									
									<div class="form-group">
								    	<label for="database[driver]">{{ trans('install.dbdriver') }}</label>
										{{ Form::select('database[default]', ['sqlite' => 'SQLite', 'mysql' => 'MySQL'], 'sqlite', array('class' => 'form-control', 'placeholder' => trans('install.dbdriver'))) }}
										{{ ($errors->has('database.default') ? $errors->first('database.default') : '') }}
									</div>
									
									<div class="form-group">
										<label for="database[database]">{{ trans('install.dbname') }}</label>
										{{ Form::text('database[database]', app_path('database/production.sqlite'), array('class' => 'form-control', 'placeholder' => trans('install.dbname'))) }}
										{{ ($errors->has('database.database') ? $errors->first('database.database') : '') }}
									</div>
									
									<div class="dbextras mysql" style="display:none">
										<div class="form-group">
											<label for="database[host]">{{ trans('install.dbhost') }}</label>
											{{ Form::text('database[host]', null, array('class' => 'form-control', 'placeholder' => trans('install.dbhost'))) }}
											{{ ($errors->has('database.host') ? $errors->first('database.host') : '') }}
										</div>
										
										<div class="form-group">
											<label for="database[username]">{{ trans('install.dbusername') }}</label>
											{{ Form::text('database[username]', null, array('class' => 'form-control', 'placeholder' => trans('install.dbusername'))) }}
											{{ ($errors->has('database.username') ? $errors->first('database.username') : '') }}
										</div>
										
										<div class="form-group">
											<label for="database[password]">{{ trans('install.dbpassword') }}</label>
											{{ Form::text('database[password]', null, array('class' => 'form-control', 'placeholder' => trans('install.dbpassword'))) }}
											{{ ($errors->has('database.password') ? $errors->first('database.password') : '') }}
										</div>
									</div>
									<br>
									<a href="#" id="testDB" data-url="checkDBCredentials"><span class="spinner" style="display:none"><i class="fa fa-spinner fa-spin"></i></span> Test DB Settings</a>
								</fieldset>
								<br>
								{{ Form::submit(trans('install.submit'), ['class' => 'btn btn-primary pull-right']) }}
							</div>
						</div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<!-- jQuery Version 1.11.0 -->
<script src="{{ asset('js/jquery-1.11.0.js') }}"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('js/bootstrap.min.js') }}"></script>

<script>
	$(function(){
		var checks = true;
		
		$("#checks li").each(function(index){
			var $this = $(this);
			var url = $this.data('url');
			var input = $this.data('input');
				
			doRequest(url, input, $this);
		});
			
		
		$("#advanced").click(function(e){
			e.preventDefault();
			$("#advanced-settings").slideToggle();
		});
		
		if($('select[name="database[default]"]').val() == 'mysql')
		{
			$("#advanced-settings").show();
			
			$('.dbextras').hide();
			$('.dbextras.mysql').show();
		}
		
		$("#testDB").click(function(e){
			e.preventDefault();
			$("#dbCheck").empty();
			
			$("#dbCheck").html("<i class='fa fa-spinner fa-spin'></i>");

			var $this = $(this);
			var url = $this.data('url');
			var input = $('input[name^="database"], select[name^="database"]').serialize();
			
			doRequest(url, input, $this);
		});
		
		$("select[name='database[default]']").change(function(){
			var driver = $(this).val(); 
			$('.dbextras').slideUp();
			$('.dbextras' + '.' + driver).slideDown();
		});
	});
	
	function doRequest(url, input, element)
	{
		element.find('.alert').remove();
		element.find('.spinner')
			.show()
			.find('i')
			.removeClass()
			.addClass('fa fa-spinner fa-spin');
		
		
		$.ajax({
			url: "/install/" + url,
			data: input
		}).done(function(data){

			if(data.status)
			{
				element.find('.spinner i')
					.removeClass('fa-spinner')
					.removeClass('fa-spin')
					.addClass('fa-check-circle text-success');
			} else
			{
				element.find('.spinner i')
					.removeClass('fa-spinner')
					.removeClass('fa-spin')
					.show()
					.addClass('fa-times-circle text-danger');
					
				if(data.message)
				{
					element.append('<div class="alert alert-danger">' + data.message + '</div>');
				}
				
				checks = false;
			}
		});
	}
</script>


@include('partials.footer')

</body>

</html>
