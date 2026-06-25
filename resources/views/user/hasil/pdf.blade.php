<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Diagnosa — {{ $hasil->pasien->nama }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            color: #212529;
            background: white;
        }

        .header {
            background: #2E86AB;
            color: white;
            padding: 20px 30px;
            margin-bottom: 24px;
        }

        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 9pt;
            opacity: 0.85;
        }

        .container { padding: 0 30px 30px; }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #2E86AB;
            border-bottom: 2px solid #2E86AB;
            padding-bottom: 4px;
            margin-bottom: 12px;
        }

        .hasil-box {
            background: #f0f8ff;
            border: 2px solid #2E86AB;
            border-radius: 8px;
            padding: 16px 20px;
            text-align: center;
            margin-bottom: 20px;
        }

        .hasil-box h2 {
            font-size: 18pt;
            color: #2E86AB;
            margin-bottom: 6px;
        }

        .hasil-box .cf-nilai {
            font-size: 28pt;
            font-weight: bold;
            color: #57CC99;
        }

        .hasil-box .cf-label {
            font-size: 10pt;
            color: #6c757d;
        }

        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .info-grid td {
            padding: 6px 8px;
            font-size: 10pt;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-grid td:first-child {
            color: #6c757d;
            width: 35%;
            font-weight: bold;
        }

        .gejala-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
        }

        .gejala-table th {
            background: #2E86AB;
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-size: 9pt;
        }

        .gejala-table td {
            padding: 7px 10px;
            border-bottom: 1px solid #f0f0f0;
        }

        .gejala-table tr:nth-child(even) td {
            background: #f8f9fa;
        }

        .progress-bar-wrap {
            background: #e9ecef;
            border-radius: 4px;
            height: 10px;
            width: 100%;
            margin-top: 4px;
        }

        .progress-bar-fill {
            background: #57CC99;
            border-radius: 4px;
            height: 10px;
        }

        .disclaimer {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 9pt;
            color: #856404;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            font-size: 8pt;
            color: #adb5bd;
            margin-top: 24px;
            padding-top: 12px;
            border-top: 1px solid #e9ecef;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <h1>&#128065; Sistem Pakar Diagnosis Penyakit Mata</h1>
        <p>Menggunakan Metode Certainty Factor &amp; Forward Chaining</p>
    </div>

    <div class="container">

        {{-- Hasil Utama --}}
        <div class="hasil-box">
            <p style="font-size:9pt;color:#6c757d;margin-bottom:4px;">Hasil Diagnosis</p>
            <h2>{{ $hasil->penyakit->nama }}</h2>
            <div class="cf-nilai">{{ $hasil->cf_persen }}%</div>
            <div class="cf-label">
                Tingkat Keyakinan: <strong>{{ $label }}</strong>
                &nbsp;|&nbsp; CF = {{ number_format($hasil->cf_hasil, 4) }}
            </div>
            <div class="progress-bar-wrap" style="max-width:300px;margin:10px auto 0;">
                <div class="progress-bar-fill"
                     style="width:{{ $hasil->cf_persen }}%;"></div>
            </div>
        </div>

        {{-- Informasi Pasien --}}
        <div class="section">
            <div class="section-title">Informasi Pasien</div>
            <table class="info-grid">
                <tr>
                    <td>Nama</td>
                    <td>{{ $hasil->pasien->nama }}</td>
                </tr>
                <tr>
                    <td>Nomor HP</td>
                    <td>{{ $hasil->pasien->no_hp }}</td>
                </tr>
                <tr>
                    <td>Tanggal Diagnosa</td>
                    <td>{{ $hasil->created_at->format('d F Y, H:i') }} WIB</td>
                </tr>
                <tr>
                    <td>ID Diagnosa</td>
                    <td>#{{ str_pad($hasil->id, 6, '0', STR_PAD_LEFT) }}</td>
                </tr>
            </table>
        </div>

        {{-- Deskripsi Penyakit --}}
        <div class="section">
            <div class="section-title">Tentang {{ $hasil->penyakit->nama }}</div>
            <p style="line-height:1.6;font-size:10pt;color:#495057;">
                {{ $hasil->penyakit->deskripsi }}
            </p>
        </div>

        {{-- Solusi --}}
        <div class="section">
            <div class="section-title">Rekomendasi Penanganan</div>
            <p style="line-height:1.6;font-size:10pt;color:#495057;">
                {{ $hasil->penyakit->solusi }}
            </p>
        </div>

        {{-- Gejala --}}
        @if($hasil->detailDiagnosa->count() > 0)
        <div class="section">
            <div class="section-title">
                Gejala yang Teridentifikasi ({{ $hasil->detailDiagnosa->count() }})
            </div>
            <table class="gejala-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Gejala</th>
                        <th>Frekuensi</th>
                        <th>CF User</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hasil->detailDiagnosa as $i => $detail)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $detail->gejala->kode }}</td>
                        <td>{{ $detail->gejala->nama }}</td>
                        <td>
                            {{ match($detail->frekuensi) {
                                'sangat_sering' => 'Sangat Sering',
                                'sering'        => 'Sering',
                                'jarang'        => 'Jarang',
                                default         => 'Tidak Pernah',
                            } }}
                        </td>
                        <td>{{ $detail->cf_user }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- Disclaimer --}}
        <div class="disclaimer">
            <strong>&#9888; Perhatian:</strong> Hasil diagnosa ini merupakan perkiraan awal berbasis
            sistem pakar dan bukan pengganti diagnosis dokter. Segera konsultasikan dengan dokter
            spesialis mata untuk penanganan lebih lanjut.
        </div>

        {{-- Footer --}}
        <div class="footer">
            Dicetak oleh Sistem Pakar Diagnosis Penyakit Mata &nbsp;|&nbsp;
            {{ now()->format('d F Y, H:i') }} WIB &nbsp;|&nbsp;
            Metode: Certainty Factor &amp; Forward Chaining
        </div>

    </div>

</body>
</html>