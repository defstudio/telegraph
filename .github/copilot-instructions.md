# Telegraph - Laravel Telegram Bot Package

Always reference these instructions first and fallback to search or bash commands only when you encounter unexpected information that does not match the info here.

Telegraph is a Laravel package for fluently interacting with Telegram Bots. This is a package for creating Telegram bots, not an application itself - it provides models, facades, and artisan commands for Laravel applications.

## Working Effectively

### Dependency Installation
- **NEVER CANCEL**: `composer install` takes 11+ minutes to complete and may have network authentication issues. Set timeout to 20+ minutes.
- If installation fails with GitHub authentication errors, the packages will be downloaded from source (git clone) which is slower but works
- For faster development: `composer install --no-dev` takes ~5 minutes for production dependencies only
- Required PHP version: PHP 8.1+ (tested with 8.1, 8.2, 8.3, 8.4)
- Required Laravel version: 10.*, 11.*, 12.*

### Build and Test Commands
Run all commands from the repository root:

- **Linting**: `composer run test:lint` - takes ~4 seconds, checks code style with PHP CS Fixer
- **Static Analysis**: `composer run test:types` - takes ~11 seconds, runs PHPStan at maximum level  
- **Combined Quick Check**: `composer run test:lint && composer run test:types` - takes ~15 seconds, essential pre-commit validation
- **Unit Tests**: `composer run test:unit` - takes ~26 seconds, runs Pest tests excluding sandbox
- **Sandbox Tests**: `composer run test:sandbox` - takes ~1 second, requires SANDOBOX_TELEGRAM_BOT_TOKEN env var
- **X-Ray Scan**: `composer run x-ray` - takes ~3 seconds, scans for Ray debugging calls  
- **Full Test Suite**: `composer run test` - **CURRENTLY FAILS** due to Carbon test issues. Individual commands work: run linting, types, and unit tests separately. Expected to take 45+ minutes when working. Set timeout to 60+ minutes.

### Manual Command Alternatives
If composer scripts fail, run commands directly:
- Linting: `vendor/bin/php-cs-fixer fix -v --dry-run --config=.php-cs-fixer.dist.php`
- Fix code style: `vendor/bin/php-cs-fixer fix -v --config=.php-cs-fixer.dist.php`
- Static analysis: `vendor/bin/phpstan analyse --ansi --memory-limit=-1`
- Tests: `vendor/bin/pest --colors=always --exclude-group=sandbox`
- Sandbox tests: `vendor/bin/pest --colors=always --group=sandbox`

### Known Issues
- **Unit tests currently have 29 failing tests** related to Carbon `DateMalformedStringException::$xdebug_message` dynamic property creation
- These failures are unrelated to most development work and should be ignored unless specifically working on date/time functionality
- The full `composer run test` command will fail due to these unit test failures, but individual commands work fine
- Sandbox tests require a real Telegram bot token to run and will be skipped otherwise

## Validation
- **Always validate with individual commands** since full suite currently fails: `composer run test:lint && composer run test:types && composer run test:sandbox && composer run x-ray`
- **Essential pre-commit checks**: `composer run test:lint && composer run test:types` - takes ~15 seconds combined
- For faster validation during development, run `composer run test:unit` to check core functionality (ignore the 29 known failing Carbon tests)
- **Manual Testing Scenarios**: After making changes, validate key functionality:
  - **Telegraph Commands**: Test `vendor/bin/testbench telegraph:new-bot --help` to ensure commands are working
  - **Package Discovery**: Run `vendor/bin/testbench package:discover` to verify service provider registration
  - **Facade Methods**: Review `src/Facades/Telegraph.php` to understand the API surface
  - **Bot Creation Flow**: Test the main workflow of creating bots, chats, and sending messages (requires Telegram token for live testing)

## Telegraph Package Features

### Artisan Commands Available
Run with `vendor/bin/testbench [command]` or within a Laravel app with `php artisan [command]`:
- `telegraph:new-bot` - Create a new TelegraphBot with guided setup
- `telegraph:new-chat` - Create a new chat associated with a bot
- `telegraph:set-webhook` - Set webhook URL in Telegram bot configuration  
- `telegraph:unset-webhook` - Unregister webhook from Telegram bot
- `telegraph:debug-webhook` - Get webhook debug info from Telegram bot

### Key Package Structure
- **Core**: `src/Telegraph.php` - Main Telegraph class with fluent API
- **Models**: `src/Models/` - TelegraphBot and TelegraphChat Eloquent models
- **Commands**: `src/Commands/` - Artisan commands for bot/chat management
- **Concerns**: `src/Concerns/` - Traits for Telegraph functionality (HasStorage, ComposesMessages, etc.)
- **Client**: `src/Client/` - HTTP client for Telegram API interaction
- **DTO**: `src/DTO/` - Data Transfer Objects for Telegram API responses
- **Controllers**: `src/Controllers/` - Webhook handling controllers
- **Tests**: `tests/` - Pest tests with Unit, Feature, Regression, and Sandbox suites

### Configuration Files
- `composer.json` - Defines scripts and dependencies
- `phpstan.neon` - PHPStan static analysis configuration (level: max)
- `.php-cs-fixer.dist.php` - PHP CS Fixer code style rules
- `phpunit.xml.dist` - PHPUnit/Pest testing configuration

## Development Workflow

### For New Features
1. Run `composer install` (wait 11+ minutes, NEVER CANCEL)
2. Run `composer run test:lint && composer run test:types` to ensure baseline passes
3. Create/modify tests in `tests/Unit/` or `tests/Feature/`
4. Implement feature in appropriate `src/` directory
5. Run `composer run test:unit` to verify tests pass
6. Run `composer run test:lint && composer run test:types` to ensure code quality
7. **Before final commit**: Run `composer run test:lint && composer run test:types && composer run test:sandbox && composer run x-ray` (full test suite currently fails due to Carbon issues)

### For Bug Fixes
1. Reproduce the bug with a failing test
2. Fix the issue with minimal code changes
3. Verify the test now passes
4. Run relevant validation commands based on the area of change

### Code Style Guidelines
- Follows PSR-12 coding standards enforced by PHP CS Fixer
- Uses PHPStan at maximum level for static analysis
- All code must pass both linting and static analysis before merging

## Common Tasks

### Repository Root Contents
```
.
..
.editorconfig
.git/
.gitattributes
.github/
.gitignore
.php-cs-fixer.dist.php
CHANGELOG.md
LICENSE.md
README.md
composer.json          # Package definition and scripts
config/                # Package configuration
database/              # Migrations and factories
docs/                  # Documentation
phpstan.neon          # Static analysis config
phpunit.xml.dist      # Test configuration  
resources/            # Package resources
routes/               # Package routes
src/                  # Main source code
tests/                # Test suites
x-ray.yml            # X-Ray configuration
```

### Working with Telegram Bots
This package provides tools for Laravel applications to create and manage Telegram bots:
- Create bots programmatically or via `telegraph:new-bot` command
- Send messages with keyboards, buttons, and rich content
- Handle webhooks and callback queries
- Store and retrieve files from Telegram
- Manage bot commands and configurations

### Package Testing Philosophy
- Uses Pest testing framework instead of PHPUnit
- Tests are organized into Unit, Feature, Regression, and Sandbox suites
- Sandbox tests require real Telegram API tokens and test against live API
- Mock/fake implementations available for testing without API calls