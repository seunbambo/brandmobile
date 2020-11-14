<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use App\Question;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class QuestionsImport implements ToCollection, WithStartRow, WithValidation, SkipsOnError
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    use Importable, SkipsErrors;

    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {

            // skip empty row
            if (!isset($row[0])) continue;

            $question = Question::where('question', $row[0])->first();

            if ($question) {
                $question->update([
                    'question'              => $row[0],
                    'is_general'            => $row[1],
                    'category'              => $row[2],
                    'point'                 => $row[3],
                    'icon_url'              => $row[4],
                    'duration'              => $row[5],
                    'choice_1'              => $row[6],
                    'is_correct_choice_1'   => $row[7],
                    'icon_url_1'            => $row[8],
                    'choice_2'              => $row[9],
                    'is_correct_choice_2'   => $row[10],
                    'icon_url_2'            => $row[11],
                    'choice_3'              => $row[12],
                    'is_correct_choice_3'   => $row[13],
                    'icon_url_3'            => $row[14],
                    'choice_4'              => $row[15],
                    'is_correct_choice_4'   => $row[16],
                    'icon_url_4'            => $row[17]
                ]);
            } else {
                Question::create(
                    [
                        'question'              => $row[0],
                        'is_general'            => $row[1],
                        'category'              => $row[2],
                        'point'                 => $row[3],
                        'icon_url'              => $row[4],
                        'duration'              => $row[5],
                        'choice_1'              => $row[6],
                        'is_correct_choice_1'   => $row[7],
                        'icon_url_1'            => $row[8],
                        'choice_2'              => $row[9],
                        'is_correct_choice_2'   => $row[10],
                        'icon_url_2'            => $row[11],
                        'choice_3'              => $row[12],
                        'is_correct_choice_3'   => $row[13],
                        'icon_url_3'            => $row[14],
                        'choice_4'              => $row[15],
                        'is_correct_choice_4'   => $row[16],
                        'icon_url_4'            => $row[17]
                    ]
                );
            }
        }
    }

    public function startRow(): int
    {
        // skip first row, the titles row
        return 2;
    }

    // public function onError(Throwable $error){}

    public function rules(): array
    {
        return [
            '0' => 'required|string',
            '1' => 'nullable|boolean',
            '2' => 'nullable|string',
            '3' => 'nullable|integer',
            '4' => 'nullable|string',
            '5' => 'nullable|integer',
            '6' => 'nullable',
            '7' => 'nullable|boolean',
            '8' => 'nullable|string',
            '9' => 'nullable',
            '10' => 'nullable|boolean',
            '11' => 'nullable|string',
            '12' => 'nullable',
            '13' => 'nullable|boolean',
            '14' => 'nullable|string',
            '15' => 'nullable',
            '16' => 'nullable|boolean',
            '17' => 'nullable|string',
        ];
    }
}
