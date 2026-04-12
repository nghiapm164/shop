<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        return $this->renderUserList($request, 'all', 'Quản lý tài khoản');
    }

    public function customers(Request $request): View
    {
        return $this->renderUserList($request, 'customer', 'Quản lý khách hàng');
    }

    public function staff(Request $request): View
    {
        return $this->renderUserList($request, 'staff_admin', 'Quản lý nhân sự');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roleOptions' => $this->roleOptions(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        $actor = auth()->user();
        $oldValues = $user->only(['name', 'email', 'phone', 'address', 'role', 'is_active', 'email_verified_at']);

        if ($actor && $actor->role !== 'super_admin' && $user->role === 'super_admin') {
            return back()->with('error', 'Chỉ Super Admin mới được chỉnh sửa tài khoản Super Admin.');
        }

        if ($actor && $actor->role !== 'super_admin' && ($data['role'] ?? '') === 'super_admin') {
            return back()->with('error', 'Bạn không có quyền gán vai trò Super Admin.');
        }

        if ($user->id === auth()->id() && ($data['is_active'] ?? true) === false) {
            return back()->with('error', 'Bạn không thể tự khóa tài khoản đang đăng nhập.');
        }

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'role' => $data['role'],
            'is_active' => (bool) $data['is_active'],
            'email_verified_at' => $data['email_verified_at'] ?? null,
        ]);

        $this->writeAudit(
            request: $request,
            action: 'user.updated',
            target: $user,
            oldValues: $oldValues,
            newValues: $user->only(['name', 'email', 'phone', 'address', 'role', 'is_active', 'email_verified_at'])
        );

        return redirect()->route('admin.users.index')->with('success', 'Đã cập nhật thông tin tài khoản.');
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'bulk_action' => ['required', 'in:activate,deactivate,delete'],
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $users = User::whereIn('id', array_unique($data['user_ids']))->get();

        if ($users->isEmpty()) {
            return back()->with('error', 'Không có tài khoản hợp lệ để thực hiện thao tác hàng loạt.');
        }

        $processed = 0;
        $skipped = 0;

        foreach ($users as $user) {
            if (!$this->canManageTarget($user)) {
                $skipped++;
                continue;
            }

            $oldValues = $user->only(['name', 'email', 'role', 'is_active']);

            if ($data['bulk_action'] === 'activate') {
                if (!$user->is_active) {
                    $user->update(['is_active' => true]);
                    $processed++;
                    $this->writeAudit($request, 'user.bulk_activated', $user, $oldValues, $user->only(['name', 'email', 'role', 'is_active']));
                }
                continue;
            }

            if ($data['bulk_action'] === 'deactivate') {
                if ($user->is_active) {
                    $user->update(['is_active' => false]);
                    $processed++;
                    $this->writeAudit($request, 'user.bulk_deactivated', $user, $oldValues, $user->only(['name', 'email', 'role', 'is_active']));
                }
                continue;
            }

            if ($data['bulk_action'] === 'delete') {
                $this->writeAudit($request, 'user.bulk_deleted', $user, $oldValues, null);
                $user->delete();
                $processed++;
            }
        }

        if ($processed === 0) {
            return back()->with('error', 'Không có tài khoản nào được xử lý. Có thể do thiếu quyền hoặc dữ liệu không thay đổi.');
        }

        $message = "Đã xử lý {$processed} tài khoản";
        if ($skipped > 0) {
            $message .= ", bỏ qua {$skipped} tài khoản do ràng buộc quyền.";
        } else {
            $message .= '.';
        }

        return back()->with('success', $message);
    }

    public function toggleActive(User $user): RedirectResponse
    {
        $actor = auth()->user();

        if ($actor && $actor->role !== 'super_admin' && $user->role === 'super_admin') {
            return back()->with('error', 'Chỉ Super Admin mới được khóa/mở khóa tài khoản Super Admin.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể tự thay đổi trạng thái tài khoản đang đăng nhập.');
        }

        $oldValues = $user->only(['name', 'email', 'role', 'is_active']);

        $user->update(['is_active' => !$user->is_active]);

        $this->writeAudit(
            request: request(),
            action: 'user.status_toggled',
            target: $user,
            oldValues: $oldValues,
            newValues: $user->only(['name', 'email', 'role', 'is_active'])
        );

        return back()->with('success', 'Đã cập nhật trạng thái tài khoản.');
    }

    public function sendResetLink(User $user): RedirectResponse
    {
        $status = Password::sendResetLink(['email' => $user->email]);

        if ($status !== Password::RESET_LINK_SENT) {
            return back()->with('error', 'Không thể gửi email đặt lại mật khẩu lúc này.');
        }

        $this->writeAudit(
            request: request(),
            action: 'user.reset_link_sent',
            target: $user,
            oldValues: null,
            newValues: null,
            meta: ['status' => $status]
        );

        return back()->with('success', 'Đã gửi email đặt lại mật khẩu.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $actor = auth()->user();

        if ($actor && $actor->role !== 'super_admin' && $user->role === 'super_admin') {
            return back()->with('error', 'Chỉ Super Admin mới được xóa tài khoản Super Admin.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể xóa tài khoản đang đăng nhập.');
        }

        $oldValues = $user->only(['name', 'email', 'phone', 'role', 'is_active']);
        $this->writeAudit(request(), 'user.deleted', $user, $oldValues, null);

        $user->delete();

        return back()->with('success', 'Đã xóa tài khoản người dùng.');
    }

    private function renderUserList(Request $request, string $scope, string $title): View
    {
        $query = User::query();

        $search = trim((string) $request->query('q', ''));
        $role = (string) $request->query('role', '');
        $status = (string) $request->query('status', '');
        $verified = (string) $request->query('verified', '');

        if ($scope === 'customer') {
            $query->where('role', 'customer');
        }

        if ($scope === 'staff_admin') {
            $query->whereIn('role', ['staff', 'admin', 'super_admin']);
        }

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role !== '') {
            $query->where('role', $role);
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        }

        if ($status === 'inactive') {
            $query->where('is_active', false);
        }

        if ($verified === 'yes') {
            $query->whereNotNull('email_verified_at');
        }

        if ($verified === 'no') {
            $query->whereNull('email_verified_at');
        }

        $users = $query->latest('id')->paginate(15)->withQueryString();

        $statsBase = User::query();
        if ($scope === 'customer') {
            $statsBase->where('role', 'customer');
        }
        if ($scope === 'staff_admin') {
            $statsBase->whereIn('role', ['staff', 'admin', 'super_admin']);
        }

        $stats = [
            'total' => (clone $statsBase)->count(),
            'active' => (clone $statsBase)->where('is_active', true)->count(),
            'inactive' => (clone $statsBase)->where('is_active', false)->count(),
            'verified' => (clone $statsBase)->whereNotNull('email_verified_at')->count(),
        ];

        return view('admin.users.index', [
            'title' => $title,
            'users' => $users,
            'stats' => $stats,
            'scope' => $scope,
            'roleOptions' => $this->roleOptions(),
            'filters' => [
                'q' => $search,
                'role' => $role,
                'status' => $status,
                'verified' => $verified,
            ],
        ]);
    }

    private function roleOptions(): array
    {
        return [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'staff' => 'Nhân viên',
            'customer' => 'Khách hàng',
        ];
    }

    private function canManageTarget(User $user): bool
    {
        $actor = auth()->user();

        if (!$actor) {
            return false;
        }

        if ($user->id === $actor->id) {
            return false;
        }

        if ($actor->role !== 'super_admin' && $user->role === 'super_admin') {
            return false;
        }

        return true;
    }

    private function writeAudit(
        Request $request,
        string $action,
        User $target,
        ?array $oldValues,
        ?array $newValues,
        ?array $meta = null
    ): void {
        AuditLog::create([
            'actor_user_id' => auth()->id(),
            'action' => $action,
            'target_type' => User::class,
            'target_id' => $target->id,
            'target_name' => $target->name,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'meta' => $meta,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);
    }
}
