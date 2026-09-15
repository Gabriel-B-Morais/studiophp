# Studio

Studio is an HTML-first application framework for Laravel. Its source of truth lives in `resources/studio/**/*.blade.php` and is compiled into a versioned intermediate representation shared by development runtime and production build pipelines.

## Core commands

```bash
php artisan studio:build --check
php artisan studio:build
php artisan studio:clear
```

The first core milestone only establishes scanning, parsing, AST/IR, semantic validation, the component registry and manifest cache. Resource CRUD generation, runtime rendering and production artifact generators are intentionally implemented in later milestones on top of this contract.
