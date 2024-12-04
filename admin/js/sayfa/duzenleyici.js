$(document).ready(function() {
    // Sürükle & Bırak özelliği
    $("#bolumlerListesi").sortable({
        handle: ".handle",
        update: function(event, ui) {
            var siralama = {};
            $("#bolumlerListesi .bolum").each(function(index) {
                siralama[$(this).data('id')] = index;
            });
            
            $.ajax({
                url: window.location.href,
                method: 'POST',
                data: {
                    islem: 'siralama_guncelle',
                    siralama: siralama
                },
                success: function(response) {
                    if(response.success) {
                        showNotification('Sıralama güncellendi', 'success');
                    }
                }
            });
        }
    });

    // Sayfa yüklendiğinde bölümleri getir
    loadBolumler();
});

// Modal İşlemleri
function modalKapat(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function yeniBolumEkle() {
    document.getElementById('yeniBolumModal').classList.remove('hidden');
}

// Bölüm İşlemleri
function bolumEkleSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    $.ajax({
        url: window.location.href,
        method: 'POST',
        data: {
            islem: 'bolum_ekle',
            sayfa: formData.get('sayfa'),
            baslik: formData.get('baslik'),
            icerik: formData.get('icerik'),
            arka_plan: formData.get('arka_plan')
        },
        success: function(response) {
            console.log('Sunucu yanıtı:', response);
            if(response.success) {
                modalKapat('yeniBolumModal');
                loadBolumler();
                showNotification('Bölüm başarıyla eklendi');
                form.reset();
            } else {
                showNotification(response.message || 'Bir hata oluştu', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX hatası:', error);
            showNotification('Sunucu hatası oluştu', 'error');
        }
    });
}

function loadBolumler() {
    $.ajax({
        url: window.location.href,
        method: 'POST',
        data: { islem: 'bolumleri_getir' },
        success: function(response) {
            if(response.success) {
                var html = '';
                response.bolumler.forEach(function(bolum) {
                    html += getBolumHTML(bolum);
                });
                $("#bolumlerListesi").html(html);
            }
        }
    });
}

function getBolumHTML(bolum) {
    return `
        <div class="bolum bg-gray-800 rounded-lg p-4" data-id="${bolum.id}">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="handle cursor-move text-gray-500 hover:text-gray-400">
                        <i class="fas fa-grip-vertical"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">${bolum.baslik}</h3>
                        <p class="text-sm text-gray-400">${bolum.sayfa} - ${bolum.arka_plan || 'Normal'}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button onclick="bolumSil(${bolum.id})" class="text-red-500 hover:text-red-400">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}

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
                loadBolumler();
                showNotification('Bölüm silindi');
            }
        }
    });
}

// Bildirim İşlemleri
function showNotification(message, type = 'success') {
    const notification = $('<div>')
        .addClass(`fixed bottom-4 right-4 p-4 rounded-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`)
        .text(message);
    
    $('body').append(notification);
    setTimeout(() => notification.fadeOut(() => notification.remove()), 3000);
}