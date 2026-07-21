<?php
if (php_sapi_name() !== 'cli') {
    echo "Run from CLI\n";
    exit(1);
}

if ($argc < 2) {
    echo "Usage: php scripts/strip_comments.php <dir1> [dir2 ...]\n";
    exit(1);
}

function stripPhpFile($path) {
    $contents = file_get_contents($path);
    if ($contents === false) return false;
    $tokens = token_get_all($contents);
    $out = '';
    foreach ($tokens as $t) {
        if (is_array($t)) {
            if ($t[0] === T_COMMENT || $t[0] === T_DOC_COMMENT) {
                continue; // skip PHP comments
            }
            $out .= $t[1];
        } else {
            $out .= $t;
        }
    }
    // Remove HTML comments that may be present in inline HTML
    $out = preg_replace('/<!--(?!\s*\[if).*?-->/s', '', $out);
    return $out;
}

function stripGenericHtml($contents) {
    return preg_replace('/<!--(?!\s*\[if).*?-->/s', '', $contents);
}

function shouldProcess($filePath) {
    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    return in_array($ext, ['php','phtml','html','htm']);
}

function processFile($filePath) {
    if (!shouldProcess($filePath)) return false;
    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $orig = file_get_contents($filePath);
    if ($orig === false) return false;
    if ($ext === 'php' || $ext === 'phtml') {
        $new = stripPhpFile($filePath);
    } else {
        $new = stripGenericHtml($orig);
    }
    if ($new === null) return false;
    if ($new !== $orig) {
        // backup
        copy($filePath, $filePath . '.bak');
        file_put_contents($filePath, $new);
        echo "Stripped comments: $filePath\n";
        return true;
    }
    echo "No change: $filePath\n";
    return false;
}

function recursePath($path) {
    if (is_file($path)) {
        processFile($path);
        return;
    }
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    foreach ($it as $file) {
        if ($file->isFile()) {
            $fp = $file->getPathname();
            if (shouldProcess($fp)) {
                processFile($fp);
            }
        }
    }
}

array_shift($argv);
foreach ($argv as $p) {
    if (!file_exists($p)) {
        echo "Not found: $p\n";
        continue;
    }
    recursePath($p);
}

echo "Done. Backups saved with .bak suffix.\n";
