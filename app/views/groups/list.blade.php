@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">View Groups
            <a href="{{ action('GroupController@create') }}" class="btn btn-primary">{{ trans('groups.create')  }}</a>
            </h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="dataTables-fax">
					<thead>
					<tr>
						<th class="col-sm-2"></th>
						<th>Name</th>
					</tr>
					</thead>
					<tbody>
					@foreach($groups as $group)
					<tr class="odd gradeX">
						<td class="text-center"><a href="{{ action('GroupController@edit', ['id' => $group['id']]) }}"><i class="fa fa-edit"> Edit</i></a></td>
						<td>{{ $group['name'] }}</td>
					</tr>
					@endforeach
					</tbody>
				</table>
			</div>
			<!-- /.table-responsive -->
		</div>
		<!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
@stop