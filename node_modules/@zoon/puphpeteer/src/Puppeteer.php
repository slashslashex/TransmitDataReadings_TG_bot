<?php

namespace Nesk\Puphpeteer;

use Composer\Semver\Semver;
use Nesk\Puphpeteer\Resources\Browser;
use Nesk\Rialto\AbstractEntryPoint;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;

/**
 * @property mixed devices
 * @property mixed errors
 * @property mixed networkConditions
 * @property string product
 *
 * @method \Nesk\Puphpeteer\Resources\Browser connect(array $options)
 *
 * @method-extended \Nesk\Puphpeteer\Resources\Browser connect(array<string, mixed> $options)
 *
 * @method void registerCustomQueryHandler(string $name, mixed $queryHandler)
 *
 * @method-extended void registerCustomQueryHandler(string $name, mixed $queryHandler)
 *
 * @method void unregisterCustomQueryHandler(string $name)
 *
 * @method-extended void unregisterCustomQueryHandler(string $name)
 *
 * @method string[] customQueryHandlerNames()
 *
 * @method-extended string[] customQueryHandlerNames()
 *
 * @method void clearCustomQueryHandlers()
 *
 * @method-extended void clearCustomQueryHandlers()
 *
 * @method \Nesk\Puphpeteer\Resources\Browser launch(array $options = [])
 *
 * @method-extended \Nesk\Puphpeteer\Resources\Browser launch(array<string, mixed> $options = null)
 *
 * @method string executablePath(string $channel = null)
 *
 * @method-extended string executablePath(string $channel = null)
 *
 * @method string[] defaultArgs(array $options = [])
 *
 * @method-extended string[] defaultArgs(array<string, mixed> $options = null)
 *
 * @method \Nesk\Puphpeteer\Resources\BrowserFetcher createBrowserFetcher(array $options)
 *
 * @method-extended \Nesk\Puphpeteer\Resources\BrowserFetcher createBrowserFetcher(array<string, mixed> $options)
 */
class Puppeteer extends AbstractEntryPoint
{
    /**
     * Default options.
     *
     * @var array
     */
    protected $options = [
        'read_timeout' => 30,

        // Logs the output of Browser's console methods (console.log, console.debug, etc...) to the PHP logger
        'log_browser_console' => false,

        // Custom js to load puppeteer-extra plugins
        'js_extra' => '',
    ];

    /**
     * Instantiate Puppeteer's entry point.
     */
    public function __construct(array $userOptions = [])
    {
        if (! empty($userOptions['logger']) && $userOptions['logger'] instanceof LoggerInterface) {
            $this->checkPuppeteerVersion($userOptions['executable_path'] ?? 'node', $userOptions['logger']);
        }

        parent::__construct(
            __DIR__.'/PuppeteerConnectionDelegate.js',
            new PuppeteerProcessDelegate(),
            $this->options,
            $userOptions
        );
    }

    private function checkPuppeteerVersion(string $nodePath, LoggerInterface $logger): void
    {
        $currentVersion = $this->currentPuppeteerVersion($nodePath);
        $acceptedVersions = $this->acceptedPuppeteerVersion();

        if (null === $currentVersion) {
            $logger->warning("Puppeteer doesn't seem to be installed.");

            return;
        }

        if (! Semver::satisfies($currentVersion, $acceptedVersions)) {
            $logger->warning(
                "The installed version of Puppeteer (v$currentVersion) doesn't match the requirements"
                ." ($acceptedVersions), you may encounter issues."
            );
        }
    }

    private function currentPuppeteerVersion(string $nodePath): ?string
    {
        $process = new Process([$nodePath, __DIR__.'/get-puppeteer-version.js']);
        $process->mustRun();

        return json_decode($process->getOutput());
    }

    private function acceptedPuppeteerVersion(): string
    {
        $npmManifestPath = __DIR__.'/../package.json';
        $npmManifest = json_decode(file_get_contents($npmManifestPath));

        return $npmManifest->dependencies->puppeteer;
    }
}
