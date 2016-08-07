<?php
/**
 * Created by IntelliJ IDEA.
 * User: nuomi
 * Date: 8/7/16
 * Time: 3:13 PM
 */

namespace Com\Youzan\ZanPhpIo\Git\Service;


use Zan\Framework\Foundation\Core\Config;
use Zan\Framework\Foundation\Exception\System\InvalidArgumentException;

class HooksService
{
    private $config;
    private $buildPath;
    private $distPath;
    private $backupPath;

    /**
     * HooksService constructor.
     */
    public function __construct()
    {
        $this->config = Config::get('hooks.doc');
    }

    private function validSecret($secret)
    {
        return ($secret === $this->config['secret']);
    }

    public function updateDoc($secret)
    {
        if (!$this->validSecret($secret)) {
            throw new InvalidArgumentException('invalid secret');
        }

        $this->buildPath = $this->getDirectory($this->config['build']);
        $this->distPath = $this->getDirectory($this->config['dist']);
        $this->backupPath = $this->getDirectory($this->config['backup']);

        $oldPath = getcwd();
        $shellPath = $this->config['src'];
        chdir($shellPath);

        $this->build();

        $this->backup();

        $this->publish();

        chdir($oldPath);

        yield 1;
    }

    private function getDirectory($dir)
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
            chmod($dir, 0755);
        }
        return $dir;
    }

    private function build()
    {
        $output = shell_exec($this->config['shell']);
        echo $output;
    }

    private function backup()
    {
        $backupPath = $this->backupPath . '/' . date("YmdH:i:s");
        rename($this->distPath, $backupPath);
    }

    private function publish()
    {
        rename($this->buildPath, $this->distPath);
    }

}