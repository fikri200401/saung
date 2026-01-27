<!DOCTYPE html>
<html>
<head>
    <title>Test Images</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .section { margin-bottom: 30px; padding: 20px; background: #f5f5f5; border-radius: 8px; }
        h2 { color: #333; }
        .image-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; margin-top: 15px; }
        .image-card { background: white; padding: 10px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .image-card img { width: 100%; height: 150px; object-fit: cover; border-radius: 4px; }
        .image-card p { margin: 10px 0 0 0; font-size: 12px; color: #666; word-break: break-all; }
        .status { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; }
        .status.ok { background: #4caf50; color: white; }
        .status.error { background: #f44336; color: white; }
    </style>
</head>
<body>
    <h1>Test Gambar - Saung Restaurant</h1>
    
    <?php
    function checkImage($path) {
        $fullPath = __DIR__ . '/storage/' . $path;
        return file_exists($fullPath) ? 'ok' : 'error';
    }
    
    function getImageFiles($dir) {
        $path = __DIR__ . '/storage/' . $dir;
        if (!is_dir($path)) return [];
        $files = scandir($path);
        return array_filter($files, function($file) use ($path) {
            return is_file($path . '/' . $file) && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
        });
    }
    ?>
    
    <!-- Saung Images -->
    <div class="section">
        <h2>Gambar Saung</h2>
        <div class="image-grid">
            <?php
            $saungImages = getImageFiles('saungs');
            if (empty($saungImages)) {
                echo '<p>Tidak ada gambar saung</p>';
            } else {
                foreach ($saungImages as $img) {
                    $path = 'saungs/' . $img;
                    $status = checkImage($path);
                    echo '<div class="image-card">';
                    echo '<img src="/storage/' . $path . '" alt="Saung" onerror="this.src=\'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'200\' height=\'150\'%3E%3Crect fill=\'%23ddd\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23999\'%3EError%3C/text%3E%3C/svg%3E\'">';
                    echo '<p><span class="status ' . $status . '">' . strtoupper($status) . '</span><br>' . $img . '</p>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
    
    <!-- Menu Images -->
    <div class="section">
        <h2>Gambar Menu</h2>
        <div class="image-grid">
            <?php
            $menuImages = getImageFiles('menus');
            if (empty($menuImages)) {
                echo '<p>Tidak ada gambar menu</p>';
            } else {
                foreach ($menuImages as $img) {
                    $path = 'menus/' . $img;
                    $status = checkImage($path);
                    echo '<div class="image-card">';
                    echo '<img src="/storage/' . $path . '" alt="Menu" onerror="this.src=\'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'200\' height=\'150\'%3E%3Crect fill=\'%23ddd\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23999\'%3EError%3C/text%3E%3C/svg%3E\'">';
                    echo '<p><span class="status ' . $status . '">' . strtoupper($status) . '</span><br>' . $img . '</p>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
    
    <!-- Deposit Images -->
    <div class="section">
        <h2>Bukti Pembayaran Deposit</h2>
        <div class="image-grid">
            <?php
            $depositImages = getImageFiles('deposits');
            if (empty($depositImages)) {
                echo '<p>Tidak ada bukti pembayaran</p>';
            } else {
                foreach ($depositImages as $img) {
                    $path = 'deposits/' . $img;
                    $status = checkImage($path);
                    echo '<div class="image-card">';
                    echo '<img src="/storage/' . $path . '" alt="Deposit" onerror="this.src=\'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'200\' height=\'150\'%3E%3Crect fill=\'%23ddd\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23999\'%3EError%3C/text%3E%3C/svg%3E\'">';
                    echo '<p><span class="status ' . $status . '">' . strtoupper($status) . '</span><br>' . $img . '</p>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
    
    <!-- System Info -->
    <div class="section">
        <h2>Info Sistem</h2>
        <p><strong>Storage Link:</strong> <?php echo is_link(__DIR__ . '/storage') ? 'OK (Junction/Symlink)' : 'ERROR (Bukan symlink)'; ?></p>
        <p><strong>Storage Target:</strong> <?php echo is_link(__DIR__ . '/storage') ? readlink(__DIR__ . '/storage') : 'N/A'; ?></p>
        <p><strong>Storage Readable:</strong> <?php echo is_readable(__DIR__ . '/storage') ? 'YES' : 'NO'; ?></p>
        <p><strong>Saungs Folder:</strong> <?php echo is_dir(__DIR__ . '/storage/saungs') ? 'EXISTS' : 'NOT FOUND'; ?></p>
        <p><strong>Menus Folder:</strong> <?php echo is_dir(__DIR__ . '/storage/menus') ? 'EXISTS' : 'NOT FOUND'; ?></p>
        <p><strong>Deposits Folder:</strong> <?php echo is_dir(__DIR__ . '/storage/deposits') ? 'EXISTS' : 'NOT FOUND'; ?></p>
    </div>
</body>
</html>
