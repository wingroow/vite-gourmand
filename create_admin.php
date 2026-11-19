<?php
// Usage : php create_admin.php  -> copie le hash dans database.sql (champ password de l'admin)
echo password_hash('MotDePasseAdmin@2026', PASSWORD_DEFAULT) . PHP_EOL;
