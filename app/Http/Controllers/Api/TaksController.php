<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Taks;
use Illuminate\Support\Facades\Validator;

class TaksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taks = Taks::with(['project:id,nom_project', 'assignee:id,name'])->get();
        if ($taks->isEmpty()) {
            return response()->json([
                'status' => false,
                'meassge' => 'Taks non trouve',

            ], 403);
        }
        return response()->json([
            'status' => true,
            'message' => 'Taks recuperer avec success',
            'taches' => $taks,
            'total' => $taks->count()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'titre' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'etat' => 'required|string|in:a_faire,en_cours,termine',
            'deadline' => 'nullable|date',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'status' => true,
                'message' => 'Erreur lors de la validation',
                'error' => $validate->errors()
            ], 403);
        }

        $taks = Taks::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'etat' => $request->etat,
            'deadline' => $request->deadline,
            "assigned_to" => $request->assigned_to,
            'project_id' => $request->project_id

        ]);
        return response()->json([
            'status' => true,
            'message' =>  'Taks creer avec success !!!',
            'tache' => $taks,

        ], 200);
    }

    /**
     * Display the specified resource.
     */

    public function show(string $id)
    {
        $taks = Taks::find($id);

        if (!$taks) {
            return response()->json([
                'status' => false,
                "message" => 'Taks Non trouver',

            ], 403);
        }

        return response()->json([
            'status' => true,
            'message' => 'Tache Recuperer avec success !!!',
            'Tache' => $taks
        ], 200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(), [
            'titre' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'etat' => 'required|string|in:a_faire,en_cours,termine',
            'deadline' => 'nullable|date',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id'
        ]);
        $taks = Taks::find($id);

        if (!$taks) {
            return response()->json([
                'status' => false,
                "message" => 'Taks Non trouver',

            ], 403);
        }

        $taks->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'etat' => $request->etat,
            'deadline' => $request->deadline,
            "assigned_to" => $request->assigned_to
        ]);
        return response()->json([
            'status' => true,
            'Message' => 'Tache mis a jour avec success',
            'tache' => $taks
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $taks = Taks::find($id);

        if (!$taks) {
            return response()->json([
                'status' => false,
                "message" => 'Taks Non trouver',
                'error' => $taks->errors()

            ], 403);
        }
        $taks->delete();
        return response()->json([
            'status' => true,
            'Message' => 'Tache suppprimer avec success !!!'
        ], 200);
    }
}
