        </div> <!-- flex container end -->

        <!-- Genel Bildirim Modal -->
        <div id="bildirimModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 p-6 rounded-lg shadow-xl">
                <div class="text-center mb-4" id="bildirimIcerik"></div>
                <div class="text-center">
                    <button onclick="document.getElementById('bildirimModal').classList.add('hidden')" 
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-md">
                        Tamam
                    </button>
                </div>
            </div>
        </div>

        <script>
        // Genel bildirim fonksiyonu
        function bildirim(mesaj, tur = 'success') {
            const modal = document.getElementById('bildirimModal');
            const icerik = document.getElementById('bildirimIcerik');
            
            // Mesaj türüne göre stil ayarla
            let stil = '';
            if(tur === 'success') {
                stil = 'text-green-500';
            } else if(tur === 'error') {
                stil = 'text-red-500';
            }
            
            icerik.innerHTML = `<p class="${stil}">${mesaj}</p>`;
            modal.classList.remove('hidden');
        }

        // ESC tuşu ile modal kapatma
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modals = document.querySelectorAll('.fixed.inset-0');
                modals.forEach(modal => {
                    if (!modal.classList.contains('hidden')) {
                        modal.classList.add('hidden');
                    }
                });
            }
        });

        // Modal dışına tıklama ile kapatma
        document.addEventListener('click', function(e) {
            const modals = document.querySelectorAll('.fixed.inset-0');
            modals.forEach(modal => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
        </script>
    </body>
</html>