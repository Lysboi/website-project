<!-- Sidebar -->
<div class="w-64 bg-gray-800">
    <nav class="mt-5">
        <a href="../admin/panel.php" class="flex items-center px-6 py-3 text-white hover:bg-gray-700 <?php echo $currentPage == 'panel' ? 'bg-gray-700' : ''; ?>">
            <i class="fas fa-home mr-3"></i>
            Ana Sayfa
        </a>
        <a href="../admin/pages/sayfa_duzenle.php" class="flex items-center px-6 py-3 text-white hover:bg-gray-700 <?php echo $currentPage == 'sayfa_duzenle' ? 'bg-gray-700' : ''; ?>">
            <i class="fas fa-edit mr-3"></i>
            Sayfa Düzenle
        </a>
        <a href="../admin/pages/hizmetler.php" class="flex items-center px-6 py-3 text-white hover:bg-gray-700 <?php echo $currentPage == 'hizmetler' ? 'bg-gray-700' : ''; ?>">
            <i class="fas fa-concierge-bell mr-3"></i>
            Hizmetler
        </a>
        <a href="../admin/pages/galeri.php" class="flex items-center px-6 py-3 text-white hover:bg-gray-700 <?php echo $currentPage == 'galeri' ? 'bg-gray-700' : ''; ?>">
            <i class="fas fa-images mr-3"></i>
            Galeri
        </a>
        <a href="../admin/pages/iletisim.php" class="flex items-center px-6 py-3 text-white hover:bg-gray-700 <?php echo $currentPage == 'iletisim' ? 'bg-gray-700' : ''; ?>">
            <i class="fas fa-address-book mr-3"></i>
            İletişim Bilgileri
        </a>
        <a href="../admin/pages/ayarlar.php" class="flex items-center px-6 py-3 text-white hover:bg-gray-700 <?php echo $currentPage == 'ayarlar' ? 'bg-gray-700' : ''; ?>">
            <i class="fas fa-cog mr-3"></i>
            Site Ayarları
        </a>
    </nav>
</div>