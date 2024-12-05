// İlk olarak sayfa yüklendiğinde bir test mesajı görelim
$(document).ready(function() {
    console.log('Sayfa yüklendi ve script çalışıyor');

    $("#bolumlerListesi").sortable({
        handle: ".handle"
    });

    loadBolumler();
});

function modalKapat(modalId) {
    console.log('Modal kapatılıyor:', modalId);
    document.getElementById(modalId).classList.add('hidden');
}

function yeniBolumEkle() {
    console.log('Yeni bölüm ekleme modalı açılıyor');
    document.getElementById('yeniBolumModal').classList.remove('hidden');
}

function bolumEkleSubmit(event) {
    event.preventDefault();
    console.log('Form gönderiliyor...');

    const form = event.target;
    const formData = new FormData(form);

    // Form verilerini kontrol edelim
    console.log('Form verileri:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

    const data = {
        islem: 'bolum_ekle',
        baslik: formData.get('baslik'),
        icerik: formData.get('icerik'),
        arka_plan: formData.get('arka_plan')
    };

    console.log('Ajax ile gönderilecek veri:', data);

    $.ajax({
        url: window.location.href,
        method: 'POST',
        data: data,
        success: function(response) {
            console.log('Sunucu yanıtı:', response);
            if(response.success) {
                console.log('İşlem başarılı, modal kapatılıyor');
                modalKapat('yeniBolumModal');
                loadBolumler();
                showNotification('Bölüm başarıyla eklendi');
                form.reset();
            } else {
                console.error('Sunucu hatası:', response.message);
                showNotification(response.message || 'Bir hata oluştu', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Ajax hatası:', {xhr, status, error});
            showNotification('Bağlantı hatası: ' + error, 'error');
        }
    });
}

function loadBolumler() {
    console.log('Bölümler yükleniyor...');
    $.ajax({
        url: window.location.href,
        method: 'POST',
        data: { islem: 'bolumleri_getir' },
        success: function(response) {
            console.log('Bölümler yüklendi:', response);
            if(response.success) {
                let html = '';
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
                        <p class="text-sm text-gray-400">Arka Plan: ${bolum.arka_plan || 'Normal'}</p>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function showNotification(message, type = 'success') {
    console.log('Bildirim gösteriliyor:', message, type);
    const notification = $('<div>')
        .addClass(`fixed bottom-4 right-4 p-4 rounded-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`)
        .text(message);
    
    $('body').append(notification);
    setTimeout(() => notification.fadeOut(() => notification.remove()), 3000);
}