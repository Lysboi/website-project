<?php

// Sayfa bölümleri ile ilgili fonksiyonlar
class SayfaYoneticisi {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Yeni bölüm ekleme
    public function bolumEkle($baslik, $tip, $ayarlar = []) {
        try {
            $stmt = $this->db->prepare("INSERT INTO sayfa_bolumleri (baslik, tip, ayarlar) VALUES (?, ?, ?)");
            $stmt->execute([$baslik, $tip, json_encode($ayarlar)]);
            return ['success' => true, 'message' => 'Bölüm başarıyla eklendi'];
        } catch(PDOException $e) {
            return ['success' => false, 'message' => 'Hata: ' . $e->getMessage()];
        }
    }

    // Bölüm düzenleme
    public function bolumDuzenle($id, $ayarlar) {
        try {
            $stmt = $this->db->prepare("UPDATE sayfa_bolumleri SET ayarlar = ? WHERE id = ?");
            $stmt->execute([json_encode($ayarlar), $id]);
            return ['success' => true, 'message' => 'Bölüm başarıyla güncellendi'];
        } catch(PDOException $e) {
            return ['success' => false, 'message' => 'Hata: ' . $e->getMessage()];
        }
    }

    // Bölüm silme
    public function bolumSil($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM sayfa_bolumleri WHERE id = ?");
            $stmt->execute([$id]);
            return ['success' => true, 'message' => 'Bölüm başarıyla silindi'];
        } catch(PDOException $e) {
            return ['success' => false, 'message' => 'Hata: ' . $e->getMessage()];
        }
    }

    // Bölüm sıralama
    public function siralama($siralar) {
        try {
            foreach($siralar as $id => $sira) {
                $stmt = $this->db->prepare("UPDATE sayfa_bolumleri SET sira = ? WHERE id = ?");
                $stmt->execute([$sira, $id]);
            }
            return ['success' => true, 'message' => 'Sıralama güncellendi'];
        } catch(PDOException $e) {
            return ['success' => false, 'message' => 'Hata: ' . $e->getMessage()];
        }
    }

    // Bölüm durumunu değiştirme
    public function durumDegistir($id, $aktif) {
        try {
            $stmt = $this->db->prepare("UPDATE sayfa_bolumleri SET aktif = ? WHERE id = ?");
            $stmt->execute([$aktif, $id]);
            return ['success' => true, 'message' => 'Durum güncellendi'];
        } catch(PDOException $e) {
            return ['success' => false, 'message' => 'Hata: ' . $e->getMessage()];
        }
    }

    // Tüm bölümleri getir
    public function tumBolumleriGetir() {
        try {
            $stmt = $this->db->query("SELECT * FROM sayfa_bolumleri ORDER BY sira ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }

    // Tek bir bölümü getir
    public function bolumGetir($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM sayfa_bolumleri WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return null;
        }
    }

    // Varsayılan ayarları oluştur
    public function varsayilanAyarlar($tip) {
        switch($tip) {
            case 'hero':
                return [
                    'baslik' => 'Yeni Başlık',
                    'aciklama' => 'Açıklama metni',
                    'buton_text' => 'Buton Metni',
                    'buton_link' => '#'
                ];
            case 'metin':
                return [
                    'baslik' => 'Yeni Metin Bölümü',
                    'icerik' => 'İçerik metni buraya gelecek',
                    'arka_plan' => 'acik'
                ];
            case 'hizmetler':
                return [
                    'baslik' => 'Hizmetlerimiz',
                    'aciklama' => 'Hizmetler açıklaması',
                    'goruntulenecek_sayi' => 3,
                    'siralama' => 'yeni'
                ];
            case 'galeri':
                return [
                    'baslik' => 'Galeri',
                    'aciklama' => 'Galeri açıklaması',
                    'goruntulenecek_sayi' => 6,
                    'kategori' => 'hepsi'
                ];
            default:
                return [];
        }
    }
}

// Güvenlik fonksiyonları
class Guvenlik {
    // XSS temizleme
    public static function temizle($data) {
        if(is_array($data)) {
            foreach($data as $key => $value) {
                $data[$key] = self::temizle($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }

    // Token oluşturma
    public static function tokenOlustur() {
        if(!isset($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['token'];
    }

    // Token doğrulama
    public static function tokenDogrula($token) {
        if(!isset($_SESSION['token']) || $token !== $_SESSION['token']) {
            return false;
        }
        return true;
    }
}

// Sayfa yöneticisi örneği oluştur
$sayfaYoneticisi = new SayfaYoneticisi($conn);
?>