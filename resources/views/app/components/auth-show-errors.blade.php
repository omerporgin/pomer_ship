<!-- Validation Errors -->
@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <div class="font-medium text-red-600">
            <i class="fas fa-times"></i> <b>{{ __('Whoops! Something went wrong.') }}</b>
        </div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
