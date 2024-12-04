// Form şablonlarını oluşturan fonksiyonlar
function getBolumForm(data = null) {
    let html = '';
    
    if(data) {
        html += `<input type="hidden" name="bolum_id" value="${data.id}">`;
    }

    html += `
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Başlık</label>
                <input type="text" name="baslik" required
                       value="${data ? data.baslik : ''}"
                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Görünürlük</label>
                <div class="flex items-center space-x-2">
                    <label class="flex items-center">
                        <input type="radio" name="tur" value="normal" 
                               ${!data || data.tur === 'normal' ? 'checked' : ''}
                               class="mr-2">
                        Normal Bölüm
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="tur" value="tam_ekran"
                               ${data && data.tur === 'tam_ekran' ? 'checked' : ''}
                               class="mr-2">
                        Tam Ekran
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">İçerik</label>
                <textarea name="icerik" class="tinymce">${data ? data.icerik : ''}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Arka Plan Rengi</label>
                <select name="arka_plan" 
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500">
                    <option value="koyu" ${data && data.arka_plan === 'koyu' ? 'selected' : ''}>Koyu Tema</option>
                    <option value="acik" ${data && data.arka_plan === 'acik' ? 'selected' : ''}>Açık Tema</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Buton Ekle</label>
                <div class="space-y-2">
                    <input type="text" name="buton_metin" placeholder="Buton Metni"
                           value="${data ? data.buton_metin || '' : ''}"
                           class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500">
                    <input type="text" name="buton_link" placeholder="Buton Linki (örn: /iletisim)"
                           value="${data ? data.buton_link || '' : ''}"
                           class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:border-purple-500">
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6">
            <button type="button" onclick="modalKapat('duzenleModal')"
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-md">
                İptal
            </button>
            <button type="submit"
                    class="px-4 py-2 bg-purple-600 hover:bg-purple-500 rounded-md">
                ${data ? 'Güncelle' : 'Ekle'}
            </button>
        </div>
    `;

    return html;
}

// Yeni bölüm modalı içeriği
function getYeniBolumForm() {
    return `
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">Yeni Bölüm Ekle</h3>
            <button onclick="modalKapat('yeniBolumModal')" 
                    class="text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form onsubmit="return bolumEkleSubmit(event)" class="space-y-4">
            ${getBolumForm()}
        </form>
    `;
}

// Modal kapatma fonksiyonu
function modalKapat(modalId) {
    // TinyMCE'yi temizle
    if(tinymce.activeEditor) {
        tinymce.activeEditor.remove();
    }
    
    document.getElementById(modalId).classList.add('hidden');
}