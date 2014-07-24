@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{trans('pages.login')}}</h3>
                </div>
                <div class="panel-body">
                    {{ Form::open(array('action' => 'SessionController@store')) }}
                        <fieldset>
                            <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
                                {{ Form::text('email', null, array('class' => 'form-control', 'placeholder' => trans('users.email'), 'autofocus')) }}
                                {{ ($errors->has('email') ? $errors->first('email') : '') }}
                            </div>

                            <div class="form-group {{ ($errors->has('password')) ? 'has-error' : '' }}">
                                {{ Form::password('password', array('class' => 'form-control', 'placeholder' => trans('users.pword')))}}
                                {{ ($errors->has('password') ?  $errors->first('password') : '') }}
                            </div>
                            <div class="checkbox">
                                <label class="checkbox">
                                    {{ Form::checkbox('rememberMe', 'rememberMe') }} {{trans('users.remember')}}?
                                </label>
                            </div>
                            {{ Form::submit(trans('pages.login'), array('class' => 'btn btn-primary'))}}
                            <a class="btn btn-link" href="#">{{trans('users.forgot')}}?</a>
                        </fieldset>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@stop