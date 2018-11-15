<?php

wfLoadExtension( 'VisualEditor' );
// Enable by default for everybody
$wgDefaultUserOptions['visualeditor-enable'] = 1;
// Don't allow users to disable it
$wgHiddenPrefs[] = 'visualeditor-enable';
// Enable VisualEditor's experimental code features
$wgDefaultUserOptions['visualeditor-enable-experimental'] = 1;
$wgVirtualRestConfig['modules']['parsoid'] = array(
    'url' => 'http://parsoid:8000',
    // Parsoid "domain", see below (optional)
    'domain' => 'mediawiki',
);
// This feature requires a non-locking session store. The default session store will not work and
// will cause deadlocks (connection timeouts from Parsoid) when trying to use this feature.
$wgSessionsInObjectCache = true;
// Forward users' Cookie: headers to Parsoid. Required for private wikis (login required to read).
// If the wiki is not private (i.e. $wgGroupPermissions['*']['read'] is true) this configuration
// variable will be ignored.
//
// WARNING: ONLY enable this on private wikis and ONLY IF you understand the SECURITY IMPLICATIONS
// of sending Cookie headers to Parsoid over HTTP. For security reasons, it is strongly recommended
// that $wgVirtualRestConfig['modules']['parsoid']['url'] be pointed to localhost if this setting is enabled.
$wgVirtualRestConfig['modules']['parsoid']['forwardCookies'] = true;
$wgVisualEditorEnableWikitext = true;
$wgDefaultUserOptions['visualeditor-newwikitext'] = 1;
