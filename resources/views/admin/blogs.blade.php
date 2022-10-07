@extends( adminTheme().'.layout.admin' , [
    'header' => 'Currencies',
    'header_desc' => '',
    'breadcrumb' => []
])

@section('top')

    <button class="btn btn-sm btn-primary open-modal" data-url="{{ route('admin_blogs.create') }}" type="button">
        <i class="fas fa-plus"></i>
    </button>

@endsection

@section('content')

    <div class="card shadow mb-4" id="admin_currencies">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ _('Blogs') }}</h6>
        </div>
        <div class="card-body">

            <div class="table_container">


            </div>
        </div>
        <div class="card-body">

            <table class="table datatable-generated table-hover no-footer dataTable"
                   data-url="{{ ifExistRoute('admin.blogs.ajax') }}"
                   data-orderable="4,5"
                   data-success="adminBlogs"
                   data-placeholder="ID, Headline"
                   data-data="{{ base64data([
                        'update_url' => route('admin_blogs.show', '###')
                   ]) }}">
                <thead>
                <tr>
                    <th>{{ _('ID') }}</th>
                    <th>{{ _('Img') }}</th>
                    <th>{{ _('Headline') }}</th>
                    <th>{{ _('Lang') }}</th>
                    <th>{{ _('Active') }}</th>
                    <th>{{ _('Update') }}</th>
                    <th>{{ _('Delete') }}</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>


@endsection
