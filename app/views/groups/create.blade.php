@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Create Group</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        {{ Form::open(array('action' => 'GroupController@store')) }}

        <div class="col-md-4 ">
            <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
            	<label for="name" class="control-label">Name</label>
                {{ Form::text('name', null, array('class' => 'form-control', 'placeholder' => trans('groups.name'))) }}
                {{ ($errors->has('name') ? $errors->first('name') : '') }}
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>General Permissions</label>

                    @foreach($permissions['static'] as $p)
                    <div class="checkbox">
                        <label>
                            {{ Form::select("permissions[".$p['id']."]", [1 => 'Allow', 0 => 'Deny'], $p['value']) }} {{ $p['name'] }} <span style="font-style: italic; color: #b7b7b7; font-weight: 200">{{ $p['description'] }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            {{ Form::submit(trans('groups.create'), array('class' => 'btn btn-primary')) }}
        </div>
    </div>

    {{ Form::close() }}
</div>
@stop