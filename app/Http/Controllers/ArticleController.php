<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class ArticleController extends Controller
{
    // Fetch Articles with Search and Pagination
    public function getAllArticles(Request $request)
    {
        try {
            $query = Article::query();

            if ($request->has('keyword')) {
                $query->where('title', 'like', '%' . $request->keyword . '%');
            }
            if ($request->has('category')) {
                $query->where('category', $request->category);
            }
            if ($request->has('source')) {
                $query->where('source', $request->source);
            }
            if ($request->has('date')) {
                $query->whereDate('published_at', $request->date);
            }

            return response()->json($query->paginate(10), 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch articles', 'message' => $e->getMessage()], 500);
        }
    }

    // Fetch Single Article
    public function getArticle($id)
    {
        try {
            $article = Article::findOrFail($id);
            return response()->json($article, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Article not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch article', 'message' => $e->getMessage()], 500);
        }
    }

    // Create New Article (Protected)
    public function storeArticle(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'source' => 'required|string',
                'content' => 'required|string',
                'url' => 'nullable|url',
                'image_url' => 'nullable|url',
                'published_at' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => 'Validation failed', 'messages' => $validator->errors()], 400);
            }

            $article = Article::create($request->all());
            return response()->json(['message' => 'Article created successfully', 'data' => $article], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create article', 'message' => $e->getMessage()], 500);
        }
    }
}
