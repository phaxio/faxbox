<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Faxbox</title>

    <!-- Custom CSS -->
	<link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="{{ asset('css/plugins/metisMenu/metisMenu.min.css') }}" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="{{ asset('css/plugins/timeline.css') }}" rel="stylesheet">

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
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{trans('users.forgot')}}</h3>
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('action' => 'UserController@forgot')) }}
                        <fieldset>
                            <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
                            	<label for="email" class="control-label">Email</label>
                                {{ Form::text('email', null, array('class' => 'form-control', 'placeholder' => trans('users.email'), 'autofocus')) }}
                                {{ ($errors->has('email') ? $errors->first('email') : '') }}
                            </div>
                            {{ Form::submit(trans('users.forgotSubmit'), array('class' => 'btn btn-primary'))}}
                        </fieldset>
                        <a href="{{ action('SessionController@create') }}" class="pull-right">Back to Login</a>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@include('partials.footer')

</body>

</html>
