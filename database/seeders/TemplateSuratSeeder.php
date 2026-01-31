<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Surat Himbauan',
                'description' => 'Template untuk himbauan internal/eksternal',
                'content' => '
                    <div style="font-family: Arial, sans-serif; line-height: 1.6;">
                        <h3 style="text-align: center; text-transform: uppercase; margin-bottom: 5px;">SURAT HIMBAUAN</h3>
                        <p style="text-align: center; margin-top: 0;">Nomor: [nomor_surat]</p>
                        
                        <br>
                        
                        <p>Yth. [tujuan]<br>
                        di Tempat</p>
                        
                        <br>
                        
                        <p>Dengan hormat,</p>
                        
                        <p>[isi_surat]</p>
                        
                        <br>
                        
                        <p>Demikian himbauan ini kami sampaikan untuk menjadi perhatian dan dilaksanakan sebagaimana mestinya.</p>
                        
                        <br><br>
                        
                        <div style="float: right; text-align: center; width: 250px;">
                            <p>[tempat_tanggal]</p>
                            <p>Hormat Kami,</p>
                            <br><br><br>
                            <p><strong>[penandatangan]</strong></p>
                        </div>
                        <div style="clear: both;"></div>
                        
                        <br>
                        <p><strong>Tembusan:</strong><br>
                        [tembusan]</p>
                    </div>
                ',
            ],
            [
                'name' => 'Surat Dinas Umum',
                'description' => 'Template surat dinas standar',
                'content' => '
                    <div style="font-family: Times New Roman, serif; line-height: 1.5;">
                        <h3 style="text-align: center; text-transform: uppercase; text-decoration: underline;">SURAT DINAS</h3>
                        <p style="text-align: center;">Nomor: [nomor_surat]</p>
                        
                        <br>
                        
                        <table>
                            <tr><td>Sifat</td><td>: [sifat]</td></tr>
                            <tr><td>Lampiran</td><td>: -</td></tr>
                            <tr><td>Perihal</td><td>: [perihal]</td></tr>
                        </table>
                        
                        <br>
                        
                        <p>Kepada Yth.<br>
                        [tujuan]<br>
                        di Tempat</p>
                        
                        <br>
                        
                        <p>Dengan hormat,</p>
                        <p>[isi_surat]</p>
                        
                        <p>Demikian surat ini kami sampaikan. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>
                        
                        <br><br>
                        
                        <div style="float: right; text-align: center;">
                            <p>Ditetapkan di: [tempat]<br>
                            Pada tanggal: [tanggal]</p>
                            <br>
                            <p>Pejabat Penandatangan,</p>
                            <br><br><br>
                            <p><strong>[penandatangan]</strong></p>
                        </div>
                    </div>
                ',
            ],
        ];

        foreach ($templates as $data) {
            \App\Models\TemplateSurat::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}
