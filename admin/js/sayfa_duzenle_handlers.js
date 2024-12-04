// Yeni Bölüm Ekleme Form Handler
function bolumEkleSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const baslik = form.bolum_baslik.value;
    const tip = form.bolum_tip.value;

    $.ajax({
        url: 'sayfa_duzenle.php',
        method: 'POST',
        data: {
            islem: 'bolum_ekle',
            baslik: baslik,
            tip: tip
        },
        success: function(response) {
            if(response.success) {
                location.reload();
            } else {
                alert('Bölüm eklenirken bir hata oluştu!');
            }
        }
    });
}

// Bölüm Güncelleme Form Handler
function bolumGuncelleSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const id = formData.get('bolum_id');
    
    // TinyMCE içeriğini al (eğer varsa)
    if(tinymce.activeEditor) {
        formData.set('icerik', tinymce.activeEditor.getContent());
    }
    
    // FormData'yı object'e çevir
    const ayarlar = {};
    formData.forEach((value, key) => {
        if(key !== 'bolum_id') {
            ayarlar[key] = value;
        }
    });

    $.ajax({
        url: 'sayfa_duzenle.php',
        method: 'POST',
        data: {
            islem: 'bolum_guncelle',
            id: id,
            ayarlar: JSON.stringify(ayarlar)
        },
        success: function(response) {
            if(response.success) {
                location.reload();
            } else {
                alert('Bölüm güncellenirken bir hata oluştu!');
            }
        }
    });
}

// Modal Kapama Handler
function modalKapat(modalId) {
    // TinyMCE editörünü temizle
    if(tinymce.activeEditor) {
        tinymce.activeEditor.remove();
    }
    
    document.getElementById(modalId).classList.add('hidden');
}

// Sayfa Yüklendiğinde
document.addEventListener('DOMContentLoaded', function() {
    // Modal dışı tıklama ile kapatma
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
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
});