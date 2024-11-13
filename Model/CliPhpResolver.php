<?php
/**
 * Aimsinfosoft
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Aimsinfosoft.com license that is
 * available through the world-wide-web at this URL:
 * https://www.aimsinfosoft.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Aimsinfosoft
 * @package     Aimsinfosoft_Base
 * @copyright   Copyright (c) Aimsinfosoft (https://www.aimsinfosoft.com/)
 * @license     https://www.aimsinfosoft.com/LICENSE.txt
 */

namespace Aimsinfosoft\Base\Model;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Shell;
use Symfony\Component\Process\PhpExecutableFinder;

class CliPhpResolver
{
    /**
     * Configuration key for PHP executable path
     */
    public const PHP_EXECUTABLE_PATH = 'php_executable_path';

    /**
     * Regular expression for checking PHP version in CLI mode
     */
    public const VERSION_CHECK_REGEXP = '/PHP [\d\.]+ \(cli\)/';

    /**
     * @var DeploymentConfig
     */
    private $deploymentConfig;

    /**
     * @var PhpExecutableFinder
     */
    private $executableFinder;

    /**
     * @var Shell
     */
    private $shell;

    /**
     * @var string
     */
    private $executablePath;

    /**
     * CliPhpResolver constructor.
     *
     * @param DeploymentConfig $deploymentConfig
     * @param PhpExecutableFinder $executableFinder
     * @param Shell $shell
     */
    public function __construct(
        DeploymentConfig $deploymentConfig,
        PhpExecutableFinder $executableFinder,
        Shell $shell
    ) {
        $this->deploymentConfig = $deploymentConfig;
        $this->executableFinder = $executableFinder;
        $this->shell = $shell;
    }

    /**
     * Return Cli PHP executable path.
     * Assumed that this executable will be executed through `exec` function
     *
     * @return string
     */
    public function getExecutablePath(): string
    {
        if (!$this->executablePath) {
            $this->executablePath = $this->resolvePhpExecutable();
        }

        return $this->executablePath;
    }

    /**
     * Resolve the PHP executable path based on configuration and system settings.
     *
     * @return string
     */
    private function resolvePhpExecutable()
    {
        $pathCandidates = [
            $this->deploymentConfig->get(self::PHP_EXECUTABLE_PATH),
            $this->executableFinder->find()
        ];

        foreach ($pathCandidates as $path) {
            if ($path && $this->isExecutable($path)) {
                return $path;
            }
        }

        return 'php';
    }

    /**
     * Check if the provided path is an executable PHP binary.
     *
     * @param string $path
     * @return bool
     */
    private function isExecutable($path): bool
    {
        $disabledFunctions = $this->getDisabledPhpFunctions();
        if (in_array('exec', $disabledFunctions)) {
            throw new \RuntimeException(
                (string)__(
                    'The PHP function exec is disabled.'
                    . ' Please contact your system administrator or your hosting provider.'
                )
            );
        }

        try {
            $versionResult = (string)$this->shell->execute($path . ' %s', ['--version']);
        } catch (\Exception $e) {
            return false;
        }

        return (bool)preg_match(self::VERSION_CHECK_REGEXP, $versionResult);
    }

    /**
     * Get the list of disabled PHP functions.
     *
     * @return array
     */
    private function getDisabledPhpFunctions(): array
    {
        return explode(',', str_replace(' ', ',', ini_get('disable_functions')));
    }
}
