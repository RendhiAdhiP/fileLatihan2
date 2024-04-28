<?php

namespace App\Http\Controllers\Api;

use App\Models\Form;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AllowedDomain;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
    public function createForm(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:forms,slug|regex:/^[a-zA-Z.-]+/',
            'allowed_domains' => ['required', 'array'],
            'description' => 'required',
            'limit_one_response' => 'required',
        ]);

        $form = Form::create([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'description' => $request->description,
            'limit_one_response' => $request->limit_one_response,
            'creator_id' => $request->user()->id,
            'id' => $request->id,
        ]);


        // $dataForm = $request->user()->form;



        if (!$request->allowedDomain == null) {
            foreach ($request->allowed_domains as $domain) {
                AllowedDomain::create(['domain' => $domain, 'form_id' => $form->id]);
            }
        }

        $form->load(['allowedDomain']);

        $form = [
            // dd($form->allowedDomain),
            'name' => $form->name,
            'slug' => $form->slug,
            // 'allowed_domain' => $form->allowedDomain->domain,
            'description' => $form->description,
            'limit_one_response' => $form->limit_one_response,
            'creator_id' => $request->user()->id,
            'id' => $form->id,
        ];


        return response()->json(['message' => 'create form success', 'form' => $form], 200);
    }



    public function getForms(Request $request)
    {

        $forms = Form::all();
        return response()->json(['message' => 'Get all form success', 'forms' => $forms]);
    }


    public function detail($slug, Request $request)
    {

        $form = Form::where('slug', $slug)->first();

        if (!$form) {
            return response()->json(['message' => 'not found'], 404);
        }

        $form->load(['allowedDomain', 'question']);
        $choices = $form->question->map(function ($c) {
            if (!$c->choices == null) {
                return [
                    "id" => $c->id,
                    "form_id" => $c->form_id,
                    "name" => $c->name,
                    "choice_type" => $c->type,
                    'choices' => trim($c['choices'], '[],"'),
                    "is_required" => $c->is_required,

                ];
            } else {
                return [
                    "id" => $c->id,
                    "form_id" => $c->form_id,
                    "name" => $c->name,
                    "choice_type" => $c->type,
                    'choices' => $c->choices,
                    "is_required" => $c->is_required,

                ];
            }
        });


        $formDetail = [
            // dd($form),
            'name' => $form->name,
            'slug' => $form->slug,
            'description' => $form->description,
            'limit_one_response' => $form->limit_one_response,
            'creator_id' => $form->creator_id,
            'allowed_domain' => $form->alowedDomain,
            'question' => $choices,
        ];
        return response()->json(['message' => 'Get form success', 'forms' => $formDetail], 200);
    }
}
