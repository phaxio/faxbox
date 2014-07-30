@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Create User</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        {{ Form::open(array('action' => 'UserController@store')) }}

        <div class="col-md-4">
            <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
                {{ Form::text('email', null, array('class' => 'form-control', 'placeholder' => trans('users.email'))) }}
                {{ ($errors->has('email') ? $errors->first('email') : '') }}
            </div>
            <div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
                {{ Form::text('first_name', null, array('class' => 'form-control', 'placeholder' => trans('users.first_name'))) }}
                {{ ($errors->has('first_name') ? $errors->first('first_name') : '') }}
            </div>
            <div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
                {{ Form::text('last_name', null, array('class' => 'form-control', 'placeholder' => trans('users.last_name'))) }}
                {{ ($errors->has('last_name') ? $errors->first('last_name') : '') }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <h4>User Level Permissions <small>Any permission set here will be specific to this user.</small></h4>
                @include("partials.permissions")
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <h4>Group Level Permissions <small>Permissions granted from groups are inherited by the user.</small></h4>
                @foreach($groups as $group)
                <div class="checkbox">
                    <label>
                        {{ Form::checkbox("groups[".$group['id']."]", 1) }} {{ $group['name'] }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{ Form::hidden('password', $password) }}
    {{ Form::hidden('password_confirmation', $password) }}

    {{ Form::submit(trans('users.create'), array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
</div>
@stop