// src/utils/tokens.php
<?php
function make_token(): string {
  return bin2hex(random_bytes(16));
}