<?php
// Bu dosya sayfa_duzenle.php'nin sonuna dahil edilecek
?>

<!-- Yeni Bölüm Modal -->
<div id="yeniBolumModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-gray-800 p-8 rounded-lg w-full max-w-md">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">Yeni Bölüm Ekle</h3>
            <button onclick="document.getElementById('yeniBolumModal').classList.add('hidden')" 
                    class="text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form onsubmit="return bolumEkleSubmit(event)" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Bölüm İsmi</label>
                <input type="text" name="bolum_baslik" required
                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500"
                       placeholder="Örn: Ana Sayfa Başlığı, Hakkımızda Bölümü">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Bölüm Tipi</label>
                <select name="bolum_tip" required
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500">
                    <option value="hero">Tam Ekran Başlık</option>
                    <option value="metin">Metin Bölümü</option>
                    <option value="hizmetler">Hizmetler Bölümü</option>
                    <option value="galeri">Galeri Bölümü</option>
                </select>
                <p class="mt-1 text-sm text-gray-400" id="bolumAciklama"></p>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="document.getElementById('yeniBolumModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-md">
                    İptal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-purple-600 hover:bg-purple-500 rounded-md">
                    Bölüm Ekle
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Düzenleme Modal -->
<div id="duzenleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-gray-800 p-8 rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">Bölüm Düzenle</h3>
            <button onclick="document.getElementById('duzenleModal').classList.add('hidden')" 
                    class="text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="duzenleForm" onsubmit="return bolumGuncelleSubmit(event)" class="space-y-4">
            <!-- Form içeriği JavaScript ile doldurulacak -->
        </form>
    </div>
</div>

<!-- Modal JavaScript -->
<script>
// Bölüm tipi değiştiğinde açıklama güncelle
document.querySelector('select[name="bolum_tip"]').addEventListener('change', function() {
    const aciklama = document.getElementById('bolumAciklama');
    switch(this.value) {
        case 'hero':
            aciklama.textContent = 'Tam ekran başlık, açıklama ve çağrı butonu içeren bölüm.';
            break;
        case 'metin':
            aciklama.textContent = 'Özelleştirilebilir başlık ve zengin metin içeriği.';
            break;
        case 'hizmetler':
            aciklama.textContent = 'Belirlediğiniz sayıda hizmetin listelendiği bölüm.';
            break;
        case 'galeri':
            aciklama.textContent = 'Seçtiğiniz kategoriden fotoğrafların sergilendiği bölüm.';
            break;
    }
});

// Modal dışına tıklama ile kapatma
document.querySelectorAll('.fixed.inset-0').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            // TinyMCE editörünü temizle (eğer varsa)
            if(tinymce.activeEditor) {
                tinymce.activeEditor.remove();
            }
        }
    });
});
</script>