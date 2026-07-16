<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use App\Models\User;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Audit::query()->latest();

        if ($request->filled('model')) {
            $query->where('auditable_type', $request->model);
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $audits = $query->paginate(20);

        $users = User::select('id', 'name_en','name_ur')->get();
        $models = Audit::select('auditable_type')->distinct()->pluck('auditable_type');
        $events = Audit::select('event')->distinct()->pluck('event');

        return view('audit-logs.index', compact('audits', 'users', 'models', 'events'));
    }
}
