@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">View Phone Numbers 
            <a href="{{ action('PhoneController@create') }}" class="btn btn-primary">{{ trans('phones.create') }}</a>
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
                        <th></th>
                        <th>Number</th>
                        <th>Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($phones as $phone)
                    <tr class="odd gradeX">
                        <td class="text-center"><a href="{{ action('PhoneController@edit', ['id' => $phone['id']]) }}"><i class="fa fa-edit"> Edit</i></a></td>
                        <td>{{ $phone['number'] }}</td>
                        <td>{{ $phone['description'] }}</td>
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

@section('scripts')
<script src="{{ asset('js/plugins/dataTables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('js/plugins/dataTables/dataTables.bootstrap.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#dataTables-fax').dataTable({
            "order": [[ 1, "desc" ]],
            "columnDefs": [
                { "orderable": false, "targets": [0,2] },
                { "searchable": false, "targets": [0,2] }
            ]
        });
    });
</script>
@stop