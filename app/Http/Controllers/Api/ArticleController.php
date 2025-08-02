<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function articles()
    {
        $articles = Article::where('is_published', true)->with('user')->get();
        return response()->json([
            'success' => true,
            'message' => 'Articles retrieved successfully.',
            'data' => $articles,
        ], 200);
    }

    public function mine(Request $request)
    {
        $user = $request->user();

        if (!$user->hasRole('author')) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to perform this action.',
                'error' => 'FORBIDDEN',
            ], 403);
        }

        $articles = Article::where('user_id', $user->id)->with('user')->get();

        return response()->json([
            'success' => true,
            'message' => 'Articles retrieved successfully.',
            'data' => $articles,
        ], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Article::class);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $article = $request->user()->articles()->create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Article created successfully.',
            'data' => $article,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        try{

            $article = Article::findOrFail($id);

            $this->authorize('update', $article);

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'body' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $article->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Article updated successfully.',
                'data' => $article,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Article not found or you do not have permission to update it.',
                'error' => 'NOT_FOUND_OR_FORBIDDEN',
            ], 404);
        }
    }

    public function delete($id)
    {
        try {

        $article = Article::findOrFail($id);

        $this->authorize('delete', $article);

        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Article deleted successfully.',
        ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Article not found or you do not have permission to delete it.',
                'error' => 'NOT_FOUND_OR_FORBIDDEN',
            ], 404);
        }

    }

    public function publish($id)
    {
        try {

        $article = Article::findOrFail($id);

        $this->authorize('publish', $article);

        $article->is_published = true;
        $article->save();

        return response()->json([
            'success' => true,
            'message' => 'Article published successfully.',
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Article not found or you do not have permission to publish it.',
            'error' => 'NOT_FOUND_OR_FORBIDDEN',
        ], 404);
    }

    }

}
