<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Utils\AppHttpUtils;
use App\Utils\LogUtils;
use App\Imports\QuestionsImport;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\NullOutput;
use Throwable;

class QuestionController extends Controller
{

    /**
     * Store a new file projection
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $error = null;
        $status = false;
        $responseMessage = '';
        $response = "failure";
        $responseData = null;

        $request->validate([
            'questions' => 'required|mimes:xlsx,csv,txt,xls'
        ]);

        try {

            $file = $request->file('questions');

            $import = new QuestionsImport();
            $import->import($file);

            $responseMessage = 'Questions imported successfully';
            $status = true;
            $response = "success";
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            foreach ($failures as $failure) {
                $data = [
                    'row' => $failure->row(), // row that went wrong
                    'col' => $failure->attribute(), // either heading key (if using heading row concern) or column index
                    'errors' => $failure->errors(), // Actual error messages from Laravel validator
                    'value' => $failure->values()[$failure->attribute()], // The values of the row that has failed.
                ];

                $responseData[] = $data;
                $responseMessage = 'validation not passed';
            }
        } catch (Throwable $e) {
            $status = false;
            $responseMessage = "An error occurred";
            $error = $e->getMessage();
        }

        $res = AppHttpUtils::responseStructure($responseMessage, $status, $responseData ? $responseData : $response);
        write_log(LogUtils::getLogData($request, $error ? $error : $res, 'Create Question'));

        return response()->json($res);
    }

    public function getAllQuestions(Request $request)
    {
        $error = null;
        $status = false;
        $responseMessage = '';
        $response = "failure";
        $responseData = null;

        try {

            $response = DB::table('questions');

            if ($request->has('category')) {
                $response->where('category', '=', $request->category);
            }
            $status = true;
            $responseMessage = 'ok';
            $response = $response->latest()->paginate($request->has('per_page') ? (int)$request->per_page : 20);
        } catch (Throwable $e) {
            $status = false;
            $responseMessage = "An error occurred";
            $error = $e->getMessage();
        }

        $res = AppHttpUtils::responseStructure($responseMessage, $status, $responseData ? $responseData : $response);
        write_log(LogUtils::getLogData($request, $error ? $error : $res, 'Get All Question'));

        return response()->json($res);
    }

    public function getSingleQuestion(Request $request, $id)
    {
        $error = null;
        $status = false;
        $responseMessage = '';
        $response = "failure";
        $responseData = null;

        try {

            $response = Question::where('id', $id)->first();

            $responseMessage = 'ok';
            $status = true;
        } catch (Throwable $e) {
            $status = false;
            $responseMessage = "An error occurred";
            $error = $e->getMessage();
        }

        $res = AppHttpUtils::responseStructure($responseMessage, $status, $responseData ? $responseData : $response);
        write_log(LogUtils::getLogData($request, $error ? $error : $res, 'Get Single Question'));

        return response()->json($res);
    }

    public function updateQuestion(Request $request)
    {
        $error = null;
        $status = false;
        $responseMessage = '';
        $response = "failure";
        $responseData = null;

        $request->validate([
            'id' => 'required|integer',
            'question' => 'required|string',
        ]);

        try {

            $updateData = Question::where('id', $request->id)->update(['question' => $request->question]);

            if (!$updateData) {
                $responseMessage = "Id does not exist";
            } else {
                $response = "success";
                $responseMessage = 'Question updated successfully';
                $status = true;
            }
        } catch (Throwable $e) {
            $status = false;
            $responseMessage = "An error occurred";
            $error = $e->getMessage();
        }

        $res = AppHttpUtils::responseStructure($responseMessage, $status, $responseData ? $responseData : $response);
        write_log(LogUtils::getLogData($request, $error ? $error : $res, 'Update Question'));

        return response()->json($res);
    }

    public function deleteQuestion(Request $request, $id)
    {
        $error = null;
        $status = false;
        $responseMessage = '';
        $response = "failure";
        $responseData = null;

        try {

            $data = Question::find($id);

            if (!$data) {
                $responseMessage = "Id does not exist";
            } else {
                $data->delete();
                $response = "success";
                $responseMessage = 'Question deleted successfully';
                $status = true;
            }
        } catch (Throwable $e) {
            $status = false;
            $responseMessage = "An error occurred";
            $error = $e->getMessage();
        }

        $res = AppHttpUtils::responseStructure($responseMessage, $status, $responseData ? $responseData : $response);
        write_log(LogUtils::getLogData($request, $error ? $error : $res, 'Delete Question'));

        return response()->json($res);
    }

    public function updateChoice(Request $request)
    {
        $error = null;
        $status = false;
        $responseMessage = '';
        $response = "failure";
        $responseData = null;

        $request->validate([
            'id' => 'required|integer',
            'choice_1' => 'nullable|string',
            'choice_2' => 'nullable|string',
            'choice_3' => 'nullable|string',
            'choice_4' => 'nullable|string',
        ]);

        try {

            $data = Question::where('id', $request->id);

            if (!$data) {
                $responseMessage = "Please check your id";
            } else {
                if ($request->choice_1) {
                    $data->update(['choice_1' => $request->choice_1]);
                }
                if ($request->choice_2) {
                    $data->update(['choice_2' => $request->choice_2]);
                }
                if ($request->choice_3) {
                    $data->update(['choice_3' => $request->choice_3]);
                }
                if ($request->choice_4) {
                    $data->update(['choice_4' => $request->choice_4]);
                }

                $response = "success";
                $responseMessage = 'Choice updated successfully';
                $status = true;
            }
        } catch (Throwable $e) {
            $status = false;
            $responseMessage = "An error occurred";
            $error = $e->getMessage();
        }

        $res = AppHttpUtils::responseStructure($responseMessage, $status, $responseData ? $responseData : $response);
        write_log(LogUtils::getLogData($request, $error ? $error : $res, 'Update Question Choice'));

        return response()->json($res);
    }

    public function deleteChoice(Request $request)
    {
        $error = null;
        $status = false;
        $responseMessage = '';
        $response = "failure";
        $responseData = null;

        $request->validate([
            'id' => 'required|integer',
            'choice' => 'required|integer|between:1,4'
        ]);

        try {

            $data = Question::find($request->id);

            if (!$data) {
                $responseMessage = "Please check your id";
            } else {
                if ($request->choice === 1) {
                    $data->update(['choice_1' => null]);
                }
                if ($request->choice === 2) {
                    $data->update(['choice_2' => null]);
                }
                if ($request->choice === 3) {
                    $data->update(['choice_3' => null]);
                }
                if ($request->choice === 4) {
                    $data->update(['choice_4' => null]);
                }

                $response = "success";
                $responseMessage = "Choice {$request->choice} deleted successfully";
                $status = true;
            }
        } catch (Throwable $e) {
            $status = false;
            $responseMessage = "An errror occurred";
            $error = $e->getMessage();
        }

        $res = AppHttpUtils::responseStructure($responseMessage, $status, $responseData ? $responseData : $response);
        write_log(LogUtils::getLogData($request, $error ? $error : $res, 'Delete Question Choice'));

        return response()->json($res);
    }
}
