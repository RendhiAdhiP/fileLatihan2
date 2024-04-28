<?php

namespace App\Http\Controllers\Api;

use App\Models\Form;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
    public function addQuestion($slug, Request $request)
    {

        $data = $request->validate([
            'name' => 'required',
            'choice_type' => 'required|in:short answer,paragraph,date,multiple choice,dropdown,checkboxes',
            'choices' => 'required_if:choice_type,multiple choice,choice_type,dropdown,choice_type,checkboxes|array',
            'is_required' => 'required',
        ]);

        $form = Form::where('slug', $slug)->first();

        // 404
        if (!$form) {
            return response()->json(['message' => 'not found'], 404);
        }


        // 403
        // $user = $request->user()->id;
        // $creator = Form::all()->pluck('creator_id')->first();

        // if ($creator != $user) {
        //     return response()->json(['message' => 'bukan user'], 403);
        // }



        if (isset($data['choices'])) {
            $data['choices'] = trim(json_encode($data['choices']), '[],"');
        }

        // dd($data['choices']);

        // dd($request->name);
        $question = Question::create([
            'name' => $request->name,
            'type' => $request->choice_type,
            'is_required' => $request->is_required ?? 1,
            'choices' => $data['choices'] ?? null,
            'form_id' => $form->id,
            // 'form_id'=>$request->$form->id, 
        ]);



        $quest = [
            // dd($question->name),
            'name' => $question->name,
            'type' => $question->type,
            'is_required' => $question->is_required,
            'choices' => $question->choices,
            'form_id' => $question->form_id,
            'id' => $question->id,
        ];


        return response()->json(['message' => 'Get form success', 'question' => $quest], 200);
    }


    public function removeQuest($slug, $id)
    {

        $form = Form::where('slug', $slug)->first();

        // 404
        if (!$form) {
            return response()->json(['message' => 'not found'], 404);
        }


        // 403
        // $user = auth()->user()->id;
        // $creator = Form::all()->pluck('creator_id')->first();

        // if ($creator != $user) {
        //     return response()->json(['message' => 'bukan user'], 403);
        // }


        $que = Question::where('id', $id)->delete();

        if (!$que) {
            return response()->json(['message' => 'question bot found'], 404);
        }
        return response()->json(['message' => 'success delete question'], 200);
    }
}
