<?php
session_start();
require_once '../includes/config.php';

if(!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// Debug için
if(isset($_POST['islem'])) {
    error_log("Gelen POST verileri: " . print_r($_POST, true));
}

if(isset($_POST['islem'])) {
    $response = ['success' => false, 'message' => ''];
    
    switch($_POST['islem']) {
        case 'bolum_ekle':
            try {
                $query = "INSERT INTO sayfa_bolumleri (sayfa, baslik, icerik, arka_plan, aktif, sira) VALUES (?, ?, ?, ?, 1, 0)";
                error_log("SQL Sorgusu: " . $query);
                
                $stmt = $conn->prepare($query);
                $success = $stmt->execute([
                    $_POST['sayfa'],
                    $_POST['baslik'],
                    $_POST['icerik'],
                    $_POST['arka_plan']
                ]);
                
                if($success) {
                    $response = ['success' => true, 'message' => 'Bölüm başarıyla eklendi'];
                } else {
                    $response = ['success' => false, 'message' => 'Veritabanına eklenemedi'];
                    error_log("PDO Hata Bilgisi: " . print_r($stmt->errorInfo(), true));
                }
            } catch(PDOException $e) {
                $response = ['success' => false, 'message' => $e->getMessage()];
                error_log("PDO Hatası: " . $e->getMessage());
            }
            break;
            
        case 'bolumleri_getir':
            $stmt = $conn->query("SELECT * FROM sayfa_bolumleri ORDER BY sira ASC");
            $response = ['success' => true, 'bolumler' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
            break;

        case 'bolum_sil':
            $stmt = $conn->prepare("DELETE FROM sayfa_bolumleri WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $response = ['success' => true];
            break;
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

require_once 'templates/sayfa_duzenle_html.php';
?>