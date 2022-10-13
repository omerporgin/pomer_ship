<button type="button" class="btn btn-primary create_documents" data-order_id="{{ $item->id }}"
        data-url="{{ ifExistRoute('vendor.create_document') }}">
    create_documents
</button>

@if( $item->document_etgb != '')
    <embed src="{{ $item->document_etgb }}?date={{ now() }}" class="order_document"/>
@else
    <div>Etgb Dökümanı hazır değil</div>
@endif

@if( $item->document_custom != '')
    <embed src="{{ $item->document_custom }}?date={{ now() }}" class="order_document"/>
@else
    <div>Gümrük Dolaylı Temsil Yetkisi hazır değil</div>
@endif

@if( $item->document_invoice != '')
    <embed src="{{ $item->document_invoice }}?date={{ now() }}" class="order_document"/>
@else
    <div>İngilizce fatura hazır değil</div>
@endif
