// Yeni bölüm ekleme form handler
function bolumEkleSubmit(event) {
    event.preventDefault();
    
    // TinyMCE içeriğini al
    const icerik = tinymce.activeEditor.getContent();
    
    // Form verilerini topla
    const formData = new FormData(event.target);
    const data = {
        baslik: formData.get('baslik'),
        tur: formData.get('tur'),
        icerik: icerik,
        arka_plan: formData.get('arka_plan'),
        buton_metin: formData.get('buton_metin'),
        buton_link: formData.get('buton_link')
    };

    $.ajax({
        url: window.location.href,
        method: 'POST',
        data: {
            islem: 'bolum_ekle',
            data: JSON.stringify(data)
        },
        success: function(response) {
            if(response.success) {
                bildirim('Bölüm başarıyla eklendi', 'success');
                modalKapat('yeniBolumModal');
                location.reload();
            } else {
                bildirim('Bölüm eklenirken bir hata oluştu', 'error');
            }
        }
    });
}

// Bölüm güncelleme form handler
function bolumGuncelleSubmit(event) {
    event.preventDefault();
    
    // TinyMCE içeriğini al
    const icerik = tinymce.activeEditor.getContent();
    
    // Form verilerini topla
    const formData = new FormData(event.target);
    const data = {
        id: formData.get('bolum_id'),
        baslik: formData.get('baslik'),
        tur: formData.get('tur'),
        icerik: icerik,
        arka_plan: formData.get('arka_plan'),
        buton_metin: formData.get('buton_metin'),
        buton_link: formData.get('buton_link')
    };

    $.ajax({
        url: window.location.href,
        method: 'POST',
        data: {
            islem: 'bolum_guncelle',
            data: JSON.stringify(data)
        },
        success: function(response) {
            if(response.success) {
                bildirim('Bölüm başarıyla güncellendi', 'success');
                modalKapat('duzenleModal');
                location.reload();
            } else {
                bildirim('Bölüm güncellenirken bir hata oluştu', 'error');
            }
        }
    });
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Modal dışı tıklama ile kapatma
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                modalKapat(this.id);
            }
        });
    });

    // ESC tuşu ile modal kapatma
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const acikModal = document.querySelector('.modal:not(.hidden)');
            if (acikModal) {
                modalKapat(acikModal.id);
            }
        }
    });

    // TinyMCE başlatma
    if(document.querySelector('.tinymce')) {
        initTinyMCE();
    }
});

// Önizleme penceresi
function onizlemeGoster(id) {
    // Önizleme penceresini aç
    const onizlemePencere = window.open('', '_blank', 'width=1024,height=768');
    
    // Site stillerini ve içeriğini yükle
    $.ajax({
        url: window.location.href,
        method: 'POST',
        data: {
            islem: 'bolum_onizleme',
            id: id
        },
        success: function(response) {
            if(response.success) {
                onizlemePencere.document.write(response.html);
            } else {
                onizlemePencere.close();
                bildirim('Önizleme oluşturulurken bir hata oluştu', 'error');
            }
        }
    });
}