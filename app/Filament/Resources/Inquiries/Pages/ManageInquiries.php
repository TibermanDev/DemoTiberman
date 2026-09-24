<?php

namespace App\Filament\Resources\Inquiries\Pages;

use App\Filament\Resources\Inquiries\InquiryResource;
use Filament\Resources\Pages\ManageRecords;

/** Hanya-baca: data masuk dari form Contact, tidak dibuat manual dari CMS. */
class ManageInquiries extends ManageRecords
{
    protected static string $resource = InquiryResource::class;
}
