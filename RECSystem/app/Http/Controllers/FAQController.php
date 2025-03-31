<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
class FAQController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view faqs', only: ['index']),
            new Middleware('permission:edit faqs', only: ['edit']),
            new Middleware('permission:create faqs', only: ['create']),
            new Middleware('permission:delete faqs', only: ['destroy']),
        ];
    }

    public function index()
    {
        $faqs = FAQ::with('user')->latest()->paginate(10);
        return view('faqs.list', [
            'faqs' => $faqs
        ]);
    }

    public function create()
    {
        return view('faqs.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|min:3',
            'answer' => 'required|min:10',
            'status' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('faqs.create')->withInput()->withErrors($validator);
        }

        $faq = new FAQ();
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->user_id = $request->user()->id;
        $faq->status = $request->status ?? true;
        $faq->save();

        return redirect()->route('faqs.index')->with('success', 'FAQ added successfully');
    }

    public function edit(string $id)
    {
        $faq = FAQ::findOrFail($id);
        return view('faqs.edit', [
            'faq' => $faq
        ]);
    }

    public function update(Request $request, string $id)
    {
        $faq = FAQ::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'question' => 'required|min:3',
            'answer' => 'required|min:10',
            'status' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('faqs.edit', $id)->withInput()->withErrors($validator);
        }

        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->status = $request->status ?? $faq->status;
        $faq->save();

        return redirect()->route('faqs.index')->with('success', 'FAQ updated successfully');
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $faq = FAQ::findOrFail($id);

        if ($faq == null) {
            session()->flash('error', 'FAQ not found.');
            return response()->json([
                'status' => false
            ]);
        }

        $faq->delete();

        session()->flash('success', 'FAQ deleted successfully.');
        return response()->json([
            'status' => true
        ]);
    }
}
