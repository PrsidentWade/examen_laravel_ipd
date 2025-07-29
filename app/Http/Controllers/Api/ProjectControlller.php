<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\error;
use Illuminate\Support\Facades\Auth;

class ProjectControlller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $projet = Project::with('owner')->get();
        if ($projet->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Project non Trouve',
                'Total' => $projet->count()
            ], 403);
        }

        return response()->json([
            'status' => true,
            'message' => 'Produit recuperer avec success',
            "project" => $projet,
            'Total' => $projet->count()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nom_project' => 'required|string|max:100',
            'description' => 'required|string|max:255',

        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de validation',
                'errors' => $validate->errors()
            ], 403);
        }

        $project = Project::create([
            'nom_project' => $request->nom_project,
            'description' => $request->description,
            'owners_id' => Auth::id(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Project Creer avec success !!!',
            'project' => $project
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'status' => false,
                'message' => 'Project non Trouver'
            ], 403);
        }
        return response()->json([
            'status' => true,
            'message' => 'Project recuperer avec success',
            'project' => $project
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(), [
            'nom_project' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validate->errors()
            ], 422);
        }

        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'status' => false,
                'message' => 'Projet non trouvé'
            ], 404);
        }

        $project->update([
            'nom_project' => $request->nom_project,
            'description' => $request->description,
            'owners_id' => Auth::id(), // Ajout correct
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Projet mis à jour avec succès',
            'project' => $project
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'status' => false,
                'message' => 'Project non Trouver'
            ], 403);
        }

        $project->delete();
        return response()->json([
            'status' => true,
            'message' => 'Project supprimer avec success'
        ], 200);
    }
}
