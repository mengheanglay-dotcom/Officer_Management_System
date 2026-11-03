@extends('layouts.app')
@section('title', 'Account approvals')
@section('content')
    <div class="mb-6"><h1 class="text-2xl font-semibold">Account approvals</h1><p class="text-sm text-slate-500">Review officer registrations and account status.</p></div>
    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="border-b bg-slate-50"><tr><th class="px-4 py-3">Officer</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Reason</th><th class="px-4 py-3 text-right">Actions</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($accounts as $account)
                    <tr>
                        <td class="px-4 py-3"><div class="font-medium">{{ $account->name }}</div><div class="text-xs text-slate-500">{{ $account->email }}</div></td>
                        <td class="px-4 py-3">{{ ucfirst($account->approval_status) }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $account->rejection_reason ?: '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                @if ($account->approval_status !== 'approved')
                                    <form method="POST" action="{{ route('admin.accounts.approve', $account) }}">@csrf<button class="rounded-lg bg-emerald-600 px-3 py-2 text-xs font-medium text-white">Approve</button></form>
                                    <form method="POST" action="{{ route('admin.accounts.reject', $account) }}" onsubmit="return confirm('Reject this registration?')">@csrf<input name="rejection_reason" required placeholder="Reason" class="rounded-lg border px-2 py-2 text-xs"><button class="rounded-lg bg-slate-700 px-3 py-2 text-xs font-medium text-white">Reject</button></form>
                                @else
                                    <form method="POST" action="{{ route('admin.accounts.toggle', $account) }}" onsubmit="return confirm('{{ $account->is_active ? 'Deactivate' : 'Activate' }} this account?')">@csrf<button class="rounded-lg {{ $account->is_active ? 'bg-amber-600' : 'bg-emerald-600' }} px-3 py-2 text-xs font-medium text-white">{{ $account->is_active ? 'Deactivate' : 'Activate' }}</button></form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-10 text-center text-slate-500">No pending account registrations.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $accounts->links() }}</div>
@endsection
