@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Edit Group</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

	{{ Form::open(['action' => ['GroupController@update', $group['id']], 'method' => 'PUT']) }}
	<div class="row">
		<div class="col-md-4 ">
			<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
				<label for="name" class="control-label">Name</label>
				{{ Form::text('name', $group['name'], array('class' => 'form-control', 'placeholder' => trans('groups.name'))) }}
				{{ ($errors->has('name') ? $errors->first('name') : '') }}
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>General Permissions</label>
	
						@foreach($group['permissions']['static'] as $p)
						<div class="checkbox">
							<label>
								{{ Form::select("permissions[".$p['id']."]", [1 => 'Allow', 0 => 'Deny'], $p['value']) }} {{ $p['name'] }} <span style="font-style: italic; color: #b7b7b7; font-weight: 200">{{ $p['description'] }}</span>
							</label>
						</div>
						@endforeach
					</div>
				</div>
			</div>
	
			@foreach($group['permissions']['dynamic'] as $resource)
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>{{ $resource['name'] }} Permissions</label>
						@foreach($resource['permissions'] as $p)
						<div class="checkbox">
							<label>
								{{ Form::select("permissions[".$p['id']."]", [1 => 'Allow', 0 => 'Deny'], $p['value']) }} {{ $p['name'] }}
							</label>
						</div>
						@endforeach
					</div>
				</div>
			</div>
			@endforeach
		</div>
		<div class="col-md-6">
			<label>Users</label>
			<div class="form-group">
				@foreach($users as $user)
				<div class="checkbox">
					<label>
						{{ Form::hidden("users[".$user['id']."]", 0) }}
						{{ Form::checkbox("users[".$user['id']."]", 1, in_array($user['id'], $group['users'])) }} {{ $user['first_name'] }} {{ $user['last_name'] }}
					</label>
				</div>
				@endforeach
			</div>
		</div>
	</div>
	{{ Form::hidden('id', $group['id'], array('class' => 'form-control', 'placeholder' => trans('groups.name'))) }}
	{{ Form::submit(trans('groups.update'), array('class' => 'btn btn-primary pull-left')) }}
	
	{{ Form::close() }}
	
	{{ Form::open(['action' => ['GroupController@destroy', $group['id']], 'method' => 'DELETE']) }}
	{{ Form::submit(trans('groups.delete'), array('class' => 'btn btn-sm btn-danger pull-right')) }}
	{{ Form::close() }}
</div>
@stop