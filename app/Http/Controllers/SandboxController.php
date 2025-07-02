<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SandboxController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::query()
            ->orderBy('id', 'desc')
            ->cursorPaginate(12);

        return Inertia::render('Sandbox', [
            'courses' => Inertia::deepMerge($courses),
        ]);
    }
}
