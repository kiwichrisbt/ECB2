<?php
/**
 * Test for GitHub issue #18: low maximum filesize on image upload when using gallery block
 * 
 * This test verifies that:
 * 1. getMaxUploadFilesize() returns a valid byte value
 * 2. The value respects PHP's upload_max_filesize
 * 3. The value respects PHP's post_max_size
 * 4. The value respects PHP's memory_limit (if set)
 * 5. convertIniSizeToBytes() correctly converts size notations (K, M, G)
 */

require_once dirname(__DIR__) . '/lib/class.ecb2_FileUtils.php';

class TestMaxFilesizeLimit
{
    private $pass_count = 0;
    private $fail_count = 0;

    public function run()
    {
        echo "Running tests for GitHub issue #18 - Maximum filesize on image upload\n";
        echo str_repeat("=", 70) . "\n\n";
        
        $this->test_getMaxUploadFilesize_returns_bytes();
        $this->test_respects_upload_max_filesize();
        $this->test_respects_post_max_size();
        $this->test_respects_memory_limit();
        $this->test_convertIniSizeToBytes_kilobytes();
        $this->test_convertIniSizeToBytes_megabytes();
        $this->test_convertIniSizeToBytes_gigabytes();
        $this->test_max_filesize_is_reasonable();
        
        echo "\n" . str_repeat("=", 70) . "\n";
        echo sprintf("Tests completed: %d passed, %d failed\n", $this->pass_count, $this->fail_count);
        
        return $this->fail_count === 0;
    }

    private function test_getMaxUploadFilesize_returns_bytes()
    {
        $max_size = \ECB2\ecb2_FileUtils::getMaxUploadFilesize();
        $this->assert('getMaxUploadFilesize returns integer', is_int($max_size));
        $this->assert('getMaxUploadFilesize returns positive value', $max_size > 0);
    }

    private function test_respects_upload_max_filesize()
    {
        $max_size = \ECB2\ecb2_FileUtils::getMaxUploadFilesize();
        $upload_max_filesize = $this->convert_ini_to_bytes(ini_get('upload_max_filesize'));
        
        $this->assert(
            'getMaxUploadFilesize does not exceed upload_max_filesize',
            $max_size <= $upload_max_filesize
        );
    }

    private function test_respects_post_max_size()
    {
        $max_size = \ECB2\ecb2_FileUtils::getMaxUploadFilesize();
        $post_max_size = $this->convert_ini_to_bytes(ini_get('post_max_size'));
        
        $this->assert(
            'getMaxUploadFilesize does not exceed post_max_size',
            $max_size <= $post_max_size
        );
    }

    private function test_respects_memory_limit()
    {
        $max_size = \ECB2\ecb2_FileUtils::getMaxUploadFilesize();
        $memory_limit = ini_get('memory_limit');
        
        if ($memory_limit !== '-1') {
            $memory_limit_bytes = $this->convert_ini_to_bytes($memory_limit);
            $expected_max = (int)($memory_limit_bytes * 0.9);
            
            $this->assert(
                'getMaxUploadFilesize respects 90% of memory_limit',
                $max_size <= $expected_max
            );
        }
    }

    private function test_convertIniSizeToBytes_kilobytes()
    {
        // Use reflection to access the protected method
        $reflection = new ReflectionClass('\ECB2\ecb2_FileUtils');
        $method = $reflection->getMethod('convertIniSizeToBytes');
        $method->setAccessible(true);
        
        $result = $method->invoke(null, '512K');
        $expected = 512 * 1024;
        $this->assert('convertIniSizeToBytes converts "512K" correctly', $result === $expected);
    }

    private function test_convertIniSizeToBytes_megabytes()
    {
        $reflection = new ReflectionClass('\ECB2\ecb2_FileUtils');
        $method = $reflection->getMethod('convertIniSizeToBytes');
        $method->setAccessible(true);
        
        $result = $method->invoke(null, '50M');
        $expected = 50 * 1024 * 1024;
        $this->assert('convertIniSizeToBytes converts "50M" correctly', $result === $expected);
    }

    private function test_convertIniSizeToBytes_gigabytes()
    {
        $reflection = new ReflectionClass('\ECB2\ecb2_FileUtils');
        $method = $reflection->getMethod('convertIniSizeToBytes');
        $method->setAccessible(true);
        
        $result = $method->invoke(null, '1G');
        $expected = 1024 * 1024 * 1024;
        $this->assert('convertIniSizeToBytes converts "1G" correctly', $result === $expected);
    }

    private function test_max_filesize_is_reasonable()
    {
        $max_size = \ECB2\ecb2_FileUtils::getMaxUploadFilesize();
        // Should be at least 1MB (1048576 bytes)
        $this->assert(
            'getMaxUploadFilesize is at least 1MB',
            $max_size >= 1048576
        );
    }

    private function assert($message, $condition)
    {
        if ($condition) {
            echo "✓ PASS: $message\n";
            $this->pass_count++;
        } else {
            echo "✗ FAIL: $message\n";
            $this->fail_count++;
        }
    }

    private function convert_ini_to_bytes($value)
    {
        $value = trim($value);
        $last = strtolower($value[strlen($value) - 1]);
        $bytes = (int)$value;
        
        switch ($last) {
            case 'g':
                $bytes *= 1024;
                // fall through
            case 'm':
                $bytes *= 1024;
                // fall through
            case 'k':
                $bytes *= 1024;
                break;
        }
        
        return $bytes;
    }
}

// Run the tests
$test = new TestMaxFilesizeLimit();
$success = $test->run();
exit($success ? 0 : 1);
