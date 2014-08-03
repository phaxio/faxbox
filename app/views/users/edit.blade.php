@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Edit User</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        {{ Form::open(array('action' => ['UserController@update', $user['id']], 'method' => 'PUT')) }}

        <div class="col-md-4">
            <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
                {{ Form::text('email', $user['email'], array('class' => 'form-control', 'placeholder' => trans('users.email'))) }}
                {{ ($errors->has('email') ? $errors->first('email') : '') }}
            </div>
            <div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
                {{ Form::text('first_name', $user['first_name'], array('class' => 'form-control', 'placeholder' => trans('users.first_name'))) }}
                {{ ($errors->has('first_name') ? $errors->first('first_name') : '') }}
            </div>
            <div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
                {{ Form::text('last_name', $user['last_name'], array('class' => 'form-control', 'placeholder' => trans('users.last_name'))) }}
                {{ ($errors->has('last_name') ? $errors->first('last_name') : '') }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <h4>User Level Permissions <small>Any permission set here will be specific to this user.</small></h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>General Permissions</label>

                            @foreach($permissions['static'] as $p)
                            <div class="checkbox">
                                <label>
                                    {{ Form::select("permissions[".$p['id']."]", [0 => 'Inherit', 1 => 'Allow', -1 => 'Deny'], $p['value']) }} {{ $p['name'] }} <span style="font-style: italic; color: #b7b7b7; font-weight: 200">{{ $p['description'] }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @foreach($permissions['dynamic'] as $resource)
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>{{ $resource['name'] }} Permissions</label>
                            @foreach($resource['permissions'] as $p)
                            <div class="checkbox">
                                <label>
                                    {{ Form::select("permissions[".$p['id']."]", [0 => 'Inherit', 1 => 'Allow', -1 => 'Deny'], $p['value']) }} {{ $p['name'] }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
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
                        {{ Form::checkbox("groups[".$group['id']."]", 1, $group['value']) }} {{ $group['name'] }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <h4>Email Notification</h4>
            
            <div class="form-group">
                <h5>Sent Faxes</h5>
                <div class="checkbox">
                    <label>
                        {{ Form::select('sent_notification', ['never' => 'Never notify me', 'failed' => 'Only on failed attempts', 'always' => 'Always notify me of successful and failed attempts'], $user['sent_notification']) }}
                    </label>
                </div>
            </div>

            <div class="form-group">
                <h5>Received Faxes</h5>
                <div class="checkbox">
                    <label>
                        {{ Form::select('received_notification', ['never' => 'Never notify me', 'groups' => 'Only for groups I\'m a part of', 'mine' => 'Only for numbers I manage', 'always' => 'Always notify me of incoming faxes'], $user['received_notification']) }}
                    </label>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <h4>Change Password</h4>
            </div>
            <div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
                {{ Form::password('oldpassword', null, array('class' => 'form-control', 'placeholder' => trans('users.oldpassword'))) }}
                {{ ($errors->has('last_name') ? $errors->first('last_name') : '') }}
            </div>
            <div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
                {{ Form::password('password', null, array('class' => 'form-control', 'placeholder' => trans('users.newpassword'))) }}
                {{ ($errors->has('last_name') ? $errors->first('last_name') : '') }}
            </div>
            <div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
                {{ Form::password('password_confirmed', null, array('class' => 'form-control', 'placeholder' => trans('users.newpasswordconfirm'))) }}
                {{ ($errors->has('last_name') ? $errors->first('last_name') : '') }}
            </div>
        </div>
    </div>

    {{ Form::hidden('id', $user['id']) }}
    {{ Form::submit(trans('users.update'), array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
</div>
@stop