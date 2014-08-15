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
								<h4>Site Name</h4>
								<p>This name is used in the header. Usually your business name.</p>
								<fieldset>
									<div class="form-group">
									  <label class="sr-only" for="name">{{ trans('install.name') }}</label>
									  {{ Form::text('name', null, array('class' => 'form-control', 'placeholder' => trans('install.name'))) }}
									</div>
								</fieldset>
								<hr>
							</div>
                        </div>
                        
                        <div class="row">
							<div class="col-sm-12">
								<h4>Phaxio API Keys</h4>
								<p>Your Phaxio api keys can be found in your <a href="http://www.phaxio.com/apiSettings" target="_blank">Phaxio account page</a>.</p>
								<fieldset>
									<div class="form-group">
									  <label class="sr-only" for="services[phaxio][public]">{{ trans('install.apiPublic') }}</label>
									  {{ Form::text('services[phaxio][public]', null, array('class' => 'form-control', 'placeholder' => trans('install.apiPublic'))) }}
									</div>
									<div class="form-group">
									  <label class="sr-only" for="services[phaxio][secret]">{{ trans('install.apiSecret') }}</label>
									  {{ Form::text('services[phaxio][secret]', null, array('class' => 'form-control', 'placeholder' => trans('install.apiSecret'))) }}
									</div>
								</fieldset>
								<hr>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-12">
								<h4>Create Admin</h4>
								<p>This user will have access to everything.</p>
								<fieldset>
									<div class="form-group">
									  <label class="sr-only" for="first_name">{{ trans('user.firstname') }}</label>
									  {{ Form::text('admin[first_name]', null, array('class' => 'form-control', 'placeholder' => trans('user.firstname'))) }}
									</div>
									<div class="form-group">
									  <label class="sr-only" for="last_name">{{ trans('user.lastname') }}</label>
									  {{ Form::text('admin[last_name]', null, array('class' => 'form-control', 'placeholder' => trans('user.lastname'))) }}
									</div>
									<div class="form-group">
									  <label class="sr-only" for="email">{{ trans('user.email') }}</label>
									  {{ Form::text('admin[email]', null, array('class' => 'form-control', 'placeholder' => trans('user.email'))) }}
									</div>
									<div class="form-group">
									  <label class="sr-only" for="password">{{ trans('user.password') }}</label>
									  {{ Form::password('admin[password]', array('class' => 'form-control', 'placeholder' => trans('user.password'))) }}
									  {{ Form::password('admin[password_confirmation]', array('class' => 'form-control', 'placeholder' => trans('user.passwordConfirmed'))) }}
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
								    	<label class="sr-only" for="database[driver]">{{ trans('install.dbdriver') }}</label>
										{{ Form::select('database[default]', ['sqlite' => 'SQLite', 'mysql' => 'MySQL'], 'sqlite', array('class' => 'form-control', 'placeholder' => trans('install.dbdriver'))) }}
									</div>
									
									<div class="form-group">
										<label class="sr-only" for="database[database]">{{ trans('install.dbname') }}</label>
										{{ Form::text('database[database]', app_path('database/production.sqlite'), array('class' => 'form-control', 'placeholder' => trans('install.dbname'))) }}
									</div>
									
									<div class="dbextras mysql" style="display:none">
										<div class="form-group">
											<label class="sr-only" for="database[host]">{{ trans('install.dbhost') }}</label>
											{{ Form::text('database[host]', null, array('class' => 'form-control', 'placeholder' => trans('install.dbhost'))) }}
										</div>
										
										<div class="form-group">
											<label class="sr-only" for="database[username]">{{ trans('install.dbusername') }}</label>
											{{ Form::text('database[username]', null, array('class' => 'form-control', 'placeholder' => trans('install.dbusername'))) }}
										</div>
										
										<div class="form-group">
											<label class="sr-only" for="database[password]">{{ trans('install.dbpassword') }}</label>
											{{ Form::text('database[password]', null, array('class' => 'form-control', 'placeholder' => trans('install.dbpassword'))) }}
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
