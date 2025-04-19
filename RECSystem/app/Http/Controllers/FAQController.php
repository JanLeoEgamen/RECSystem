<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

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

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = FAQ::with('user')->select('*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $editBtn = '';
                    $deleteBtn = '';
                    
                    if (request()->user()->can('edit faqs')) {
                        $editBtn = '<a href="'.route('faqs.edit', $row->id).'" class="inline-block mb-2 px-5 py-2 text-white hover:text-[#101966] hover:border-[#101966] bg-[#101966] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#101966] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Edit</a>';
                    }
                    
                    if (request()->user()->can('delete faqs')) {
                        $deleteBtn = '<a href="javascript:void(0)" onclick="deleteFAQ('.$row->id.')" class="inline-block px-3 py-2 text-white hover:text-[#a10303] hover:border-[#a10303] bg-[#a10303] hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#a10303] border border-white border font-medium dark:border-[#3E3E3A] dark:hover:bg-black dark:hover:border-[#3F53E8] rounded-lg text-md leading-normal">Delete</a>';
                    }
                    
                    return $editBtn.' '.$deleteBtn;
                })
                ->editColumn('question', function($row) {
                    return Str::limit($row->question, 50);
                })
                ->editColumn('answer', function($row) {
                    return Str::limit($row->answer, 50);
                })
                ->addColumn('author', function($row) {
                    return $row->user->first_name . ' ' . $row->user->last_name;
                })
                ->editColumn('status', function($row) {
                    return $row->status 
                        ? '<span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Active</span>'
                        : '<span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Inactive</span>';
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('d M, y');
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        
        return view('faqs.list');
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
