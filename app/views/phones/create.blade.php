@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Create Phone Number</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        {{ Form::open(array('action' => 'PhoneController@store')) }}

        <div class="col-md-4">
            <div class="form-group {{ ($errors->has('area')) ? 'has-error' : '' }}">
            	<label for="area" class="control-label">Area Code</label>
                {{ Form::select('area', $area, array('class' => 'form-control', 'placeholder' => trans('phones.area'))) }}
                {{ ($errors->has('area') ? $errors->first('area') : '') }}
            </div>
            <div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
            	<label for="description" class="control-label">Description</label>
                {{ Form::text('description', null, array('class' => 'form-control', 'placeholder' => trans('phones.description'))) }}
                {{ ($errors->has('description') ? $errors->first('description') : '') }}
            </div>
        </div>
    </div>

    @if(count($groups))
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <h4>Group Permissions <small>These groups will have access to view faxes from this number</small></h4>
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

    {{ Form::submit(trans('phones.create'), array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
</div>
@stop