<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminAuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AuditLog::query()->with('actor');

        $action = trim((string) $request->query('action', ''));
        $actorId = (string) $request->query('actor_id', '');
        $from = (string) $request->query('from', '');
        $to = (string) $request->query('to', '');
        $target = trim((string) $request->query('target', ''));

        if ($action !== '') {
            $query->where('action', 'like', "%{$action}%");
        }

        if ($actorId !== '') {
            $query->where('actor_user_id', $actorId);
        }

        if ($from !== '') {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to !== '') {
            $query->whereDate('created_at', '<=', $to);
        }

        if ($target !== '') {
            $query->where(function ($builder) use ($target) {
                $builder->where('target_name', 'like', "%{$target}%")
                    ->orWhere('target_type', 'like', "%{$target}%")
                    ->orWhere('target_id', $target);
            });
        }

        $logs = $query->latest('id')->paginate(30)->withQueryString();

        return view('admin.audit-logs.index', [
            'logs' => $logs,
            'filters' => [
                'action' => $action,
                'actor_id' => $actorId,
                'from' => $from,
                'to' => $to,
                'target' => $target,
            ],
            'actors' => User::orderBy('name')->get(['id', 'name', 'email']),
        ]);
    }
}
