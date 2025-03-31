<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


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
    public function index()
    {
        $articles = Article::with('user')->latest()->paginate(10);
        return view('articles.list', [
            'articles' => $articles
        ]);
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
