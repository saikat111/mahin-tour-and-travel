<?php
/**
 * Mahin Travel & Tours - Media Library & File Upload Manager
 */

require_once __DIR__ . '/includes/auth_check.php';

$pdo = getDB();

// Handle File Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['media_file'])) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Security validation failed.');
    } else {
        $file = $_FILES['media_file'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            setFlashMessage('error', 'Upload failed with error code: ' . $file['error']);
        } else {
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml'];
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
            
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($ext, $allowedExts) || !in_array($mime, $allowedMimes)) {
                setFlashMessage('error', 'Invalid file type. Only JPG, PNG, WEBP, and SVG images are allowed.');
            } elseif ($file['size'] > 8 * 1024 * 1024) {
                setFlashMessage('error', 'File size exceeds maximum limit of 8MB.');
            } else {
                $targetDir = ROOT_PATH . '/uploads';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                $cleanBase = slugify(pathinfo($file['name'], PATHINFO_FILENAME));
                $newFilename = $cleanBase . '-' . time() . '.' . $ext;
                $targetPath = $targetDir . '/' . $newFilename;
                $relativePath = 'uploads/' . $newFilename;

                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    $altText = cleanInput($_POST['alt_text'] ?? $cleanBase);
                    $stmt = $pdo->prepare("INSERT INTO media_library (file_name, file_path, file_size, mime_type, alt_text) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$newFilename, $relativePath, $file['size'], $mime, $altText]);
                    setFlashMessage('success', "Image successfully uploaded: {$newFilename}");
                } else {
                    setFlashMessage('error', 'Failed to move uploaded file to target directory.');
                }
            }
        }
        header("Location: " . ADMIN_URL . "/media.php");
        exit;
    }
}

// Handle Delete
$action = cleanInput($_GET['action'] ?? '');
$id = (int)($_GET['id'] ?? 0);
if ($action === 'delete' && $id > 0) {
    $stmt = $pdo->prepare("SELECT file_path FROM media_library WHERE id = ?");
    $stmt->execute([$id]);
    $media = $stmt->fetch();
    if ($media) {
        $realFile = ROOT_PATH . '/' . $media['file_path'];
        if (file_exists($realFile)) {
            @unlink($realFile);
        }
        $pdo->prepare("DELETE FROM media_library WHERE id = ?")->execute([$id]);
        setFlashMessage('success', 'Media file deleted.');
    }
    header("Location: " . ADMIN_URL . "/media.php");
    exit;
}

$mediaItems = $pdo->query("SELECT * FROM media_library ORDER BY uploaded_at DESC, id DESC")->fetchAll();

$activeAdminPage = 'media';
$adminPageTitle = 'Media Library';
require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="page-title-row">
    <div>
        <h1 class="page-title">Media Library & File Uploads</h1>
        <p class="page-subtitle">Upload destination photos, tour imagery, and promotional assets securely.</p>
    </div>
</div>

<!-- Upload Box -->
<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Upload New Image</h2>
    </div>

    <form action="<?= ADMIN_URL ?>/media.php" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="form-row">
            <div class="admin-form-group">
                <label>Select Image File (JPG, PNG, WEBP, SVG - Max 8MB) *</label>
                <input type="file" name="media_file" class="admin-input" accept="image/*" required>
            </div>

            <div class="admin-form-group">
                <label>Alt Text / Image Description</label>
                <input type="text" name="alt_text" class="admin-input" placeholder="e.g. Dubai Marina Tour Banner">
            </div>
        </div>

        <button type="submit" class="btn-admin btn-admin-gold">
            <?= renderIcon('plus', '', 14) ?>
            <span>Upload Image</span>
        </button>
    </form>
</div>

<!-- Media Gallery Grid -->
<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Uploaded Media Files (<?= count($mediaItems) ?>)</h2>
    </div>

    <?php if (empty($mediaItems)): ?>
        <p style="text-align: center; color: var(--admin-muted); padding: 3rem 0;">No media files uploaded yet. Upload images above to use across tours and pages.</p>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
            <?php foreach ($mediaItems as $m): ?>
                <div style="border: 1px solid var(--admin-border); border-radius: var(--radius); overflow: hidden; background: #FFFFFF; display: flex; flex-direction: column;">
                    <div style="height: 140px; background: #0A2240; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                        <img src="<?= url($m['file_path']) ?>" alt="<?= e($m['alt_text']) ?>" style="max-width: 100%; max-height: 100%; object-fit: cover;">
                    </div>
                    <div style="padding: 0.85rem; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; gap: 0.5rem;">
                        <div>
                            <span style="font-size: 0.8rem; font-weight: 600; color: var(--admin-navy); display: block; word-break: break-all;">
                                <?= e($m['file_name']) ?>
                            </span>
                            <span style="font-size: 0.72rem; color: var(--admin-muted);">
                                <?= round($m['file_size'] / 1024) ?> KB • <?= date('d M Y', strtotime($m['uploaded_at'])) ?>
                            </span>
                        </div>
                        <div style="display: flex; gap: 0.35rem;">
                            <button type="button" class="btn-admin btn-admin-outline btn-admin-sm" data-copy-url="<?= e($m['file_path']) ?>" style="flex: 1; justify-content: center;">
                                Copy Path
                            </button>
                            <a href="<?= ADMIN_URL ?>/media.php?action=delete&id=<?= $m['id'] ?>" class="btn-admin btn-admin-danger btn-admin-sm" data-confirm="Delete this image file?">
                                &times;
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
