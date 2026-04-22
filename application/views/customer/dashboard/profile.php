<?= $this->load->view('customer/layout/header', ['title' => $title], TRUE) ?>

<div class="mb-8 max-w-2xl mx-auto">
    <div class="mb-8">
        <h2 class="font-display font-black text-2xl text-gray-900">Profil Saya</h2>
        <p class="text-sm text-gray-500">Kelola data informasi diri Anda untuk mempermudah proses pengiriman.</p>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-8">
            <form action="<?= site_url('customer/dashboard/profile') ?>" method="post" class="space-y-6">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <span class="iconify absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" data-icon="lucide:user"></span>
                        <input type="text" name="nama" value="<?= set_value('nama', $customer->nama) ?>" class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-primary-500 focus:bg-white outline-none transition" placeholder="Masukkan nama lengkap">
                    </div>
                    <?= form_error('nama', '<p class="text-red-500 text-xs mt-1">', '</p>') ?>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nomor WhatsApp</label>
                    <div class="relative">
                        <span class="iconify absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" data-icon="lucide:phone"></span>
                        <input type="text" name="no_hp" value="<?= set_value('no_hp', $customer->no_hp) ?>" class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-primary-500 focus:bg-white outline-none transition" placeholder="Masukkan nomor WhatsApp">
                    </div>
                    <?= form_error('no_hp', '<p class="text-red-500 text-xs mt-1">', '</p>') ?>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap</label>
                    <textarea name="alamat" rows="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-primary-500 focus:bg-white outline-none transition" placeholder="Tuliskan alamat pengiriman utama Anda"><?= set_value('alamat', $customer->alamat) ?></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-primary-600 text-white font-black py-4 rounded-2xl hover:bg-primary-700 transition shadow-lg shadow-primary-200 flex items-center justify-center gap-2">
                        Simpan Perubahan
                    </button>
                    <p class="text-center text-[10px] text-gray-400 mt-4 italic">Nasaktion Fruit menjamin kerahasiaan data pribadi Anda.</p>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Summary in Profile -->
    <div class="grid grid-cols-2 gap-4 mt-8">
        <div class="bg-primary-50 border border-primary-100 rounded-2xl p-4">
            <p class="text-xs text-primary-600 font-bold mb-1">Point Loyalitas</p>
            <p class="text-xl font-display font-black text-primary-700"><?= number_format($customer->point_loyalitas, 0, ',', '.') ?> <span class="text-xs font-medium">pts</span></p>
        </div>
        <div class="bg-purple-50 border border-purple-100 rounded-2xl p-4">
            <p class="text-xs text-purple-600 font-bold mb-1">Status Member</p>
            <p class="text-xl font-display font-black text-purple-700"><?= $customer->segment ?></p>
        </div>
    </div>
</div>

<?= $this->load->view('customer/layout/footer', [], TRUE) ?>
