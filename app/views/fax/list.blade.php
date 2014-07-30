@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">View Faxes</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table
                    class="table table-striped table-bordered table-hover"
                    id="dataTables-example">
                    <thead>
                    <tr>
                        <th>Direction</th>
                        <th>Recipient</th>
                        <th>Pages</th>
                        <th>Incoming Number</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($faxes as $fax)
                    <tr class="odd gradeX">
                        <td>{{ $fax['direction'] == 'received' ? '<i class="fa fa-arrow-circle-o-down"></i>' : '<i class="fa fa-arrow-circle-o-up"></i>'}} {{ $fax['direction'] }}</td>
                        <td>{{ $fax['recipient']['number'] ?: '-----' }}</td>
                        <td>{{ $fax['pages'] }}</td>
                        <td>{{ $fax['phone']['number'] ?: '-----'  }}</td>
                        <td>{{ $fax['in_progress'] ?  '<i class="fa fa-spinner fa-spin"></i> In Progress' : 'Completed' }}</td>
                        <td>{{ $fax['created_at'] }}</td>
                        <td><a href="#">View</a></td>
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