<?php
require_once '../includes/header.php';
$currentPage = 'sayfa_duzenle';

// AJAX işlemleri için
if(isset($_POST['islem'])) {
    header('Content-Type: application/json');
    $response = ['success' => false, 'message' => ''];
    
    switch($_POST['islem']) {
        // AJAX işlemleri buraya gelecek
    }
    
    echo json_encode($response);
    exit;
}

// Ana içerik
require_once '../includes/sidebar.php';
?>

<!-- Ana İçerik Alanı -->
<div class="flex-1 p-10">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold">Sayfa Düzenle</h2>
        <button onclick="yeniBolumEkle()" 
                class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-md text-white flex items-center">
            <i class="fas fa-plus mr-2"></i> Yeni Bölüm Ekle
        </button>
    </div>

    <!-- Bölümler Listesi -->
    <div class="space-y-4" id="bolumlerListesi">
        <!-- Bölümler JavaScript ile doldurulacak -->
    </div>
</div>

<!-- Modal ve diğer içerikler buraya gelecek -->

<?php require_once '../includes/footer.php'; ?>