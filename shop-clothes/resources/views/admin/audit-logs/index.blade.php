@extends('layouts.admin')

@section('page-title', 'Nhật ký hoạt động')

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Nhật ký hoạt động quản trị</h1>
            <p class="text-sm text-gray-500 mt-1">Theo dõi ai thao tác gì, trên đối tượng nào và vào thời điểm nào.</p>
        </div>
        <a href="{{ route('admin.audit-logs.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Đặt lại bộ lọc</a>
    </div>

    <form method="GET" class="bg-white border border-gray-200 rounded-xl p-4 grid grid-cols-1 lg:grid-cols-6 gap-3">
        <input type="text" name="action" value="{{ $filters['action'] }}" placeholder="Hành động (vd: user.updated)" class="border border-gray-300 rounded-lg px-3 py-2.5">

        <select name="actor_id" class="border border-gray-300 rounded-lg px-3 py-2.5">
            <option value="">Mọi người thao tác</option>
            @foreach ($actors as $actor)
                <option value="{{ $actor->id }}" @selected((string) $filters['actor_id'] === (string) $actor->id)>{{ $actor->name }} ({{ $actor->email }})</option>
            @endforeach
        </select>

        <input type="text" name="target" value="{{ $filters['target'] }}" placeholder="Đối tượng (tên/id/type)" class="border border-gray-300 rounded-lg px-3 py-2.5">
        <input type="date" name="from" value="{{ $filters['from'] }}" class="border border-gray-300 rounded-lg px-3 py-2.5">
        <input type="date" name="to" value="{{ $filters['to'] }}" class="border border-gray-300 rounded-lg px-3 py-2.5">

        <button type="submit" class="px-4 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-black">Lọc nhật ký</button>
    </form>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Thời gian</th>
                        <th class="px-4 py-3 text-left">Người thao tác</th>
                        <th class="px-4 py-3 text-left">Hành động</th>
                        <th class="px-4 py-3 text-left">Đối tượng</th>
                        <th class="px-4 py-3 text-left">Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr class="border-t border-gray-100 align-top">
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-900">{{ $log->actor?->name ?? 'Hệ thống' }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $log->ip_address ?: '-' }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">{{ $log->action }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                <p>{{ $log->target_type ?: '-' }}</p>
                                <p class="text-xs text-gray-500 mt-1">ID: {{ $log->target_id ?: '-' }} | {{ $log->target_name ?: '-' }}</p>
                            </td>
                            <td class="px-4 py-3">
                                @if (!empty($log->old_values) || !empty($log->new_values) || !empty($log->meta))
                                    <details>
                                        <summary class="cursor-pointer text-blue-700 hover:text-blue-800">Xem dữ liệu thay đổi</summary>
                                        <div class="mt-2 space-y-2">
                                            @if (!empty($log->old_values))
                                                <div>
                                                    <p class="text-xs font-semibold text-gray-600">Giá trị cũ</p>
                                                    <pre class="text-xs bg-gray-50 border border-gray-200 rounded p-2 overflow-auto">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </div>
                                            @endif
                                            @if (!empty($log->new_values))
                                                <div>
                                                    <p class="text-xs font-semibold text-gray-600">Giá trị mới</p>
                                                    <pre class="text-xs bg-gray-50 border border-gray-200 rounded p-2 overflow-auto">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </div>
                                            @endif
                                            @if (!empty($log->meta))
                                                <div>
                                                    <p class="text-xs font-semibold text-gray-600">Metadata</p>
                                                    <pre class="text-xs bg-gray-50 border border-gray-200 rounded p-2 overflow-auto">{{ json_encode($log->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </div>
                                            @endif
                                        </div>
                                    </details>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-gray-500">Chưa có dữ liệu nhật ký.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-gray-100">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
