<?php

declare(strict_types=1);

namespace URLCV\NoticePeriodCalculator\Laravel;

use App\Tools\Contracts\ToolInterface;

/**
 * Laravel tool adapter for the Notice Period Calculator.
 * Frontend-only: all logic runs in the browser via Alpine.js.
 */
class NoticePeriodCalculatorTool implements ToolInterface
{
    public function slug(): string
    {
        return 'notice-period-calculator';
    }

    public function name(): string
    {
        return 'Notice period calculator';
    }

    public function summary(): string
    {
        return 'Calculate your last working day from your notice start date and notice period (days, weeks, or months).';
    }

    public function descriptionMd(): ?string
    {
        return <<<'MD'
## Notice period calculator

Enter the date your notice period **starts** (e.g. the day you hand in your resignation, or the next day if your contract says so) and the length of your notice period. The tool will show your **last day of notice**.

### Inputs

- **Start date** — when the notice period begins
- **Notice amount** — a positive number (e.g. 4, 2, 30)
- **Unit** — days, weeks, or calendar months
- **Count working days only** — if checked, only Monday–Friday are counted (weekends skipped)

### Output

- Your **last day of notice** (formatted e.g. "Friday, 28 March 2025")
- A short summary (e.g. "28 calendar days from 1 Mar")
- If the end date falls on a weekend, a note explaining that your last *working* day may be the previous Friday

### Use cases

- Recruiters and candidates working out resignation dates
- Checking contract notice clauses (e.g. "4 weeks' notice", "2 months")
- Planning handover and end-of-employment dates

**This tool is for guidance only.** Your contract or local employment law may define notice start/end differently (e.g. in writing, exclusive of holidays). Always confirm with your employer or HR.
MD;
    }

    public function categories(): array
    {
        return ['productivity', 'recruiting'];
    }

    public function tags(): array
    {
        return ['notice-period', 'resignation', 'last-day', 'employment', 'productivity'];
    }

    public function inputSchema(): array
    {
        return [];
    }

    public function run(array $input): array
    {
        return [];
    }

    public function mode(): string
    {
        return 'frontend';
    }

    public function isAsync(): bool
    {
        return false;
    }

    public function isPublic(): bool
    {
        return true;
    }

    public function frontendView(): ?string
    {
        return 'notice-period-calculator::notice-period-calculator';
    }

    public function rateLimitPerMinute(): int
    {
        return 30;
    }

    public function cacheTtlSeconds(): int
    {
        return 0;
    }

    public function sortWeight(): int
    {
        return 100;
    }
}
