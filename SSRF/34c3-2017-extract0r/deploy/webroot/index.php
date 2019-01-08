<?php
session_start();

include "url.php";

function get_directory($new=false) {
    if (!isset($_SESSION["directory"]) || $new) {
        $_SESSION["directory"] = "files/" . sha1(random_bytes(100));
    }

    $directory = $_SESSION["directory"];

    if (!is_dir($directory)) {
        mkdir($directory);
    }

    return $directory;
}

function clear_directory() {
    $dir = get_directory();
    $files = glob($dir . '/*'); 
    foreach($files as $file) { 
        if(is_file($file) || is_link($file)) {
            unlink($file); 
        } else if (is_dir($file)) {
            rmdir($file);
        }
    }
}

function verify_archive($path) {
    $res = shell_exec("7z l " . escapeshellarg($path) . " -slt");
    $line = strtok($res, "\n");
    $file_cnt = 0;
    $total_size = 0;

    while ($line !== false) {
        preg_match("/^Size = ([0-9]+)/", $line, $m);
        if ($m) {
            $file_cnt++;
            $total_size += (int)$m[1];
        }
        $line = strtok( "\n" );
    }

    if ($total_size === 0) {
        return "Archive's size 0 not supported";
    }

    if ($total_size > 1024*10) {
        return "Archive's total uncompressed size exceeds 10KB";
    }

    if ($file_cnt === 0) {
        return "Archive is empty";
    }

    if ($file_cnt > 5) {
        return "Archive contains more than 5 files";
    }

    return 0;
}

function verify_extracted($directory) {
    $files = glob($directory . '/*'); 
    $cntr = 0;
    foreach($files as $file) {
        if (!is_file($file)) {
            $cntr++;
            unlink($file);
            @rmdir($file);
        }
    }
    return $cntr;
}

function decompress($s) {
    $directory = get_directory(true);
    $archive =  tempnam("/tmp", "archive_");

    file_put_contents($archive, $s);
    $error = verify_archive($archive);
    if ($error) {
        unlink($archive);
        error($error);
    }

    shell_exec("7z e ". escapeshellarg($archive) . " -o" . escapeshellarg($directory) . " -y");
    unlink($archive);

    return verify_extracted($directory);
}

function error($s) {
    clear_directory();
    die("<h2><b>ERROR</b></h2> " . htmlspecialchars($s));
}

$msg = "";
if (isset($_GET["url"])) {
    $page =  get_contents($_GET["url"]);

    if (strlen($page) === 0) {
        error("0 bytes fetched. Looks like your file is empty.");
    } else {
        $deleted_dirs = decompress($page);
        $msg = "<h3>Done!</h3> Your files were extracted if you provided a valid archive.";

        if ($deleted_dirs > 0) {
            $msg .= "<h3>WARNING:</h3> we have deleted some folders from your archive for security reasons with our <a href='cyber_filter'>cyber-enabled filtering system</a>!";
        }
    }
}
?>

<html>
    <head><title>extract0r!</title></head>
    <body>
        <form>
            <h1>extract0r - secure file extraction service</h1>
            <p><b>Your Archive:</b></p>
            <p><input type="text" size="100" name="url"></p>
            <p><input type="submit" value="Extract it!"></p>
        </form>

        <p>Your extracted files will appear <a href="<?= htmlspecialchars(get_directory()) ?>">here</a>.</p>
        <?php if (!empty($msg)) echo "<hr><p>" . $msg . "</p>"; ?>
    </body>
</html>
