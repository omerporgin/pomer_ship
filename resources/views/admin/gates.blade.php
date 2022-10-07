@extends( adminTheme().'.layout.admin')

@section('top')

@endsection

@section('content')

    <div class="card shadow mb-4"  id="admin_gtip">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ _('Gates') }}</h6>
        </div>
        <div class="card-body">
            <div class="table_container">
                <table class="table datatable-generated table-hover no-footer dataTable"
                    <thead>
                    <tr>
                        <th>{{ _('ID') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($gateList as $gateName=>$gate)
                        <tr>
                            <td>{{ $gateName }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
