<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SandboxController extends Controller
{
    public function index(Request $request)
    {
        logger(__METHOD__.':debug-log: request()->input() '.var_export(request()->input(), true));
        $courses = Course::query()
            ->orderBy('id', 'desc')
            ->cursorPaginate(30);

        return Inertia::render('Sandbox', [
            'courses' => Inertia::deepMerge($courses),
        ]);
    }
}
