<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaffDocumentTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documentNameKh = [
            'ទម្រង់សម្ភាសន៍',
            'បញ្ចក់លក្ខខណ្ឌក្នុងពេលចុះកិច្ចសន្យា',
            'ពាក្យសំុបម្រើការងារ',
            'ប្រវត្តិរូបសង្ខេប',
            'ឯកសារភ្ជាប់',
            'កិច្ចសន្យាបន្ថែមថ្មី',
            'លិខិតធានា',
            'ទិន្នន័យតាមដានប្រវត្តិរូប',
            'រូបថត',
            'បទពិពណ៌នាការងារ',
            'បច្ចុប្បន្នភាពបុគ្គលិក',
            'លិខិតប្រគល់-ទទួលសម្ភារៈ',
            'លិខិតផ្ទេរការងារ',
            'កិច្ចសន្យាជួលម៉ូតូ',
            'ភាពច្បាស់លាស់ ពីនិតិវិធី​ និងគោលការណ៍',
            'NSSF(លេខកាតបសស)',
            'លិខិតណែនាំកំហុស',
            'លិខិតព្រមាន',
        ];

        foreach ($documentNameKh as $item)
        {
            DB::table('staff_document_types')->insert([
                'name_kh' => $item,
            ]);
        }

        echo count($documentNameKh).' Document Types created';

    }
}
