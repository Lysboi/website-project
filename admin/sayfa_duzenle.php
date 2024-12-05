<?php
session_start();
require_once '../includes/config.php';

if(!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

if(isset($_POST['islem'])) {
    $response = ['success' => false, 'message' => ''];
    
    switch($_POST['islem']) {
        case 'bolum_ekle':
            try {
                $stmt = $conn->prepare("INSERT INTO sayfa_bolumleri (baslik, icerik, arka_plan) VALUES (?, ?, ?)");
                $result = $stmt->execute([
                    $_POST['baslik'],
                    $_POST['icerik'],
                    $_POST['arka_plan']
                ]);
                
                if($result) {
                    $response = ['success' => true, 'message' => 'Bölüm başarıyla eklendi'];
                } else {
                    $error = $stmt->errorInfo();
                    $response = ['success' => false, 'message' => 'DB Hata: ' . $error[2]];
                }
            } catch(PDOException $e) {
                $response = ['success' => false, 'message' => $e->getMessage()];
            }
            break;
            
        case 'bolumleri_getir':
            try {
                $stmt = $conn->query("SELECT * FROM sayfa_bolumleri ORDER BY sira ASC");
                $response = ['success' => true, 'bolumler' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
            } catch(PDOException $e) {
                $response = ['success' => false, 'message' => $e->getMessage()];
            }
            break;
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

require_once 'templates/sayfa_duzenle_html.php';
?>