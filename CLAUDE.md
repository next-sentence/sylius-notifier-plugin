# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Identity

This is **LWC Sylius Notifier Plugin** (`lwc/sylius-notifier-plugin`), a Sylius 2.0 e-commerce plugin. Built on the Sylius Plugin Skeleton with `sylius/test-application` for isolated development and testing.

- **Namespace**: `LWC\SyliusNotifierPlugin`
- **Test namespace**: `Tests\LWC\SyliusNotifierPlugin`
- **PHP**: ^8.2, **Sylius**: ^2.0, **Symfony**: ^7.4

## Development Commands

### Setup
```bash
# Frontend
(cd vendor/sylius/test-application && yarn install)
(cd vendor/sylius/test-application && yarn build)
vendor/bin/console assets:install

# Database
vendor/bin/console doctrine:database:create
vendor/bin/console doctrine:migrations:migrate -n
vendor/bin/console sylius:fixtures:load -n

# Start server
symfony server:start -d
```

Database credentials are in `tests/TestApplication/.env` and `.env.test`.

### Composer Scripts
```bash
composer run database-reset    # Drop, create, migrate, load fixtures
composer run frontend-clear    # Rebuild frontend assets
composer run test-app-init     # Full reset (database + frontend)
```

### Docker
```bash
# Uses compose.yml with PHP 8.3, MySQL 8.4, Nginx, Mailhog
docker compose up -d
# Copy compose.override.dist.yml to compose.override.yml for local customization
```

### Testing
```bash
# PHPUnit
vendor/bin/phpunit

# Behat (non-JS)
vendor/bin/behat --strict --tags="~@javascript&&~@mink:chromedriver"

# Behat (JS) - requires Chrome headless + Symfony server on port 8080
APP_ENV=test symfony server:start --port=8080 --daemon
vendor/bin/behat --strict --tags="@javascript,@mink:chromedriver"
```

### Code Quality
```bash
vendor/bin/phpstan analyse -c phpstan.neon -l max src/
vendor/bin/ecs check
```

## Architecture

### Plugin Entry Points
- `src/LWCSyliusNotifierPlugin.php` - Bundle class using `SyliusPluginTrait`, overrides `getPath()` to point to project root
- `src/DependencyInjection/LWCSyliusNotifierExtension.php` - Extends `AbstractResourceExtension`, loads `config/services.xml`, prepends Doctrine migrations
- `src/DependencyInjection/Configuration.php` - TreeBuilder configuration

### Configuration Layout
- `config/services.xml` - Service definitions (imports from `config/services/` subdirectory)
- `config/config.yaml` - Plugin config, imports twig hooks
- `config/routes/admin.yaml` and `config/routes/shop.yaml` - Route definitions
- `config/twig_hooks/` - Twig hook YAML configs for extending Sylius templates

### Test Application
The plugin runs inside `vendor/sylius/test-application`. Configuration lives in `tests/TestApplication/`:
- `config/bundles.php` - Registers plugin bundle
- `config/config.yaml` - Test app config
- `config/services_test.php` - Test service overrides
- `.env` - Database URL, bundle/config/route imports using `@LWCSyliusNotifierPlugin` Twig namespace

### Naming Conventions
- Service XML uses `config/services/` for organized sub-files imported via `config/services.xml`
- Twig templates reference the plugin as `@LWCSyliusNotifierPlugin`
- Database name pattern: `lwc_sylius_notifier_plugin_{environment}`
- DI extension alias follows Symfony convention (snake_case of plugin name)

## AI Development Guides

- **CLEANUP_GUIDE.md** - Removing example/demo code from the skeleton
- **RENAME_GUIDE.md** - Renaming plugin namespace and all references
- **COMPATIBILITY_GUIDE.md** - Supporting multiple Sylius versions (1.14, 2.0, 2.1)
