<?php
    
    $service = app()->make(\App\Services\LocalizationService::class);
    return  $service->langVariables(29);
