<?php

namespace App\Http\Controllers;

use App\Models\Enrollement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
public function index(Request $request)
{
    $user = Auth::user();

    $enrollements = Enrollement::query()
        ->with(['user'])
        ->where('user_id', $user->id)
        ->when($request->input('search'), function ($query, $search) {
            $query->where(function($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                  ->orWhere('working_for', 'like', "%{$search}%");
            });
        })
        ->when($request->input('status'), function ($query, $status) {
            $query->where('status', $status);
        })
        ->latest()
        ->get();

    return Inertia::render('Dashboard', [
        'stats' => [
            'totalCouriers' => 156, // À dynamiser si besoin
            'activeDeliveries' => 23,
            'pending' => $enrollements->where('status', 'pending')->count(),
            'completed' => $enrollements->where('status', 'approved')->count()
        ],
        'enrollements' => $enrollements,
        'filters' => $request->only(['search', 'status'])
    ]);
}
}
