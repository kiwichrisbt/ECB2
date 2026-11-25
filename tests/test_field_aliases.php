<?php

namespace CMSMS {
    class HookManager {
        public static function add_hook($name, $callback) {}
    }
}

namespace {
    class CMSModule {
        public function __construct() {}
        public function GetModulePath() { return dirname(__DIR__); }
        public function Lang($key, ...$args) { return $key; }
    }

    class cms_utils {
        public static $module;
        public static function get_module($name) { return self::$module; }
    }

    function cms_join_path(...$parts) { return implode('/', $parts); }
    function munge_string_to_url($value, $unused) { return $value; }

    require_once dirname(__DIR__) . '/lib/class.ecb2_FieldDefBase.php';
    require_once dirname(__DIR__) . '/ECB2.module.php';
    cms_utils::$module = new ECB2();

    if (($argv[1] ?? '') === 'duplicate') {
        class DuplicateAliasField extends \ECB2\ecb2_FieldDefBase {
            public function __construct($mod, $blockName, $value, $params, $adding, $id = 0) {
                parent::__construct($mod, $blockName, $value, $params, $adding, $id);
                $this->field_aliases = ['select'];
            }
            protected function set_field_parameters() {}
            public function get_content_block_input() {}
        }
        class_alias(DuplicateAliasField::class, 'ECB2\\ecb2fd_textinput');

        for ($i = 0; $i < 2; $i++) {
            try {
                ECB2::getFieldAliases();
                fwrite(STDERR, "Duplicate alias was not rejected\n");
                exit(1);
            } catch (\RuntimeException $e) {
                if (strpos($e->getMessage(), "'select'") === false ||
                    strpos($e->getMessage(), "'dropdown'") === false ||
                    strpos($e->getMessage(), "'textinput'") === false) {
                    fwrite(STDERR, "Unexpected error: " . $e->getMessage() . "\n");
                    exit(1);
                }
            }
        }
        echo "Duplicate alias detection passed\n";
        exit(0);
    }

    $expected = [
        'input' => 'textinput',
        'editor' => 'textarea',
        'select' => 'dropdown',
        'dropdown_from_module' => 'dropdown',
        'dropdown_from_udt' => 'dropdown',
        'dropdown_from_gbc' => 'dropdown',
        'dropdown_from_customgs' => 'dropdown',
        'timepicker' => 'date_time_picker',
        'datepicker' => 'date_time_picker',
        'pages' => 'page_picker',
        'module' => 'module_picker',
        'fieldset_start' => 'admin_fieldset_start',
        'fieldset_end' => 'admin_fieldset_end',
        'hr' => 'admin_hr',
        'text' => 'admin_text',
        'link' => 'admin_link',
        'module_link' => 'admin_module_link',
        'input_repeater' => 'textinput',
        'image' => 'gallery',
    ];
    $actual = ECB2::getFieldAliases();
    ksort($expected);
    ksort($actual);
    if ($actual !== $expected || ECB2::getFieldAliases() != $expected) {
        fwrite(STDERR, "Field alias discovery failed:\n" . var_export($actual, true) . "\n");
        exit(1);
    }

    echo "Field alias discovery passed\n";
}
