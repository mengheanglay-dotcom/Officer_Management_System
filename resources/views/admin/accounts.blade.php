@extends('layouts.app')

@section('title', 'Account approvals')

@section('content')
    @php
        $statusStyles = [
            'pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
            'approved' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'rejected' => 'bg-rose-50 text-rose-700 ring-rose-200',
        ];
        $tabs = [
            'pending' => ['label' => 'Pending', 'icon' => 'clock-3'],
            'approved' => ['label' => 'Approved', 'icon' => 'circle-check'],
            'rejected' => ['label' => 'Rejected', 'icon' => 'circle-x'],
        ];
        $button = 'inline-flex items-center justify-center gap-2 rounded-lg px-3.5 py-2.5 text-sm font-medium transition focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2';
    @endphp

    <div class="mx-auto max-w-6xl space-y-6">
        <header class="flex flex-col gap-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-6">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#102a4c] text-white">
                    <i data-lucide="user-check" class="h-5 w-5"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-indigo-600">Administration</p>
                    <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Account approvals</h1>
                    <p class="mt-1 text-sm text-slate-500">Review officer registrations and manage account access.</p>
                </div>
            </div>
            <div class="flex items-center gap-3 border-t border-slate-100 pt-4 sm:border-0 sm:pt-0">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                    <i data-lucide="users" class="h-5 w-5"></i>
                </div>
                <div>
                    <p class="text-2xl font-semibold leading-none text-slate-900">{{ $accounts->total() }}</p>
                    <p class="mt-1 text-xs capitalize text-slate-500">{{ $status }} account{{ $accounts->total() === 1 ? '' : 's' }}</p>
                </div>
            </div>
        </header>

        <nav class="rounded-xl border border-slate-200 bg-white p-1.5 shadow-sm" aria-label="Filter by status">
            <div class="grid grid-cols-3 gap-1">
                @foreach ($tabs as $key => $tab)
                    <a href="{{ route('admin.accounts', ['status' => $key]) }}"
                        @if ($status === $key) aria-current="page" @endif
                        class="flex items-center justify-center gap-2 rounded-lg px-3 py-3 text-sm font-medium transition focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400
                            {{ $status === $key ? 'bg-[#102a4c] text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i data-lucide="{{ $tab['icon'] }}" class="h-4 w-4"></i>
                        <span>{{ $tab['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </nav>

        @if ($accounts->count())
            <section class="space-y-4" aria-label="{{ ucfirst($status) }} accounts">
                @foreach ($accounts as $account)
                    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="p-5 sm:p-6">
                            <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">
                                <div class="flex min-w-0 items-start gap-4">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-full bg-sky-50 text-base font-semibold text-[#102a4c] ring-4 ring-slate-50">
                                        @if ($account->officer?->avatar_path || $account->avatar_path)
                                            <img src="{{ asset('storage/' . ($account->officer?->avatar_path ?: $account->avatar_path)) }}"
                                                alt="{{ $account->name }}" class="h-full w-full object-cover">
                                        @else
                                            {{ strtoupper(substr($account->name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h2 class="text-base font-semibold text-slate-900">{{ $account->name }}</h2>
                                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $statusStyles[$account->approval_status] }}">
                                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                                {{ ucfirst($account->approval_status) }}
                                            </span>
                                        </div>
                                        <p class="mt-1 break-all text-sm text-slate-500">{{ $account->email }}</p>
                                        <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-xs text-slate-500">
                                            <span class="inline-flex items-center gap-1.5">
                                                <i data-lucide="shield" class="h-3.5 w-3.5 text-slate-400"></i> Officer
                                            </span>
                                            <span class="inline-flex items-center gap-1.5">
                                                <i data-lucide="calendar-days" class="h-3.5 w-3.5 text-slate-400"></i>
                                                {{ $account->created_at->format('M d, Y') }}
                                            </span>
                                            @if ($account->officer?->officer_id)
                                                <span class="inline-flex items-center gap-1.5">
                                                    <i data-lucide="hash" class="h-3.5 w-3.5 text-slate-400"></i>
                                                    Record #{{ $account->officer->officer_id }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2 border-t border-slate-100 pt-4 xl:border-0 xl:pt-0">
                                    @if ($account->approval_status === 'pending')
                                        <form method="POST" action="{{ route('admin.accounts.approve', $account) }}"
                                            onsubmit="return confirm('Approve this officer account?')">
                                            @csrf
                                            <button class="{{ $button }} bg-emerald-600 text-white hover:bg-emerald-700 focus-visible:ring-emerald-500">
                                                <i data-lucide="check" class="h-4 w-4"></i> Approve
                                            </button>
                                        </form>
                                        <button type="button" data-reject-toggle="{{ $account->id }}" aria-expanded="false"
                                            aria-controls="reject-panel-{{ $account->id }}"
                                            class="{{ $button }} border border-slate-300 bg-white text-slate-700 hover:border-rose-300 hover:bg-rose-50 hover:text-rose-700 focus-visible:ring-rose-400">
                                            <i data-lucide="x" class="h-4 w-4"></i> Reject
                                        </button>
                                    @elseif ($account->approval_status === 'approved')
                                        <form method="POST" action="{{ route('admin.accounts.toggle', $account) }}"
                                            onsubmit="return confirm('{{ $account->is_active ? 'Deactivate' : 'Activate' }} this account?')">
                                            @csrf
                                            <button class="{{ $button }} text-white {{ $account->is_active ? 'bg-amber-600 hover:bg-amber-700 focus-visible:ring-amber-500' : 'bg-emerald-600 hover:bg-emerald-700 focus-visible:ring-emerald-500' }}">
                                                <i data-lucide="{{ $account->is_active ? 'pause-circle' : 'play-circle' }}" class="h-4 w-4"></i>
                                                {{ $account->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.accounts.approve', $account) }}"
                                            onsubmit="return confirm('Approve this officer account?')">
                                            @csrf
                                            <button class="{{ $button }} bg-emerald-600 text-white hover:bg-emerald-700 focus-visible:ring-emerald-500">
                                                <i data-lucide="check" class="h-4 w-4"></i> Approve
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            @if ($account->approval_status === 'approved' && ! $account->is_active)
                                <div class="mt-5 flex items-start gap-2.5 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                                    <i data-lucide="info" class="mt-0.5 h-4 w-4 shrink-0"></i>
                                    <span>This account is approved but currently inactive.</span>
                                </div>
                            @endif

                            @if ($account->rejection_reason)
                                <div class="mt-5 flex items-start gap-2.5 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                                    <i data-lucide="message-square-warning" class="mt-0.5 h-4 w-4 shrink-0"></i>
                                    <p><span class="font-medium">Rejection reason:</span> {{ $account->rejection_reason }}</p>
                                </div>
                            @endif

                            @if ($account->approval_status === 'pending')
                                <div id="reject-panel-{{ $account->id }}" class="mt-5 hidden rounded-xl border border-slate-200 bg-slate-50 p-4 sm:p-5">
                                    <form method="POST" action="{{ route('admin.accounts.reject', $account) }}" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label for="reason-{{ $account->id }}" class="block text-sm font-medium text-slate-800">Reason for rejection</label>
                                            <p class="mt-1 text-xs text-slate-500">This reason is saved and shared with the applicant.</p>
                                        </div>
                                        <textarea id="reason-{{ $account->id }}" name="rejection_reason" rows="3" required maxlength="1000"
                                            placeholder="Explain what needs to be corrected."
                                            class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">{{ old('rejection_reason') }}</textarea>
                                        @error('rejection_reason')
                                            <p class="text-xs text-rose-600">{{ $message }}</p>
                                        @enderror
                                        <div class="flex justify-end gap-2">
                                            <button type="button" data-reject-toggle="{{ $account->id }}"
                                                class="{{ $button }} border border-slate-300 bg-white text-slate-700 hover:bg-slate-100 focus-visible:ring-slate-400">
                                                Cancel
                                            </button>
                                            <button type="submit" onclick="return confirm('Confirm rejection?')"
                                                class="{{ $button }} bg-rose-600 text-white hover:bg-rose-700 focus-visible:ring-rose-500">
                                                Confirm rejection
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </section>
        @else
            <section class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                    <i data-lucide="{{ $status === 'pending' ? 'inbox' : 'users' }}" class="h-6 w-6"></i>
                </div>
                <h2 class="mt-4 font-medium text-slate-900">No {{ $status }} accounts</h2>
                <p class="mt-1 text-sm text-slate-500">There are no accounts in this category.</p>
            </section>
        @endif

        @if ($accounts->hasPages())
            <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                {{ $accounts->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <script>
        document.querySelectorAll('[data-reject-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const panel = document.getElementById(`reject-panel-${button.dataset.rejectToggle}`);
                if (!panel) return;

                const isHidden = panel.classList.toggle('hidden');
                const opener = document.querySelector(
                    `button[data-reject-toggle="${button.dataset.rejectToggle}"][aria-expanded]`
                );

                if (opener) opener.setAttribute('aria-expanded', String(!isHidden));
                if (!isHidden) panel.querySelector('textarea').focus();
            });
        });
    </script>
@endsection
