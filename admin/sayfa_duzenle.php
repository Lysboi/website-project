<?php
session_start();
require_once '../includes/config.php';

// Oturum kontrolü
if(!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// AJAX istekleri için işlemler
if(isset($_POST['islem'])) {
    $response = ['success' => false, 'message' => ''];
    
    switch($_POST['islem']) {
        case 'siralama_guncelle':
            $siralar = json_decode($_POST['siralar'], true);
            foreach($siralar as $id => $sira) {
                $stmt = $conn->prepare("UPDATE sayfa_bolumleri SET sira = ? WHERE id = ?");
                $stmt->execute([$sira, $id]);
            }
            $response = ['success' => true, 'message' => 'Sıralama güncellendi'];
            break;
            
        case 'bolum_ekle':
            $baslik = trim($_POST['baslik']);
            $tip = trim($_POST['tip']);
            $varsayilan_ayarlar = [];
            
            switch($tip) {
                case 'hero':
                    $varsayilan_ayarlar = [
                        'baslik' => 'Yeni Hero Başlığı',
                        'aciklama' => 'Hero açıklama metni',
                        'buton_text' => 'Randevu Al',
                        'buton_link' => '#'
                    ];
                    break;
                case 'metin':
                    $varsayilan_ayarlar = [
                        'baslik' => 'Yeni Metin Bölümü',
                        'icerik' => 'İçerik metni buraya gelecek',
                        'arka_plan' => 'acik' // açık/koyu
                    ];
                    break;
                case 'hizmetler':
                    $varsayilan_ayarlar = [
                        'baslik' => 'Hizmetlerimiz',
                        'aciklama' => 'Hizmetler bölüm açıklaması',
                        'goruntulenecek_sayi' => 3,
                        'siralama' => 'yeni' // yeni/eski/rastgele
                    ];
                    break;
                case 'galeri':
                    $varsayilan_ayarlar = [
                        'baslik' => 'Galeri',
                        'aciklama' => 'Galeri bölüm açıklaması',
                        'goruntulenecek_sayi' => 6,
                        'kategori' => 'hepsi'
                    ];
                    break;
            }
            
            $stmt = $conn->prepare("INSERT INTO sayfa_bolumleri (baslik, tip, ayarlar) VALUES (?, ?, ?)");
            $stmt->execute([$baslik, $tip, json_encode($varsayilan_ayarlar)]);
            $yeni_id = $conn->lastInsertId();
            
            $response = [
                'success' => true, 
                'message' => 'Bölüm eklendi',
                'id' => $yeni_id
            ];
            break;
            
        case 'bolum_sil':
            $id = (int)$_POST['id'];
            $stmt = $conn->prepare("DELETE FROM sayfa_bolumleri WHERE id = ?");
            $stmt->execute([$id]);
            $response = ['success' => true, 'message' => 'Bölüm silindi'];
            break;
            
        case 'bolum_guncelle':
            $id = (int)$_POST['id'];
            $ayarlar = json_decode($_POST['ayarlar'], true);
            $stmt = $conn->prepare("UPDATE sayfa_bolumleri SET ayarlar = ? WHERE id = ?");
            $stmt->execute([json_encode($ayarlar), $id]);
            $response = ['success' => true, 'message' => 'Bölüm güncellendi'];
            break;
            
        case 'durum_degistir':
            $id = (int)$_POST['id'];
            $aktif = (int)$_POST['aktif'];
            $stmt = $conn->prepare("UPDATE sayfa_bolumleri SET aktif = ? WHERE id = ?");
            $stmt->execute([$aktif, $id]);
            $response = ['success' => true, 'message' => 'Durum güncellendi'];
            break;
            
        case 'bolum_bilgi_getir':
            $id = (int)$_POST['id'];
            $stmt = $conn->prepare("SELECT * FROM sayfa_bolumleri WHERE id = ?");
            $stmt->execute([$id]);
            $bolum = $stmt->fetch();
            if($bolum) {
                $response = [
                    'success' => true,
                    'data' => [
                        'id' => $bolum['id'],
                        'baslik' => $bolum['baslik'],
                        'tip' => $bolum['tip'],
                        'ayarlar' => json_decode($bolum['ayarlar'], true)
                    ]
                ];
            }
            break;
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Mevcut bölümleri çek
$stmt = $conn->query("SELECT * FROM sayfa_bolumleri ORDER BY sira ASC");
$bolumler = $stmt->fetchAll();
?>