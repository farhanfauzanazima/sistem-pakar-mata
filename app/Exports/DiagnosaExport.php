<?php

namespace App\Exports;

use App\Models\HasilDiagnosa;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DiagnosaExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    ShouldAutoSize
{
    protected ?string $tanggalMulai;
    protected ?string $tanggalAkhir;
    protected ?int    $penyakitId;

    public function __construct(
        ?string $tanggalMulai = null,
        ?string $tanggalAkhir = null,
        ?int    $penyakitId   = null
    ) {
        $this->tanggalMulai = $tanggalMulai;
        $this->tanggalAkhir = $tanggalAkhir;
        $this->penyakitId   = $penyakitId;
    }

    public function query()
    {
        $query = HasilDiagnosa::with(['pasien', 'penyakit', 'detailDiagnosa'])
            ->orderBy('created_at', 'desc');

        if ($this->tanggalMulai) {
            $query->whereDate('created_at', '>=', $this->tanggalMulai);
        }

        if ($this->tanggalAkhir) {
            $query->whereDate('created_at', '<=', $this->tanggalAkhir);
        }

        if ($this->penyakitId) {
            $query->where('penyakit_id', $this->penyakitId);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'No',
            'ID Diagnosa',
            'Nama Pasien',
            'Nomor HP',
            'Penyakit Terdiagnosis',
            'Nilai CF',
            'CF (%)',
            'Tingkat Keyakinan',
            'Jumlah Gejala',
            'Tanggal Diagnosa',
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        $persen = $row->cf_persen;
        $label  = match(true) {
            $persen >= 80 => 'Sangat Tinggi',
            $persen >= 60 => 'Tinggi',
            $persen >= 40 => 'Sedang',
            $persen >= 20 => 'Rendah',
            default       => 'Sangat Rendah',
        };

        return [
            $no,
            '#' . str_pad($row->id, 6, '0', STR_PAD_LEFT),
            $row->pasien->nama,
            $row->pasien->no_hp,
            $row->penyakit->nama,
            number_format($row->cf_hasil, 4),
            $row->cf_persen . '%',
            $label,
            $row->detailDiagnosa->count(),
            $row->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row
            1 => [
                'font' => [
                    'bold'  => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size'  => 11,
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2E86AB'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Data Diagnosa';
    }
}