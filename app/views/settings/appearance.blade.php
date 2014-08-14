@section('head')
<link rel="stylesheet" href="{{ asset('colpick/css/colpick.css') }}"/>
<style>
#picker {
	margin:0;
	padding:0;
	border:0;
	width:70px;
	height:20px;
	border-right:20px solid green;
	line-height:20px;
}
</style>
@stop

@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Appearance</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    
    <div class="row">
    {{ Form::open(array('action' => 'SettingController@updateAppearance', 'enctype' => 'multipart/form-data')) }}
		<div class="col-md-4">
			<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
				{{ Form::text('name', $settings['name'], ['class' => 'form-control', 'placeholder' => 'Site Name']) }}
				{{ ($errors->has('name') ? $errors->first('name') : '') }}
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-4">
			<div class="form-group {{ ($errors->has('logo')) ? 'has-error' : '' }}">
				@if($settings['logo'])
				<img style="max-width:200px;" src="{{ asset('images/'.$settings['logo']) }}"><br>
				@endif
				<label>Logo</label>
				{{ Form::file('logo', ['accept' => 'image/jpeg,image/png']) }}
				{{ ($errors->has('logo') ? $errors->first('logo') : '') }}
			</div>
		</div>
	</div>	
	
    <div class="row">
		<div class="col-md-2">
			<div class="form-group {{ ($errors->has('color1')) ? 'has-error' : '' }}">
				{{ Form::text('color1', $settings['color1'], ['class' => 'form-control', 'placeholder' => 'Color', 'id' => 'color1']) }}
				{{ ($errors->has('color1') ? $errors->first('color1') : '') }}
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group {{ ($errors->has('color2')) ? 'has-error' : '' }}">
				{{ Form::text('color2', $settings['color2'], ['class' => 'form-control', 'placeholder' => 'Color', 'id' => 'color2']) }}
				{{ ($errors->has('color2') ? $errors->first('color2') : '') }}
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2">
			<div class="form-group {{ ($errors->has('color3')) ? 'has-error' : '' }}">
				{{ Form::text('color3', $settings['color3'], ['class' => 'form-control', 'placeholder' => 'Color', 'id' => 'color3']) }}
				{{ ($errors->has('color3') ? $errors->first('color3') : '') }}
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group {{ ($errors->has('color4')) ? 'has-error' : '' }}">
				{{ Form::text('color4', $settings['color4'], ['class' => 'form-control', 'placeholder' => 'Color', 'id' => 'color4']) }}
				{{ ($errors->has('color4') ? $errors->first('color4') : '') }}
			</div>
		</div>
	</div>
		

    {{ Form::submit(trans('setting.updateAppearance'), array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
</div>
@stop

@section('scripts')
<script type="text/javascript" src="{{ asset('colpick/js/colpick.js') }}"></script>

<script>
$(document).ready(function(){
	var options = {
		layout:'hex',
		submit:0,
		colorScheme:'dark',
		onChange:function(hsb,hex,rgb,el,bySetColor) {
			$(el).css('border-color','#'+hex);
			// Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
			if(!bySetColor) $(el).val(hex);
		}
	}
    $('#color1').colpick(options).keyup(function(){
    	$(this).colpickSetColor(this.value);
    });
    
    $('#color2').colpick(options).keyup(function(){
		$(this).colpickSetColor(this.value);
	});
        
	$('#color3').colpick(options).keyup(function(){
		$(this).colpickSetColor(this.value);
	});
            
	$('#color4').colpick(options).keyup(function(){
		$(this).colpickSetColor(this.value);
	});
});
</script>
@stop