<?php

namespace Database\Seeders;

use App\Models\Evaluation;
use App\Models\EvaluationSession;
use App\Models\Finding;
use App\Models\Informant;
use App\Models\Insight;
use App\Models\Interview;
use App\Models\Observation;
use App\Models\PainPoint;
use App\Models\Task;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $dataset = $this->dataset();

        foreach ($dataset as $index => $item) {
            $evaluation = Evaluation::create([
                'kode_evaluasi' => sprintf('EV-2026-%03d', $index + 1),
                'status' => 'selesai',
            ]);

            Informant::create([
                'evaluation_id' => $evaluation->id,
                ...$item['informant'],
            ]);

            EvaluationSession::create([
                'evaluation_id' => $evaluation->id,
                ...$item['session'],
            ]);

            $taskModels = [];
            foreach ($item['tasks'] as $taskData) {
                $observations = $taskData['observations'];
                unset($taskData['observations']);

                $task = Task::create([
                    'evaluation_id' => $evaluation->id,
                    ...$taskData,
                ]);

                Observation::create([
                    'task_id' => $task->id,
                    ...$observations,
                ]);

                $taskModels[] = $task;
            }

            Interview::create([
                'evaluation_id' => $evaluation->id,
                'catatan' => $item['interview_catatan'],
            ]);

            Insight::create([
                'evaluation_id' => $evaluation->id,
                'catatan' => $item['insight_catatan'],
            ]);

            $painPointModels = [];
            foreach ($item['pain_points'] as $ppData) {
                $painPointModels[] = PainPoint::create([
                    'evaluation_id' => $evaluation->id,
                    ...$ppData,
                ]);
            }

            foreach ($item['findings'] as $findingData) {
                $taskRef = isset($findingData['task_index'])
                    ? $taskModels[$findingData['task_index']]->id
                    : null;
                $ppRef = isset($findingData['pain_point_index'])
                    ? $painPointModels[$findingData['pain_point_index']]->id
                    : null;

                unset($findingData['task_index'], $findingData['pain_point_index']);

                Finding::create([
                    'evaluation_id' => $evaluation->id,
                    'task_id' => $taskRef,
                    'pain_point_id' => $ppRef,
                    ...$findingData,
                ]);
            }
        }
    }

    private function dataset(): array
    {
        return [
            // Evaluasi 1
            [
                'informant' => [
                    'kode' => 'INF-01',
                    'nama' => 'Ahmad Fauzi',
                    'jabatan' => 'Staf Akademik',
                    'unit' => 'Bagian Akademik',
                    'pengalaman_penggunaan' => '1 tahun',
                    'frekuensi_penggunaan' => 'Setiap hari',
                    'catatan' => 'Informan cukup terbuka dan komunikatif selama sesi.',
                ],
                'session' => [
                    'tanggal' => '2026-08-10',
                    'waktu' => '09:00',
                    'durasi' => '50 menit',
                    'tujuan' => 'Mengamati proses input data akademik pada SI-IMUT.',
                    'konteks' => 'Sesi dilakukan di ruang kerja informan pada jam sibuk pelayanan.',
                    'lingkungan' => 'Kantor, cukup ramai',
                    'perangkat' => 'Laptop Windows, browser Chrome',
                    'kondisi_penggunaan' => 'Informan sambil melayani mahasiswa yang datang langsung.',
                    'catatan' => 'Beberapa kali sesi terjeda karena interupsi pekerjaan lain.',
                ],
                'tasks' => [
                    [
                        'tujuan' => 'Menambahkan data akademik mahasiswa baru.',
                        'instruksi' => 'Silakan input satu data mahasiswa baru ke sistem.',
                        'status' => 'berhasil',
                        'waktu_penyelesaian' => '3 menit 20 detik',
                        'catatan' => 'Sempat ragu di form karena banyak field.',
                        'observations' => [
                            'tindakan' => 'Mengisi form input secara berurutan dari atas ke bawah.',
                            'perilaku' => 'Membaca label field dengan teliti sebelum mengisi.',
                            'reaksi' => 'Terlihat bingung saat menemukan field tanpa keterangan format.',
                            'kesulitan' => 'Tidak tahu format tanggal yang diminta sistem.',
                            'kebingungan' => 'Sempat submit gagal karena format tanggal salah.',
                            'strategi_pengguna' => 'Mencoba beberapa format tanggal secara trial-error.',
                            'catatan' => 'Perlu ada contoh format di placeholder field.',
                        ],
                    ],
                    [
                        'tujuan' => 'Mencari data mahasiswa berdasarkan NIM.',
                        'instruksi' => 'Silakan cari data mahasiswa dengan NIM tertentu.',
                        'status' => 'berhasil',
                        'waktu_penyelesaian' => '1 menit 10 detik',
                        'catatan' => 'Task ini relatif lancar.',
                        'observations' => [
                            'tindakan' => 'Langsung menuju kolom pencarian di bagian atas.',
                            'perilaku' => 'Mengetik NIM lalu menekan Enter.',
                            'reaksi' => 'Puas karena hasil pencarian muncul cepat.',
                            'kesulitan' => 'Tidak ada kendala berarti.',
                            'kebingungan' => 'Tidak ada.',
                            'strategi_pengguna' => 'Menggunakan fitur pencarian bawaan sistem.',
                            'catatan' => 'Fitur pencarian dinilai sudah cukup baik.',
                        ],
                    ],
                ],
                'interview_catatan' => 'Informan menyampaikan bahwa sistem sudah cukup membantu, namun form input dinilai terlalu panjang dan kurang ada petunjuk pengisian.',
                'insight_catatan' => 'Kebutuhan utama informan adalah efisiensi input data, bukan kelengkapan fitur.',
                'pain_points' => [
                    [
                        'deskripsi' => 'Field tanggal tidak memiliki format/placeholder yang jelas.',
                        'catatan' => 'Menyebabkan beberapa kali gagal submit.',
                    ],
                    [
                        'deskripsi' => 'Form input akademik terasa panjang tanpa pengelompokan section.',
                        'catatan' => 'Informan sempat kehilangan fokus di tengah pengisian.',
                    ],
                ],
                'findings' => [
                    [
                        'judul' => 'Format tanggal tidak jelas pada form input',
                        'deskripsi' => 'Pengguna kesulitan menentukan format tanggal yang benar sehingga terjadi error submit berulang.',
                        'kategori' => 'interaction',
                        'severity' => 'high',
                        'frequency' => '1 dari 3 informan',
                        'impact' => 'Menambah waktu penyelesaian task dan menurunkan kepercayaan diri pengguna.',
                        'root_cause' => 'Tidak ada placeholder atau date-picker pada field tanggal.',
                        'catatan' => 'Prioritas perbaikan tinggi.',
                        'task_index' => 0,
                        'pain_point_index' => 0,
                    ],
                    [
                        'judul' => 'Form input akademik terlalu panjang',
                        'deskripsi' => 'Semua field ditampilkan dalam satu halaman panjang tanpa pengelompokan.',
                        'kategori' => 'visual_ui',
                        'severity' => 'medium',
                        'frequency' => '2 dari 3 informan',
                        'impact' => 'Pengguna mudah kehilangan konteks saat mengisi form.',
                        'root_cause' => 'Tidak ada accordion/tab untuk mengelompokkan field.',
                        'catatan' => null,
                        'task_index' => 0,
                        'pain_point_index' => 1,
                    ],
                ],
            ],

            // Evaluasi 2
            [
                'informant' => [
                    'kode' => 'INF-02',
                    'nama' => 'Siti Rahma',
                    'jabatan' => 'Operator Layanan',
                    'unit' => 'Bagian Kemahasiswaan',
                    'pengalaman_penggunaan' => '6 bulan',
                    'frekuensi_penggunaan' => 'Beberapa kali seminggu',
                    'catatan' => 'Informan pengguna baru, belum terlalu familiar dengan sistem.',
                ],
                'session' => [
                    'tanggal' => '2026-08-12',
                    'waktu' => '13:30',
                    'durasi' => '40 menit',
                    'tujuan' => 'Mengamati proses pengajuan surat melalui SI-IMUT.',
                    'konteks' => 'Sesi dilakukan setelah jam istirahat siang.',
                    'lingkungan' => 'Kantor, tenang',
                    'perangkat' => 'PC desktop, browser Edge',
                    'kondisi_penggunaan' => 'Kondisi normal, tidak ada gangguan berarti.',
                    'catatan' => 'Informan cukup nyaman dan tidak terburu-buru.',
                ],
                'tasks' => [
                    [
                        'tujuan' => 'Mengajukan permohonan surat keterangan aktif kuliah.',
                        'instruksi' => 'Silakan buat pengajuan surat untuk satu mahasiswa.',
                        'status' => 'gagal',
                        'waktu_penyelesaian' => '5 menit 45 detik',
                        'catatan' => 'Informan tidak menemukan menu pengajuan surat.',
                        'observations' => [
                            'tindakan' => 'Menjelajahi beberapa menu sidebar secara berulang.',
                            'perilaku' => 'Tampak ragu dan sering kembali ke halaman dashboard.',
                            'reaksi' => 'Frustrasi karena tidak menemukan menu yang dimaksud.',
                            'kesulitan' => 'Label menu tidak sesuai istilah yang biasa digunakan informan.',
                            'kebingungan' => 'Mengira fitur surat ada di menu "Layanan", padahal di menu "Administrasi".',
                            'strategi_pengguna' => 'Mencoba membuka semua menu satu per satu.',
                            'catatan' => 'Task akhirnya dihentikan karena informan menyerah.',
                        ],
                    ],
                ],
                'interview_catatan' => 'Informan menyatakan istilah menu di sidebar kurang familiar dan berbeda dari istilah yang biasa dipakai sehari-hari di unit kerjanya.',
                'insight_catatan' => 'Terdapat kesenjangan istilah (terminology gap) antara label sistem dengan bahasa kerja pengguna di lapangan.',
                'pain_points' => [
                    [
                        'deskripsi' => 'Label menu navigasi tidak sesuai dengan istilah yang familiar bagi pengguna.',
                        'catatan' => 'Menyebabkan pengguna gagal menyelesaikan task.',
                    ],
                ],
                'findings' => [
                    [
                        'judul' => 'Navigasi menu tidak intuitif bagi pengguna baru',
                        'deskripsi' => 'Pengguna gagal menemukan fitur pengajuan surat karena label menu tidak sesuai ekspektasi.',
                        'kategori' => 'navigation',
                        'severity' => 'critical',
                        'frequency' => '1 dari 3 informan',
                        'impact' => 'Pengguna gagal menyelesaikan task inti, berpotensi menimbulkan keluhan berulang.',
                        'root_cause' => 'Penamaan menu berdasarkan struktur teknis sistem, bukan mental model pengguna.',
                        'catatan' => 'Perlu uji ulang setelah perbaikan label menu.',
                        'task_index' => 0,
                        'pain_point_index' => 0,
                    ],
                ],
            ],

            // Evaluasi 3
            [
                'informant' => [
                    'kode' => 'INF-03',
                    'nama' => 'Budi Santoso',
                    'jabatan' => 'Kepala Unit',
                    'unit' => 'Bagian Kepegawaian',
                    'pengalaman_penggunaan' => '2 tahun',
                    'frekuensi_penggunaan' => 'Setiap hari',
                    'catatan' => 'Informan berpengalaman, cukup kritis dalam memberi masukan.',
                ],
                'session' => [
                    'tanggal' => '2026-08-15',
                    'waktu' => '10:00',
                    'durasi' => '60 menit',
                    'tujuan' => 'Mengamati proses rekap laporan bulanan.',
                    'konteks' => 'Sesi dilakukan menjelang deadline laporan bulanan.',
                    'lingkungan' => 'Kantor, agak terburu-buru',
                    'perangkat' => 'Laptop, browser Firefox',
                    'kondisi_penggunaan' => 'Informan sedikit terburu-buru karena tenggat waktu.',
                    'catatan' => 'Kondisi ini memengaruhi cara informan berinteraksi dengan sistem.',
                ],
                'tasks' => [
                    [
                        'tujuan' => 'Mengunduh rekap laporan bulanan dalam format PDF.',
                        'instruksi' => 'Silakan unduh laporan bulan berjalan.',
                        'status' => 'berhasil',
                        'waktu_penyelesaian' => '2 menit',
                        'catatan' => 'Task selesai meski sempat mencari tombol unduh cukup lama.',
                        'observations' => [
                            'tindakan' => 'Membuka halaman laporan lalu men-scroll ke bawah.',
                            'perilaku' => 'Terlihat terburu-buru dalam membaca tampilan.',
                            'reaksi' => 'Sedikit kesal karena tombol unduh berada di posisi tidak terduga.',
                            'kesulitan' => 'Tombol unduh PDF terletak di bagian bawah halaman, di luar area pandang awal.',
                            'kebingungan' => 'Sempat mengira laporan hanya bisa dilihat, bukan diunduh.',
                            'strategi_pengguna' => 'Melakukan scroll penuh ke bawah halaman untuk mencari opsi unduh.',
                            'catatan' => 'Posisi tombol perlu dipindah ke area yang lebih mudah terlihat.',
                        ],
                    ],
                    [
                        'tujuan' => 'Memfilter laporan berdasarkan rentang tanggal.',
                        'instruksi' => 'Silakan tampilkan laporan untuk periode tertentu.',
                        'status' => 'berhasil',
                        'waktu_penyelesaian' => '1 menit 30 detik',
                        'catatan' => 'Tidak ada kendala berarti.',
                        'observations' => [
                            'tindakan' => 'Mengisi filter tanggal menggunakan date-picker.',
                            'perilaku' => 'Cukup percaya diri karena sudah terbiasa dengan pola UI serupa.',
                            'reaksi' => 'Puas dengan hasil filter yang sesuai.',
                            'kesulitan' => 'Tidak ada kendala berarti.',
                            'kebingungan' => 'Tidak ada.',
                            'strategi_pengguna' => 'Menggunakan date-picker bawaan sistem.',
                            'catatan' => 'Fitur filter dinilai sudah baik.',
                        ],
                    ],
                ],
                'interview_catatan' => 'Informan menilai fitur laporan sudah cukup lengkap, namun tata letak tombol aksi utama perlu diperbaiki agar lebih mudah ditemukan.',
                'insight_catatan' => 'Penempatan call-to-action yang tidak strategis dapat menurunkan efisiensi meskipun fitur yang dibutuhkan sebenarnya sudah tersedia.',
                'pain_points' => [
                    [
                        'deskripsi' => 'Tombol unduh laporan PDF berada di luar area pandang awal pengguna.',
                        'catatan' => 'Berpotensi membuat pengguna mengira fitur unduh tidak tersedia.',
                    ],
                ],
                'findings' => [
                    [
                        'judul' => 'Tombol unduh laporan sulit ditemukan',
                        'deskripsi' => 'Posisi tombol unduh PDF berada di bagian bawah halaman sehingga tidak langsung terlihat oleh pengguna.',
                        'kategori' => 'visual_ui',
                        'severity' => 'medium',
                        'frequency' => '2 dari 3 informan',
                        'impact' => 'Menambah waktu penyelesaian task dan menimbulkan keraguan pengguna.',
                        'root_cause' => 'Tata letak halaman tidak mengikuti prinsip visibilitas elemen penting.',
                        'catatan' => 'Rekomendasi: pindahkan tombol ke bagian atas halaman.',
                        'task_index' => 0,
                        'pain_point_index' => 0,
                    ],
                ],
            ],
        ];
    }
}
