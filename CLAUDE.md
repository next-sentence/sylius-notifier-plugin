# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Identity

This is **LWC Sylius Notifier Plugin** (`lwc/sylius-notifier-plugin`), a Sylius 2.0 e-commerce plugin that replaces Sylius's built-in email system with a template-driven notification system using Symfony Notifier/Mailer, monsieurbiz/sylius-rich-editor-plugin for content editing, and Inky/CSS inliner for professional email rendering.

- **Namespace**: `LWC\SyliusNotifierPlugin`
- **Test namespace**: `Tests\LWC\SyliusNotifierPlugin`
- **PHP**: ^8.2, **Sylius**: ^2.0, **Symfony**: ^7.4
- **DI prefix**: `lwc_sylius_notifier`
- **Table prefix**: `lwc_sylius_notifier__`

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
docker compose up -d  # PHP 8.3, MySQL 8.4, Nginx, Mailhog
```

### Testing
```bash
vendor/bin/phpunit
vendor/bin/phpstan analyse -c phpstan.neon -l max src/
vendor/bin/ecs check
```

## Architecture

### Plugin Entry Points
- `src/LWCSyliusNotifierPlugin.php` - Extends `AbstractResourceBundle` with `SyliusPluginTrait`, exposes model namespace for Doctrine mapping
- `src/DependencyInjection/LWCSyliusNotifierExtension.php` - Registers Sylius resources (`notification`, `notification_template`), loads services, prepends Doctrine migrations
- `src/DependencyInjection/Configuration.php` - Defines resource tree with model/controller/factory/repository/form classes for both resources

### Entities
- **Notification** - In-app notifications with date ranges, priority, user segmentation, and rich editor body content
- **NotificationTemplate** - Email/SMS templates with translatable name/subject/body, channel types (email/sms/in_app), type (simple/custom)
- **NotificationTemplateTranslation** - Locale-specific content for templates

### Email Flow
1. Sylius event triggers (order, password reset, verification, etc.)
2. `EventListener/MailerListener` or `EmailManager/*` dispatches `CreateNotification` message to Symfony Messenger
3. `MessageHandler/CreateNotificationHandler` finds template by code, creates `EmailNotification`
4. Symfony Notifier sends email using `Notifier/Message/EmailNotification` which renders `templates/email/dynamic.html.twig`
5. Template applies Inky Framework (HTML table layout) + CSS inlining + rich editor rendering + variable replacement

### Service Decorators
The plugin decorates Sylius email managers to route all emails through the notification template system:
- `EmailManager/AdminOrderEmailManager` decorates admin order email
- `EmailManager/ShopOrderEmailManager` decorates shop order email
- `EmailManager/ShipmentEmailManager` decorates shipment email
- `Mailer/OrderEmailManager` decorates the base order email manager

### Configuration Layout
- `config/services.xml` - Imports service sub-files from `config/services/`
- `config/config.yaml` - Plugin config, grid imports, mailer/notifier config, disables Sylius built-in emails
- `config/routes/admin/` - Sylius CRUD routes for notifications and templates
- `config/doctrine/model/` - Doctrine ORM XML mappings
- `config/validation/` - Symfony validation XML constraints
- `config/serialization/` - Serializer group mappings for API
- `config/app/grids/` - Sylius Grid configurations for admin CRUD

### Key Dependencies
- `monsieurbiz/sylius-rich-editor-plugin` - WYSIWYG editor for notification body content
- `symfony/notifier` + `symfony/mailer` - Email sending infrastructure
- `twig/inky-extra` - Converts Inky markup to responsive HTML email tables
- `twig/cssinliner-extra` - Inlines CSS into HTML for email client compatibility

### Naming Conventions
- Twig templates: `@LWCSyliusNotifierPlugin`
- Sylius resource aliases: `lwc_sylius_notifier.notification`, `lwc_sylius_notifier.notification_template`
- Route prefix: `lwc_sylius_notifier_admin_`
- Translation prefix: `lwc_sylius_notifier.`
- Database tables: `lwc_sylius_notifier__notification`, `lwc_sylius_notifier__notification_template`
