# 1.0.1 Serve the module's bundled logos via the core logo finder

Overrides OpenEMR's logo resolution so the module always serves its own bundled logos. The module
listens to the core `LogoFilterEvent` (`logo.filter.url`, dispatched by `LogoService::getLogo()` for
the login page, EMR menu, portal and favicon) and, for any logo type it bundles an image for, points
the web path at `public/assets/images/logos/<logoType>/`.

It is generic: drop a `logo.*` (svg/png/jpg/jpeg/gif, or `favicon.ico`) into
`public/assets/images/logos/<logoType>/` and it is used automatically. The folder path must match the
core logo-type slug, e.g. `core/login/primary`, `core/menu/primary`, `core/login/secondary`,
`portal/login/primary`, `portal/menu/primary`, `core/favicon`. Logo types with no bundled image fall
through to OpenEMR's default. Files are served from `public/` so they pass core's
`ModulesApplication::filterSafeLocalModuleFiles()` safety check.

Ships bundled logos for `core/login/primary` (provider login page), `core/menu/primary` (EMR top
menu) and `portal/login/primary` (patient portal login). The portal logo is currently the same image
as the provider login logo; replace `public/assets/images/logos/portal/login/primary/logo.*` to give
the portal its own.

Also removes dead skeleton leftovers from Bootstrap (an unused `CustomModuleSkeleton` import and
unused imports).

# 1.0.0 Initial Version
