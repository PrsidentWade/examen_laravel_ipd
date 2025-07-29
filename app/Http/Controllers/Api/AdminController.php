<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Taks;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Console\View\Components\Task;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //
            $statistique = [
                'users_count' => $this->getUserCount(),
                'projet_count' => $this->getProjetCount(),
                'taks_count' => $this->getTacheCount(),
                'Project_recent' => $this->getRecentProjects(),
                'Taks_recent' => $this->getRecentTasks()
            ];
            return response()->json([
                'status' => true,
                'data' => $statistique
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Get total user
     */
    public function getUserCount(): int
    {
        return User::count();
    }
    /**
     * Get total projects
     */
    public function getProjetCount(): int
    {
        return Project::count();
    }
    /**
     * Get Total numbers Taches effucter
     */
    public function getTacheCount(): int
    {
        return Taks::count();
    }
    /**
     * Get recent projects (last 30 days)
     */
    public function getRecentProjects(int $limit = 10): array
    {
        return Project::with(['owner:id,name,email'])
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($project) {
                return [
                    'id' => $project->id,
                    'nom_project' => $project->nom_project ?? $project->name,
                    'description' => $project->description,
                    'created_at' => $project->created_at->format('Y-m-d H:i:s'),
                    'user' => $project->owner ? [
                        'id' => $project->owner->id,
                        'name' => $project->owner->name,
                        'email' => $project->owner->email
                    ] : null
                ];
            })
            ->toArray();
    }
    /**
     * Get recent tasks (last 30 days)
     */
    public function getRecentTasks(int $limit = 10): array
    {
        return Taks::with(['assignee:id,name,email', 'project:id,nom_project,description'])
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'titre' => $task->titre ?? $task->name,
                    'description' => $task->description,
                    'etat' => $task->etat,
                    'deadline' => $task->deadline ?? 'medium',
                    'created_at' => $task->created_at->format('Y-m-d H:i:s'),
                    'assignee' => $task->assignee  ? [
                        'id' => $task->assignee->id,
                        'name' => $task->assignee->name,
                        'email' => $task->assignee->email
                    ] : null,
                    'project' => $task->project ? [
                        'id' => $task->project->id,
                        'nom_project' => $task->project->nom_project,
                        'description' => $task->project->description

                    ] : null
                ];
            })
            ->toArray();
    }
}
