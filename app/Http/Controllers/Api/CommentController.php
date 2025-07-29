<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comment = Comment::all();

        if ($comment->isEmpty()) {
            return response()->json([
                'status' => false,
                'Message' => 'Comment non trouve',
            ], 403);
        }

        return response()->json([
            'status' => true,
            'Message' => 'Comment recuperer avec success',
            'comment' => $comment,
            'Total' => $comment->count()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'texte' => 'required|string|max:200',
            'auteur_id' => 'required|exists:users,id',
            'taks_id' =>  'required|exists:taks,id'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'Message' => 'Erreur lors de la validation',

            ], 403);
        }

        $comment = Comment::create([
            'texte' => $request->texte,
            'auteur_id' => $request->auteur_id,
            "taks_id" => $request->taks_id
        ]);
        return response()->json([
            'status' => true,
            'Message' => 'Commentaire Effectue avec Success!!!',
            'comment' => $comment
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json([
                'status' => false,
                'message' => 'Commentaire non trouve',

            ], 403);
        }

        return response()->json([
            'status' => true,
            'message' => 'Commentaire trouve',
            'comment' => $comment
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(), [
            'texte' => 'required|string|max:200',
            'auteur_id' => 'required|exists:users,id',
            'taks_id' =>  'required|exists:taks,id'
        ]);

        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json([
                'status' => false,
                'message' => 'Commentaire non trouve',

            ], 403);
        }

        $comment->update([
            'texte' => $request->texte,
            'auteur_id' => $request->auteur_id,
            "taks_id" => $request->taks_id
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Commentaire mis a jour ',
            'comment' => $comment
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'status' => false,
                'message' => 'Commentaire non trouve',

            ], 403);
        }

        $comment->delete();
        return response()->json([
            'status' => true,
            'message' => 'Commentaire Supprimer avec success'
        ], 200);
    }
}
