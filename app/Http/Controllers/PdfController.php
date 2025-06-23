<?php

namespace App\Http\Controllers;

use App\Models\DataMahasiswa;
use App\Models\CalonMahasiswa;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    /**
     * Generate PDF for single Data Mahasiswa
     */
    public function dataMahasiswa(DataMahasiswa $dataMahasiswa)
    {
        $data = [
            'mahasiswa' => $dataMahasiswa,
            'title' => 'Data Mahasiswa - ' . $dataMahasiswa->nama,
            'generated_at' => now()->format('d/m/Y H:i:s')
        ];

        $pdf = Pdf::loadView('pdf.data-mahasiswa', $data);

        $filename = 'data-mahasiswa-' . str_replace(' ', '-', strtolower($dataMahasiswa->nama)) . '.pdf';

        return $pdf->download($filename);
    }
    /**
     * Generate bulk PDF for multiple Data Mahasiswa
     */
    public function dataMahasiswaBulk(Request $request)
    {
        $ids = explode(',', $request->get('ids', ''));
        $mahasiswas = DataMahasiswa::whereIn('id', $ids)->get();

        if ($mahasiswas->isEmpty()) {
            abort(404, 'No data found');
        }

        $data = [
            'mahasiswas' => $mahasiswas,
            'title' => 'Data Mahasiswa - Bulk Export',
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'total' => $mahasiswas->count()
        ];

        $pdf = Pdf::loadView('pdf.data-mahasiswa-bulk', $data);

        $filename = 'data-mahasiswa-bulk-' . now()->format('Y-m-d-H-i-s') . '.pdf';

        return $pdf->download($filename);
    }
    /**
     * Generate PDF for single Calon Mahasiswa
     */
    public function calonMahasiswa(CalonMahasiswa $calonMahasiswa)
    {
        $kriteria = Kriteria::orderBy('kode')->get();

        // Get dynamic values for each criteria
        $kriteriaValues = [];
        foreach ($kriteria as $k) {
            $fieldName = strtolower($k->kode);
            $value = $calonMahasiswa->$fieldName ?? null;
            $kriteriaValues[] = [
                'kriteria' => $k,
                'raw_value' => $value,
                'formatted_value' => $k->formatValue($value)
            ];
        }

        $data = [
            'mahasiswa' => $calonMahasiswa,
            'dataMahasiswa' => $calonMahasiswa->dataMahasiswa,
            'kriteria' => $kriteria,
            'kriteriaValues' => $kriteriaValues,
            'title' => 'Calon Mahasiswa - ' . $calonMahasiswa->nama,
            'generated_at' => now()->format('d/m/Y H:i:s')
        ];

        $pdf = Pdf::loadView('pdf.calon-mahasiswa', $data);

        $filename = 'calon-mahasiswa-' . str_replace(' ', '-', strtolower($calonMahasiswa->nama)) . '.pdf';

        return $pdf->download($filename);
    }
    /**
     * Generate bulk PDF for multiple Calon Mahasiswa
     */
    public function calonMahasiswaBulk(Request $request)
    {
        $ids = explode(',', $request->get('ids', ''));
        $mahasiswas = CalonMahasiswa::with('dataMahasiswa')->whereIn('id', $ids)->get();

        if ($mahasiswas->isEmpty()) {
            abort(404, 'No data found');
        }

        $kriteria = Kriteria::orderBy('kode')->get();

        // Prepare data for each mahasiswa
        $mahasiswaData = [];
        foreach ($mahasiswas as $mahasiswa) {
            $kriteriaValues = [];
            foreach ($kriteria as $k) {
                $fieldName = strtolower($k->kode);
                $value = $mahasiswa->$fieldName ?? null;
                $kriteriaValues[] = [
                    'kriteria' => $k,
                    'raw_value' => $value,
                    'formatted_value' => $k->formatValue($value)
                ];
            }

            $mahasiswaData[] = [
                'mahasiswa' => $mahasiswa,
                'kriteriaValues' => $kriteriaValues
            ];
        }

        $data = [
            'mahasiswaData' => $mahasiswaData,
            'kriteria' => $kriteria,
            'title' => 'Calon Mahasiswa - Bulk Export',
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'total' => $mahasiswas->count()
        ];

        $pdf = Pdf::loadView('pdf.calon-mahasiswa-bulk', $data);

        $filename = 'calon-mahasiswa-bulk-' . now()->format('Y-m-d-H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Generate PDF for all Data Mahasiswa
     */
    public function dataMahasiswaPrintAll()
    {
        $mahasiswas = DataMahasiswa::all();

        if ($mahasiswas->isEmpty()) {
            abort(404, 'No data found');
        }

        $data = [
            'mahasiswas' => $mahasiswas,
            'title' => 'Data Mahasiswa - All Data Export',
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'total' => $mahasiswas->count()
        ];

        $pdf = Pdf::loadView('pdf.data-mahasiswa-all', $data);

        $filename = 'data-mahasiswa-all-' . now()->format('Y-m-d-H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Generate PDF for all Calon Mahasiswa
     */
    public function calonMahasiswaPrintAll()
    {
        $mahasiswas = CalonMahasiswa::with('dataMahasiswa')->get();

        if ($mahasiswas->isEmpty()) {
            abort(404, 'No data found');
        }

        $kriteria = Kriteria::orderBy('kode')->get();

        // Prepare data for each mahasiswa
        $mahasiswaData = [];
        foreach ($mahasiswas as $mahasiswa) {
            $kriteriaValues = [];
            foreach ($kriteria as $k) {
                $fieldName = strtolower($k->kode);
                $value = $mahasiswa->$fieldName ?? null;
                $kriteriaValues[] = [
                    'kriteria' => $k,
                    'raw_value' => $value,
                    'formatted_value' => $k->formatValue($value)
                ];
            }

            $mahasiswaData[] = [
                'mahasiswa' => $mahasiswa,
                'kriteriaValues' => $kriteriaValues
            ];
        }

        $data = [
            'mahasiswaData' => $mahasiswaData,
            'kriteria' => $kriteria,
            'title' => 'Calon Mahasiswa - All Data Export',
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'total' => $mahasiswas->count()
        ];

        $pdf = Pdf::loadView('pdf.calon-mahasiswa-table', $data);

        $filename = 'calon-mahasiswa-all-' . now()->format('Y-m-d-H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Debug PDF for single Data Mahasiswa
     */
    public function dataMahasiswaDebug(DataMahasiswa $dataMahasiswa)
    {
        $data = [
            'mahasiswa' => $dataMahasiswa,
            'title' => 'Data Mahasiswa - ' . $dataMahasiswa->nama,
            'generated_at' => now()->format('d/m/Y H:i:s')
        ];

        $pdf = Pdf::loadView('pdf.debug-data-mahasiswa', $data);

        $filename = 'debug-data-mahasiswa-' . str_replace(' ', '-', strtolower($dataMahasiswa->nama)) . '.pdf';

        return $pdf->download($filename);
    }
}
