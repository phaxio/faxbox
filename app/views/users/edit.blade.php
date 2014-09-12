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
            	<label for="email" class="control-label">Email</label>
                {{ Form::text('email', $user['email'], array('class' => 'form-control', 'placeholder' => trans('users.email'))) }}
                {{ ($errors->has('email') ? $errors->first('email') : '') }}
            </div>
            <div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
            	<label for="first_name" class="control-label">First Name</label>
                {{ Form::text('first_name', $user['first_name'], array('class' => 'form-control', 'placeholder' => trans('users.firstname'))) }}
                {{ ($errors->has('first_name') ? $errors->first('first_name') : '') }}
            </div>
            <div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
            	<label for="last_name" class="control-label">Last Name</label>
                {{ Form::text('last_name', $user['last_name'], array('class' => 'form-control', 'placeholder' => trans('users.lastname'))) }}
                {{ ($errors->has('last_name') ? $errors->first('last_name') : '') }}
            </div>
        </div>
    </div>

	@if(Sentry::getUser()->isSuperUser())
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
                                    {{ Form::select("permissions[".$p['id']."]", [0 => '', 1 => 'Allow', -1 => 'Deny'], $p['value']) }} {{ $p['name'] }} <span style="font-style: italic; color: #b7b7b7; font-weight: 200">{{ $p['description'] }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($groups)
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
    @endif
    @endif

    <div class="row">
        <div class="col-md-4">
            <h4>Email Notification</h4>
            
            <div class="form-group">
                <h5>Sent Faxes</h5>
                <div class="checkbox">
                    <label>
                        {{ Form::select('sent_notification', ['never' => 'Never', 'always' => 'Always', 'failed' => 'Only on failed faxes'], $user['sent_notification']) }}
                    </label>
                </div>
            </div>

            <div class="form-group">
                <h5>Received Faxes</h5>
                <div class="checkbox">
                    <label>
                        {{ Form::select('received_notification', ['never' => 'Never', 'always' => 'Always', 'mine' => 'Only for numbers I own'], $user['received_notification']) }}
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
            <div class="form-group {{ ($errors->has('old_password')) ? 'has-error' : '' }}">
            	<label for="old_password" class="control-label">Current Password</label>
                {{ Form::password('old_password', array('class' => 'form-control', 'placeholder' => trans('users.currentpassword'))) }}
                {{ ($errors->has('old_password') ? $errors->first('old_password') : '') }}
            </div>
            <div class="form-group {{ ($errors->has('password')) ? 'has-error' : '' }}">
            	<label for="password" class="control-label">New Password</label>
                {{ Form::password('password', array('class' => 'form-control', 'placeholder' => trans('users.newpassword'))) }}
                {{ ($errors->has('password') ? $errors->first('password') : '') }}
            </div>
            <div class="form-group {{ ($errors->has('password_confirmation')) ? 'has-error' : '' }}">
            	<label for="password_confirmation" class="control-label">New Password Confirm</label>
                {{ Form::password('password_confirmation', array('class' => 'form-control', 'placeholder' => trans('users.newpasswordconfirm'))) }}
                {{ ($errors->has('password_confirmation') ? $errors->first('password_confirmation') : '') }}
            </div>
        </div>
    </div>

    {{ Form::hidden('id', $user['id']) }}
    {{ Form::submit(trans('general.update'), array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
    
    @if(!count($user['faxes']) && !Sentry::findUserById($user['id'])->isSuperUser())
	
	<button class="btn btn-danger pull-right" data-toggle="modal" data-target="#deleteModal">{{ trans('general.delete') }}</button>
	
	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title">Modal title</h4>
          </div>
          <div class="modal-body">
            <p>One fine body&hellip;</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            {{ Form::open(['action' => ['UserController@destroy', $user['id']], 'method' => 'delete', 'class' => 'pull-left']) }}
			{{ Form::submit(trans('users.delete'), array('class' => 'btn btn-danger')) }}
			{{ Form::close() }}
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    
	@endif
</div>
@stop

@section('scripts')
<script>
$(function(){
//	$('#deleteModal').modal()
})
</script>
@stop