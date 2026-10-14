<?php
// Legacy /simulator.php used DB-backed matching rules. It has not passed live E2E validation.
// Keep an honest, working alternative until the authenticated rules engine is repaired.
header('Location: /conditions.php',true,302);
header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
exit;
