<?php

namespace App\Exports;

use App\Models\Card;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnFormatting,
    ShouldAutoSize
};

class CardsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnFormatting, ShouldAutoSize
{
    public function __construct(private array $ids) {}

    public function collection()
    {
        return Card::whereIn('id', $this->ids)
            ->select('cardholder_name', 'org_name', 'card_number', 'expiry_date', 'csc', 'credit_limit', 'card_type')
            ->get();
    }

    public function map($card): array
    {
        return [
            $card->cardholder_name,
            $card->org_name,
            " " . $card->card_number,
            Carbon::parse($card->expiry_date)->format('d M Y'),
            (string) $card->csc,
            $card->credit_limit,
            $card->card_type,
        ];
    }

    public function headings(): array
    {
        return [
            'Cardholder Name',
            'Organisation',
            'Card Number',
            'Expiry Date',
            'CVV',
            'Credit Limit',
            'Card Type',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT
        ];
    }
}
