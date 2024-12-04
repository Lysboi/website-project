// Sürükle & Bırak işlemleri
$(document).ready(function() {
    $("#bolumlerListesi").sortable({
        handle: ".fa-grip-vertical",
        update: function(event, ui) {
            const siralar = {};
            $("#bolumlerListesi > div").each(function(index) {
                siralar[$(this).data('id')] = index;
            });
            
            $.ajax({
                url: window.location.href,
                method: 'POST',
                data: {
                    islem: 'siralama_guncelle',
                    siralar: JSON.stringify(siralar)
                },
                success: function(response) {
                    if(response.success) {
                        bildirim('Sıralama güncellendi', 'success');
                    } else {
                        bildirim('Sıralama güncellenirken bir hata oluştu', 'error');
                    }
                }
            });
        }
    });
});

// Yeni bölüm ekleme fonksiyonu
function yeniBolumEkle() {
    document.getElementById('yeniBolumModal').classList.remove('hidden');
}

// Bölüm düzenleme fonksiyonu
function bolumDuzenle(id) {
    $.ajax({
        url: window.location.href,
        method: 'POST',
        data: {
            islem: 'bolum_getir',
            id: id
        },
        success: function(response) {
            if(response.success) {
                // Formu hazırla ve modalı aç
                document.getElementById('duzenleForm').innerHTML = getBolumForm(response.data);
                document.getElementById('duzenleModal').classList.remove('hidden');
                
                // TinyMCE'yi başlat
                initTinyMCE();
            } else {
                bildirim('Bölüm bilgileri alınamadı', 'error');
            }
        }
    });
}

// Bölüm silme fonksiyonu
function bolumSil(id) {
    if(!confirm('Bu bölümü silmek istediğinizden emin misiniz?')) return;

    $.ajax({
        url: window.location.href,
        method: 'POST',
        data: {
            islem: 'bolum_sil',
            id: id
        },
        success: function(response) {
            if(response.success) {
                bildirim('Bölüm başarıyla silindi', 'success');
                location.reload();
            } else {
                bildirim('Bölüm silinirken bir hata oluştu', 'error');
            }
        }
    });
}

// Durum değiştirme fonksiyonu
function durumDegistir(id, aktif) {
    $.ajax({
        url: window.location.href,
        method: 'POST',
        data: {
            islem: 'durum_degistir',
            id: id,
            aktif: aktif ? 1 : 0
        },
        success: function(response) {
            if(response.success) {
                bildirim(aktif ? 'Bölüm aktifleştirildi' : 'Bölüm pasifleştirildi', 'success');
            } else {
                bildirim('Durum değiştirilirken bir hata oluştu', 'error');
            }
        }
    });
}

// TinyMCE başlatma fonksiyonu
function initTinyMCE() {
    tinymce.init({
        selector: '.tinymce',
        height: 400,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | formatselect | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }'
    });
}

// Bildirim gösterme fonksiyonu
function bildirim(mesaj, tur = 'success') {
    const bildirimDiv = document.createElement('div');
    bildirimDiv.className = `fixed bottom-4 right-4 px-6 py-3 rounded shadow-lg ${tur === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
    bildirimDiv.textContent = mesaj;
    document.body.appendChild(bildirimDiv);
    
    setTimeout(() => {
        bildirimDiv.remove();
    }, 3000);
}