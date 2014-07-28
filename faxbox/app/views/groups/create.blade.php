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
                {{ Form::text('name', null, array('class' => 'form-control', 'placeholder' => trans('groups.name'))) }}
                {{ ($errors->has('name') ? $errors->first('name') : '') }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3>General Permissions</h3>
            <div class="form-group">
                @foreach($permissions['static'] as $p)
                <label class="checkbox">
                    {{ Form::checkbox("permissions[".$p['id']."]", 1) }} {{ $p['name'] }} <span style="font-style: italic; color: #b7b7b7; font-weight: 200">{{ $p['description'] }}</span>
                </label>
                @endforeach
            </div>
        </div>
    </div>

    @foreach($permissions['dynamic'] as $resource)
    <div class="row">
        <div class="col-md-12">
            <h3>{{ $resource['name'] }} Permissions</h3>
            <div class="form-group">
                @foreach($resource['permissions']['classLevel'] as $p)
                <label class="checkbox">
                    {{ Form::checkbox("permissions[".$p['id']."]", 1) }} {{ $p['name'] }} <span style="font-style: italic; color: #b7b7b7; font-weight: 200">{{ $p['description'] }}</span>
                </label>
                @endforeach
            </div>

            <div class="form-group">
                @foreach($resource['permissions']['itemLevel'] as $p)
                <label class="checkbox">
                    {{ Form::checkbox("permissions[".$p['id']."]", 1) }} {{ $p['name'] }} <span style="font-style: italic; color: #b7b7b7; font-weight: 200">{{ $p['description'] }}</span>
                </label>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach

    {{ Form::submit(trans('groups.create'), array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
</div>
@stop