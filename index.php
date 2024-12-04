<?php
require_once 'includes/config.php';

// Site ayarlarını çek
$stmt = $conn->query("SELECT * FROM ayarlar");
$ayarlar = [];
while($ayar = $stmt->fetch()) {
    $ayarlar[$ayar['anahtar']] = $ayar['deger'];
}

// Aktif hizmetleri çek
$stmt = $conn->prepare("SELECT * FROM hizmetler WHERE aktif = 1");
$stmt->execute();
$hizmetler = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($ayarlar['site_baslik'] ?? 'Sed Nail Art'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($ayarlar['site_aciklama'] ?? ''); ?>">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <h1>Sed Nail Art</h1>
            </div>
            <div class="nav-links">
                <a href="#" class="active">Ana Sayfa</a>
                <a href="#">Hizmetler</a>
                <a href="#">Galeri</a>
                <a href="#">Hakkımızda</a>
                <a href="#">İletişim</a>
            </div>
        </div>
    </nav>

    <header class="hero">
        <div class="hero-content">
            <h1><?php echo htmlspecialchars($ayarlar['ana_sayfa_baslik'] ?? 'Profesyonel Tırnak Tasarımında Yeni Bir Boyut'); ?></h1>
            <p><?php echo nl2br(htmlspecialchars($ayarlar['ana_sayfa_aciklama'] ?? 'Eşsiz tasarımlar, uzman kadro ve steril ortamda hayalinizdeki tırnaklara kavuşun')); ?></p>
            <a href="#" class="cta-button">Randevu Al</a>
        </div>
    </header>

    <section class="services">
        <h2>Öne Çıkan Hizmetlerimiz</h2>
        <div class="service-grid">
            <?php foreach($hizmetler as $hizmet): ?>
            <div class="service-card">
                <i class="<?php echo htmlspecialchars($hizmet['ikon']); ?>"></i>
                <h3><?php echo htmlspecialchars($hizmet['baslik']); ?></h3>
                <p><?php echo htmlspecialchars($hizmet['aciklama']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="about-preview">
        <div class="about-content">
            <h2>Neden Biz?</h2>
            <p>10 yılı aşkın deneyimimiz ve sürekli kendini yenileyen ekibimizle, sizlere en iyi hizmeti sunmak için buradayız. Steril ortamımız, profesyonel ekipmanlarımız ve size özel tasarımlarımızla fark yaratıyoruz.</p>
            <a href="#" class="learn-more">Daha Fazla Bilgi</a>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Sed Nail Art</h3>
                <p>Profesyonel tırnak tasarımında güvenilir adresiniz.</p>
            </div>
            <div class="footer-section">
                <h3>İletişim</h3>
                <?php
                // Aktif iletişim bilgilerini çek
                $stmt = $conn->prepare("SELECT * FROM iletisim_bilgileri WHERE aktif = 1");
                $stmt->execute();
                while($bilgi = $stmt->fetch()) {
                    $icon = '';
                    switch($bilgi['tip']) {
                        case 'telefon': $icon = 'fa-phone'; break;
                        case 'email': $icon = 'fa-envelope'; break;
                        case 'adres': $icon = 'fa-location-dot'; break;
                        case 'whatsapp': $icon = 'fa-whatsapp'; break;
                        default: $icon = 'fa-info';
                    }
                    echo '<p><i class="fas ' . $icon . '"></i> ' . htmlspecialchars($bilgi['deger']) . '</p>';
                }
                ?>
            </div>
            <div class="footer-section">
                <h3>Takip Edin</h3>
                <div class="social-links">
                    <?php if(!empty($ayarlar['instagram'])): ?>
                    <a href="https://instagram.com/<?php echo htmlspecialchars($ayarlar['instagram']); ?>" target="_blank">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <?php endif; ?>

                    <?php if(!empty($ayarlar['facebook'])): ?>
                    <a href="<?php echo htmlspecialchars($ayarlar['facebook']); ?>" target="_blank">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <?php endif; ?>

                    <?php if(!empty($ayarlar['tiktok'])): ?>
                    <a href="https://tiktok.com/@<?php echo htmlspecialchars($ayarlar['tiktok']); ?>" target="_blank">
                        <i class="fab fa-tiktok"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Sed Nail Art. Tüm hakları saklıdır.</p>
        </div>
    </footer>
</body>
</html>