@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Edit Phone Number</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        {{ Form::open(array('action' => ['PhoneController@update', $phone['id']], 'method' => 'PUT')) }}

        <div class="col-md-4">
            <h4>{{ $phone['number'] }}</h4>
            <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
                {{ Form::text('description', $phone['description'], array('class' => 'form-control', 'placeholder' => trans('phone.description'))) }}
                {{ ($errors->has('description') ? $errors->first('description') : '') }}
            </div>
        </div>
    </div>

    @if($groups)
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <h4>Group Permissions <small>These groups will have access to view faxes from this number</small></h4>
                @foreach($groups as $group)
                <div class="checkbox">
                    <label>
                        {{ Form::hidden("groups[".$group['id']."]", 0) }}
                        {{ Form::checkbox("groups[".$group['id']."]", 1, $group['value']) }} {{ $group['name'] }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    {{ Form::submit(trans('phone.update'), array('class' => 'btn btn-primary')) }}
    @endif
</div>
@stop