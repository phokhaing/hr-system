<?php

namespace Modules\HRTraining\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HRTraining\Entities\Question;

class QuestionController extends Controller
{


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->input('data');
        $data = [];

        if ($input['question_type'] === QUESTION_TYPE['OPEN']['key']) {
            $data = [
                'exam_id' => @$input['exam']['id'],
                'json_data->type' => QUESTION_TYPE['OPEN']['value'],
                'json_data->title' => @$input['open']['title'],
                'json_data->point' => @$input['open']['point'],
            ];
        }

        if ($input['question_type'] === QUESTION_TYPE['CLOSE']['key']) {
            $data = [
                'exam_id' => @$input['exam']['id'],
                'json_data->type' => QUESTION_TYPE['CLOSE']['value'],
                'json_data->title' => @ $input['close']['title'],
                'json_data->point' => @$input['close']['point'],
                'json_data->answer' => [
                    [
                        'id' => 1,
                        'title' => 'Yes',
                        'status' => (@$input['close']['answer'] === 'yes') ? ANSWER_STATUS['CORRECT'] : ANSWER_STATUS['WRONG']
                    ],
                    [
                        'id' => 2,
                        'title' => 'No',
                        'status' => (@$input['close']['answer'] === 'no') ? ANSWER_STATUS['CORRECT'] : ANSWER_STATUS['WRONG']
                    ]
                ]
            ];
        }

        if ($input['question_type'] === QUESTION_TYPE['MULTIPLE-CHOICE']['key']) {
            $answer = [];
            foreach ($input['multipleChoice']['answerLists'] as $value) {
                $answer[] = [
                    'id' => $value["id"],
                    'title' => $value["title"],
                    'status' => ($value["answer"] === true) ? ANSWER_STATUS['CORRECT'] : ANSWER_STATUS['WRONG']
                ];
            }
            $data = [
                'exam_id' => @$input['exam']['id'],
                'json_data->type' => QUESTION_TYPE['MULTIPLE-CHOICE']['value'],
                'json_data->title' => @ $input['multipleChoice']['title'],
                'json_data->point' => @$input['multipleChoice']['point'],
                'json_data->answer' => $answer
            ];
        }

        $exam = new Question();
        $record = $exam->createRecord($data);

        return response()->json([
            'status' => 'success',
            'data' => $record
        ]);
    }


    public function destroy(Request $request)
    {
        $id = $request->input('data');
        $question = Question::destroy($id);
        return response()->json([
            'data' => $question,
            'status' => 'deleted'
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function listByExamId($id)
    {
        $questions = Question::select('id', 'json_data')->getByExamId($id)->get();
        $questions->map(function ($question){
            switch ($question->json_data->type){
                case QUESTION_TYPE['OPEN']['value']:
                    $question->question_type = QUESTION_TYPE['OPEN']['key'];
                    break;
                case QUESTION_TYPE['CLOSE']['value']:
                    $question->question_type = QUESTION_TYPE['CLOSE']['key'];
                    break;
                case QUESTION_TYPE['MULTIPLE-CHOICE']['value']:
                    $question->question_type = QUESTION_TYPE['MULTIPLE-CHOICE']['key'];
                    break;
                default:
                    $question->question_type = "N/A";
                    break;
            }
        });
        return response()->json([
            'data' => $questions,
            'status' => 'success'
        ]);
    }
}
