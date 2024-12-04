// Sürükle & Bırak Sıralama
$(document).ready(function() {
    $("#bolumlerListesi").sortable({
        handle: ".fa-grip-vertical",
        update: function(event, ui) {
            const siralar = {};
            $("#bolumlerListesi > div").each(function(index) {
                siralar[$(this).data('id')] = index;
            });
            
            $.ajax({
                url: 'sayfa_duzenle.php',
                method: 'POST',
                data: {
                    islem: 'siralama_guncelle',
                    siralar: JSON.stringify(siralar)
                },
                success: function(response) {
                    if(!response.success) {
                        alert('Sıralama güncellenirken bir hata oluştu!');
                    }
                }
            });
        }
    });
});

// Yeni Bölüm Ekleme
function yeniBolumEkle() {
    document.getElementById('yeniBolumModal').classList.remove('hidden');
}

// Bölüm Silme
function bolumSil(id) {
    if(!confirm('Bu bölümü silmek istediğinizden emin misiniz?')) return;

    $.ajax({
        url: 'sayfa_duzenle.php',
        method: 'POST',
        data: {
            islem: 'bolum_sil',
            id: id
        },
        success: function(response) {
            if(response.success) {
                location.reload();
            } else {
                alert('Bölüm silinirken bir hata oluştu!');
            }
        }
    });
}

// Durum Değiştirme
function durumDegistir(id, aktif) {
    $.ajax({
        url: 'sayfa_duzenle.php',
        method: 'POST',
        data: {
            islem: 'durum_degistir',
            id: id,
            aktif: aktif ? 1 : 0
        },
        success: function(response) {
            if(response.success) {
                location.reload();
            } else {
                alert('Durum güncellenirken bir hata oluştu!');
            }
        }
    });
}

// Bölüm Düzenleme
function bolumDuzenle(id, tip) {
    $.ajax({
        url: 'sayfa_duzenle.php',
        method: 'POST',
        data: {
            islem: 'bolum_bilgi_getir',
            id: id
        },
        success: function(response) {
            if(response.success) {
                const form = document.getElementById('duzenleForm');
                form.innerHTML = getBolumForm(tip, response.data);
                document.getElementById('duzenleModal').classList.remove('hidden');
                
                // TinyMCE editörünü başlat
                if(document.querySelector('#duzenleForm .tinymce')) {
                    tinymce.init({
                        selector: '#duzenleForm .tinymce',
                        height: 300,
                        menubar: false,
                        plugins: [
                            'advlist', 'autolink', 'lists', 'link', 'charmap', 'preview',
                            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                            'insertdatetime', 'media', 'table', 'help', 'wordcount'
                        ],
                        toolbar: 'undo redo | blocks | bold italic backcolor | alignleft aligncenter ' +
                            'alignright alignjustify | bullist numlist outdent indent | removeformat | help',
                        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }'
                    });
                }
            }
        }
    });
}