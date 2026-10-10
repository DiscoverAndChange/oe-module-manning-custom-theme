<?php

/**
 * Overrides OpenEMR's logo resolution so the module always serves its own bundled logos.
 *
 * OpenEMR core (LogoService::getLogo) dispatches a LogoFilterEvent for every logo it renders
 * (login, EMR menu, portal, favicon, ...), carrying the logo "type" slug (e.g. core/login/primary).
 * For each requested type we look for a bundled image under this module's web-served assets at
 * images/logos/<type>/ and, if one exists, point the web path at it. Core then validates the path
 * with ModulesApplication::filterSafeLocalModuleFiles(), so the file MUST physically live under
 * interface/modules/.../public for the override to take effect.
 *
 * @package   OpenEMR
 * @author    Stephen Nielson <snielson@discoverandchange.com>
 * @copyright Copyright (c) 2026 Discover And Change, Inc.
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

namespace DiscoverAndChange\Modules\CustomManningTheme\Controllers;

use OpenEMR\Events\Services\LogoFilterEvent;

class LogoController
{
    /** Image extensions we recognise as bundled logos, in preference order. */
    private const LOGO_EXTENSIONS = ['svg', 'png', 'jpg', 'jpeg', 'gif', 'ico'];

    /**
     * @param string $logosFilePath Absolute filesystem path to the bundled logos dir (…/public/assets/images/logos/).
     * @param string $logosWebPath  Web-root-relative URL prefix to the same dir.
     */
    public function __construct(
        private readonly string $logosFilePath,
        private readonly string $logosWebPath
    ) {
    }

    public function respondToLogoFilterEvent(LogoFilterEvent $event): LogoFilterEvent
    {
        // logo types arrive as a path slug that may have a trailing slash (e.g. "core/menu/primary/").
        $logoType = trim($event->getLogoType(), '/');
        if ($logoType === '' || str_contains($logoType, '..')) {
            return $event;
        }

        $bundled = $this->findBundledLogo($logoType);
        if ($bundled !== null) {
            // cache-bust on file mtime; the query string is stripped by the core safety filter.
            $event->setWebPath($this->logosWebPath . $logoType . '/' . $bundled['name'] . '?v=' . $bundled['mtime']);
        }
        return $event;
    }

    /**
     * Find a bundled logo file for the given type slug.
     *
     * @return array{name: string, mtime: int}|null
     */
    private function findBundledLogo(string $logoType): ?array
    {
        $dir = $this->logosFilePath . $logoType;
        if (!is_dir($dir)) {
            return null;
        }
        foreach (self::LOGO_EXTENSIONS as $ext) {
            $matches = glob($dir . DIRECTORY_SEPARATOR . '*.' . $ext) ?: [];
            foreach ($matches as $match) {
                if (is_file($match)) {
                    return ['name' => basename($match), 'mtime' => (int) filemtime($match)];
                }
            }
        }
        return null;
    }
}
