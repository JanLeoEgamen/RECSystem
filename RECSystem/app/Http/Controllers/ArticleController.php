<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ArticleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view articles', only: ['index']),
            new Middleware('permission:edit articles', only: ['edit']),
            new Middleware('permission:create articles', only: ['create']),
            new Middleware('permission:delete articles', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Article::with('user')->select('*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $buttons = '';
                    
                    if (request()->user()->can('edit articles')) {
                        $buttons .= '<a href="'.route('articles.edit', $row->id).'" class="p-2 text-indigo-600 hover:text-white hover:bg-indigo-600 rounded-full transition-colors duration-200" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>';
                    }

                    if (request()->user()->can('delete articles')) {
                        $buttons .= '<a href="javascript:void(0)" onclick="deleteArticle('.$row->id.')" class="p-2 text-red-600 hover:text-white hover:bg-red-600 rounded-full transition-colors duration-200" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </a>';
                    }

                    return '<div class="flex space-x-2">'.$buttons.'</div>';
                })
                ->editColumn('image', function($row) {
                    if ($row->image) {
                        return '<img src="'.asset('images/'.$row->image).'" alt="Article Image" class="h-20 w-20 object-cover">';
                    }
                    return 'No Image';
                })
                ->editColumn('status', function($row) {
                    return $row->status 
                        ? '<span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Active</span>'
                        : '<span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Inactive</span>';
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('d M, y');
                })
                ->addColumn('uploaded_by', function($row) {
                    return $row->user->first_name . ' ' . $row->user->last_name;
                })
                ->rawColumns(['action', 'status', 'image'])
                ->make(true);
        }
        
        return view('articles.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3',
            'author' => 'required|min:3',
            'content' => 'required|min:10',
            'status' => 'sometimes|boolean',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('articles.create')->withInput()->withErrors($validator);
        }

        $article = new Article();
        $article->title = $request->title;
        $article->content = $request->content;
        $article->author = $request->author;
        $article->user_id = $request->user()->id;
        $article->status = $request->status ?? true;
        $article->save();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
            $article->image = $imagePath;
            $article->save();
        }

        return redirect()->route('articles.index')->with('success', 'Article added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $article = Article::findOrFail($id);
        return view('articles.edit', [
            'article' => $article
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $article = Article::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3',
            'author' => 'required|min:3',
            'content' => 'required|min:10',
            'status' => 'sometimes|boolean',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('articles.edit', $id)->withInput()->withErrors($validator);
        }

        $article->title = $request->title;
        $article->author = $request->author;
        $article->content = $request->content;
        $article->status = $request->status ?? $article->status;
        $article->save();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            
            $imagePath = $request->file('image')->store('articles', 'public');
            $article->image = $imagePath;
            $article->save();
        }

        return redirect()->route('articles.index')->with('success', 'Article updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $article = Article::findOrFail($id);

        if ($article == null) {
            session()->flash('error', 'Article not found.');
            return response()->json([
                'status' => false
            ]);
        }

        // Delete image if exists
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        session()->flash('success', 'Article deleted successfully.');
        return response()->json([
            'status' => true
        ]);
    }

}
