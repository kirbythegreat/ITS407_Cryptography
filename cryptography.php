<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cryptography</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>ITS407 - Cryptography</h2>
        <form method="post">
            <label>Enter Text:</label>
            <textarea name="text" required></textarea>
            <div class="buttons">
                <button class="btn btn-warning" type="submit" name="encrypt">Encrypt</button>
                <button class="btn btn-success" type="submit" name="decrypt">Decrypt</button>
            </div>
        </form>

        <?php require 'AES_256_CBC.php'; ?>
    </div>
</body>
</html>
