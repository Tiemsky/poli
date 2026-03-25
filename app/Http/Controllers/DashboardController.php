<?php

namespace App\Http\Controllers;

use App\Models\Enrollement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
    $user = Auth::user();
    $enrollements = Enrollement::query()->with(['user'])->where('user_id', $user->id)->latest()->get();

        return Inertia::render('Dashboard', [

            'stats' => [
                'totalCouriers' => 156,
                'activeDeliveries' => 23,
                'pending' => 12,
                'completed' => 121
            ],
             'enrollements' => $enrollements,
             'applications' => []
        ]);
    }
}
