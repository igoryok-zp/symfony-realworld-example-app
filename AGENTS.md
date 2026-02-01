# AGENTS.md

## Build/Lint/Test Commands

All commands should be run inside the Docker container:

```bash
# Start the development environment
docker compose -f docker-compose.dev.yml up -d

# Run all tests
docker compose exec app phpunit

# Run a single test
docker compose exec app phpunit tests/Service/ArticleServiceTest.php

# Run a single test method
docker compose exec app phpunit --filter testMethodName tests/Service/ArticleServiceTest.php

# Run code analysis (runs all quality checks)
docker compose exec app grumphp run

# Run individual code quality tools
docker compose exec app phpcs              # PHP_CodeSniffer (PSR-12)
docker compose exec app phpstan analyse    # PHPStan (level max)
docker compose exec app phpmd src/         # PHP Mess Detector

# Install dependencies
docker compose exec app composer install

# Clear caches
docker compose exec app cache:clear
docker compose exec app cache:clear --env=test
```

## Code Style Guidelines

### General Formatting
- **Standard**: PSR-12 (enforced via PHP_CodeSniffer)
- **Strict Types**: All PHP files MUST include `declare(strict_types=1);`
- **Line Length**: Soft limit 120 characters, hard limit unlimited
- **Indentation**: 4 spaces, no tabs

### Naming Conventions
- **Classes**: PascalCase, singular nouns (e.g., `Article`, `UserService`)
- **Properties/Methods**: camelCase (e.g., `$createdAt`, `getUser()`)
- **Constants**: UPPER_CASE (defined in Config classes)
- **Interfaces**: PascalCase with no suffix (e.g., `TransformerInterface`)
- **Abstract Classes**: PascalCase (e.g., `AbstractRepository`)

### Imports & Namespacing
- **PSR-4 Autoloading**: `App\` maps to `src/`, `App\Tests\` maps to `tests/`
- **Import Order**: `use` statements alphabetically sorted by namespace
- **Group Imports**: Not used - one import per line
- **Type Imports**: Fully qualify types in docblocks only when needed

### Type System
- **Type Declarations**: Always use type hints for parameters and returns
- **Nullable Types**: Use `?string`, `?int` format
- **Return Types**: Always declare return types, including `void`
- **Union Types**: Use PHP 8.0+ union types where appropriate
- **No Mixed**: Avoid `mixed` type, be specific

### Architecture Patterns

**Directory Structure**:
```
src/
├── ApiResource/      # API Platform resources (separate from Entities)
├── Config/           # Constants and validation groups per entity
├── Controller/Api/   # Custom controllers for non-standard operations
├── Dto/              # Data Transfer Objects with validation attributes
├── Entity/           # Doctrine entities
├── Mapper/           # Entity-to-DTO and DTO-to-Entity converters
├── Repository/       # Doctrine repositories extending ServiceEntityRepository
├── Service/          # Business logic layer
├── State/            # API Platform state providers and processors
└── Validator/        # Custom validation constraints
```

**Config Classes Pattern**:
```php
// src/Config/ArticleConfig.php
final class ArticleConfig
{
    public const VALID = 'article:valid';
    public const VALID_CREATE = 'article:valid:create';
    public const VALID_UPDATE = 'article:valid:update';
    public const TITLE_LENGTH = 255;
    public const DESCRIPTION_LENGTH = 1000;
}
```

**DTO Pattern**:
```php
// src/Dto/ArticleDto.php
final class ArticleDto
{
    #[Groups([ArticleConfig::INPUT_CREATE, ArticleConfig::INPUT_UPDATE])]
    #[Assert\NotBlank(groups: [ArticleConfig::VALID_CREATE])]
    #[Assert\Length(max: ArticleConfig::TITLE_LENGTH)]
    public ?string $title = null;
}
```

**Mapper Pattern**:
```php
// src/Mapper/ArticleMapper.php
final class ArticleMapper
{
    public function map(ArticleDto $dto, Article $article): void;
    public function createDto(Article $article): ArticleDto;
}
```

### API Platform Conventions
- Use separate ApiResource classes (not entity annotations)
- Define State Providers for read operations
- Define State Processors for write operations
- Use DTOs for input/output with proper validation groups
- Use custom Controllers only when standard operations don't suffice
- Serialization groups: `INPUT`, `INPUT_CREATE`, `INPUT_UPDATE`, `OUTPUT`

### Testing
- **Framework**: PHPUnit 9.5+
- **Location**: `tests/` mirrors `src/` structure
- **Naming**: `{ClassName}Test.php` with `test{MethodName}()` methods
- **Fixtures**: Use AliceBundle and DoctrineTestBundle
- **Database**: Tests use separate test database with transactions

### Error Handling
- Throw domain-specific exceptions from `src/Exception/`
- Use Symfony's HTTP exception for API errors
- Validate input at API boundaries (DTO validation)
- Fail fast with assertions

### Git Workflow
- GrumPHP runs pre-commit: phpcs, phpmd, phpstan
- All checks must pass before committing
- Write descriptive commit messages
