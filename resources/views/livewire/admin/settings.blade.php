<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Profil & Pengaturan Usaha</h1>
            <p class="text-xs text-slate-500 mt-1">Identitas usaha SORASA, nomor WhatsApp admin, dan info rekening nota pesanan</p>
        </div>
    </div>

    <div class="max-w-3xl space-y-6">
        <x-card title="Identitas Bisnis & Dapur">
            <dl class="divide-y divide-slate-100 text-xs">
                <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="font-semibold text-slate-500">Nama Usaha</dt>
                    <dd class="mt-1 font-bold text-slate-900 sm:col-span-2 sm:mt-0">{{ $settings['store_name'] ?? '-' }}</dd>
                </div>
                <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="font-semibold text-slate-500">Slogan / Tagline</dt>
                    <dd class="mt-1 text-slate-700 sm:col-span-2 sm:mt-0">{{ $settings['store_tagline'] ?? '-' }}</dd>
                </div>
                <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="font-semibold text-slate-500">Alamat Dapur</dt>
                    <dd class="mt-1 text-slate-700 sm:col-span-2 sm:mt-0">{{ $settings['store_address'] ?? '-' }}</dd>
                </div>
                <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="font-semibold text-slate-500">Nomor WhatsApp Usaha</dt>
                    <dd class="mt-1 font-mono font-bold text-slate-900 sm:col-span-2 sm:mt-0">{{ $settings['store_phone'] ?? '-' }}</dd>
                </div>
            </dl>
        </x-card>

        <x-card title="Informasi Rekening Pembayaran (Nota)">
            <dl class="divide-y divide-slate-100 text-xs">
                <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="font-semibold text-slate-500">Bank</dt>
                    <dd class="mt-1 font-bold text-slate-900 sm:col-span-2 sm:mt-0">{{ $settings['bank_name'] ?? '-' }}</dd>
                </div>
                <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="font-semibold text-slate-500">Nomor Rekening</dt>
                    <dd class="mt-1 font-mono font-bold text-amber-900 sm:col-span-2 sm:mt-0">{{ $settings['bank_account_number'] ?? '-' }}</dd>
                </div>
                <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="font-semibold text-slate-500">Atas Nama</dt>
                    <dd class="mt-1 font-semibold text-slate-900 sm:col-span-2 sm:mt-0">{{ $settings['bank_account_name'] ?? '-' }}</dd>
                </div>
            </dl>
        </x-card>
    </div>
</div>
