// Her bölüm tipi için form HTML'i oluşturan fonksiyon
function getBolumForm(tip, data) {
    let html = `<input type="hidden" name="bolum_id" value="${data.id}">`;
    
    switch(tip) {
        case 'hero':
            html += getHeroForm(data);
            break;
        case 'metin':
            html += getMetinForm(data);
            break;
        case 'hizmetler':
            html += getHizmetlerForm(data);
            break;
        case 'galeri':
            html += getGaleriForm(data);
            break;
    }
    
    html += getFormFooter();
    return html;
}

// Hero Bölümü Formu
function getHeroForm(data) {
    return `
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Başlık</label>
                <input type="text" name="baslik" value="${data.ayarlar.baslik || ''}"
                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Açıklama</label>
                <textarea name="aciklama" rows="3"
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white">${data.ayarlar.aciklama || ''}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Buton Metni</label>
                <input type="text" name="buton_text" value="${data.ayarlar.buton_text || ''}"
                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Buton Linki</label>
                <input type="text" name="buton_link" value="${data.ayarlar.buton_link || ''}"
                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white">
            </div>
        </div>
    `;
}

// Metin Bölümü Formu
function getMetinForm(data) {
    return `
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Başlık</label>
                <input type="text" name="baslik" value="${data.ayarlar.baslik || ''}"
                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">İçerik</label>
                <textarea name="icerik" class="tinymce">${data.ayarlar.icerik || ''}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Arka Plan</label>
                <select name="arka_plan"
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white">
                    <option value="acik" ${data.ayarlar.arka_plan === 'acik' ? 'selected' : ''}>Açık</option>
                    <option value="koyu" ${data.ayarlar.arka_plan === 'koyu' ? 'selected' : ''}>Koyu</option>
                </select>
            </div>
        </div>
    `;
}

// Hizmetler Bölümü Formu
function getHizmetlerForm(data) {
    return `
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Başlık</label>
                <input type="text" name="baslik" value="${data.ayarlar.baslik || ''}"
                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Açıklama</label>
                <textarea name="aciklama" rows="2"
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white">${data.ayarlar.aciklama || ''}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Gösterilecek Hizmet Sayısı</label>
                <input type="number" name="goruntulenecek_sayi" value="${data.ayarlar.goruntulenecek_sayi || 3}"
                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Sıralama</label>
                <select name="siralama"
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white">
                    <option value="yeni" ${data.ayarlar.siralama === 'yeni' ? 'selected' : ''}>Yeni Eklenenler</option>
                    <option value="eski" ${data.ayarlar.siralama === 'eski' ? 'selected' : ''}>Eski Eklenenler</option>
                    <option value="rastgele" ${data.ayarlar.siralama === 'rastgele' ? 'selected' : ''}>Rastgele</option>
                </select>
            </div>
        </div>
    `;
}

// Galeri Bölümü Formu
function getGaleriForm(data) {
    return `
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Başlık</label>
                <input type="text" name="baslik" value="${data.ayarlar.baslik || ''}"
                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Açıklama</label>
                <textarea name="aciklama" rows="2"
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white">${data.ayarlar.aciklama || ''}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Gösterilecek Fotoğraf Sayısı</label>
                <input type="number" name="goruntulenecek_sayi" value="${data.ayarlar.goruntulenecek_sayi || 6}"
                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Kategori</label>
                <select name="kategori"
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white">
                    <option value="hepsi" ${data.ayarlar.kategori === 'hepsi' ? 'selected' : ''}>Tüm Kategoriler</option>
                    <option value="el_bakimi" ${data.ayarlar.kategori === 'el_bakimi' ? 'selected' : ''}>El Bakımı</option>
                    <option value="ayak_bakimi" ${data.ayarlar.kategori === 'ayak_bakimi' ? 'selected' : ''}>Ayak Bakımı</option>
                    <option value="protez_tirnak" ${data.ayarlar.kategori === 'protez_tirnak' ? 'selected' : ''}>Protez Tırnak</option>
                    <option value="kalici_oje" ${data.ayarlar.kategori === 'kalici_oje' ? 'selected' : ''}>Kalıcı Oje</option>
                </select>
            </div>
        </div>
    `;
}

// Form Footer (Kaydet/İptal Butonları)
function getFormFooter() {
    return `
        <div class="flex justify-end space-x-3 mt-6">
            <button type="button" 
                    onclick="document.getElementById('duzenleModal').classList.add('hidden')"
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-md">
                İptal
            </button>
            <button type="submit"
                    class="px-4 py-2 bg-purple-600 hover:bg-purple-500 rounded-md">
                Değişiklikleri Kaydet
            </button>
        </div>
    `;
}