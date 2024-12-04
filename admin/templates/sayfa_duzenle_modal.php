<!-- Yeni Bölüm Modal -->
<div id="yeniBolumModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
    <div class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
        <div class="bg-gray-800 rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Yeni Bölüm Ekle</h3>
                <button onclick="modalKapat('yeniBolumModal')" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="yeniBolumForm" onsubmit="return bolumEkleSubmit(event)" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Sayfa</label>
                    <select name="sayfa" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white">
                        <option value="ana_sayfa">Ana Sayfa</option>
                        <option value="hizmetler">Hizmetler</option>
                        <option value="hakkimizda">Hakkımızda</option>
                        <option value="iletisim">İletişim</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Başlık</label>
                    <input type="text" name="baslik" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">İçerik</label>
                    <textarea name="icerik" required rows="4" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Stil</label>
                    <select name="stil" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white">
                        <option value="normal">Normal Bölüm</option>
                        <option value="tam_ekran">Tam Ekran Bölüm</option>
                        <option value="iki_kolon">İki Kolonlu Bölüm</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Arka Plan</label>
                    <select name="arka_plan" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white">
                        <option value="acik">Açık Tema</option>
                        <option value="koyu">Koyu Tema</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="modalKapat('yeniBolumModal')"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-md">İptal</button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-500 rounded-md">Ekle</button>
                </div>
            </form>
        </div>
    </div>
</div>