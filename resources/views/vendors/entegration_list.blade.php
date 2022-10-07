@extends( vendorTheme().'.layout.admin' , [
    'header' => 'Entegrasyon Listesi',
    'header_desc' => '',
    'breadcrumb' => []
])

@section('top')

@endsection

@section('content')

    <div class="card shadow mb-4" id="entegration_list">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{ _('Entegration List') }}</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                     aria-labelledby="dropdownMenuLink" style="">
                    <div class="dropdown-header">Dropdown Header:</div>
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </div>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th>{{ _('Img') }}</th>
                    <th>{{ _('Name') }}</th>
                    <th>{{ _('Description') }}</th>
                    <th>{{ _('Action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach( $list['list'] as $item)

                    <tr>
                        <th class="img_th">
                            <img src="{{ asset('img/entegrations/'.$item->id.".png") }}" class="img-fluid">
                        </th>

                        <th>{{$item->name}}</th>
                        <th></th>
                        <th class="text-center">
                            <button class="d-none btn btn-primary open-modal" data-target="entegrationData"  data-template="{{ vendorTheme() }}" data-id="" data-data="{{ json_encode(['id'=>$item->id]) }}">Ekle</button>
                            <button class="btn btn-primary open-modal" data-url="{{ route('vendor_entegration_data.create') }}" data-data="{{ json_encode(['id'=>$item->id]) }}">Ekle</button>
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

