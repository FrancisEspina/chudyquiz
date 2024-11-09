<?php

namespace Database\Seeders;

use App\Models\QuestionType;
use App\Models\Subject;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $csvFile = fopen(base_path('database/seeders/questions.csv'), 'r');

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== FALSE) {
            if (!$firstline) {


                $questionId = DB::table('questions')->insertGetId([
                    'question' => $data[1],
                    'qtype_id' => QuestionType::where('name', $data[9])->value('id'),
                    'difficulty' => $data[8],
                    'subject_id' => Subject::where('name', $data[10])->value('id'),
                ]);

                if ($data[2] != '') {
                    DB::table('items')->insert([
                        'question_id' => $questionId,
                        'A' => $data[2],
                        'B' => $data[3],
                        'C' => $data[4],
                        'D' => $data[5],
                    ]);
                }

                DB::table('answers')->insert([
                    'question_id' => $questionId,
                    'answer' => $data[6],
                ]);
                //}
            }
            $firstline = false;
        }
        fclose($csvFile);
        // echo "?????";
        // echo $csvFile;
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
