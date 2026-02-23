# urlcv/notice-period-calculator

[![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue)](https://www.php.net)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Calculate your last working day from your notice start date and notice period (days, weeks, or months).

> **Live tool:** [urlcv.com/tools/notice-period-calculator](https://urlcv.com/tools/notice-period-calculator)  
> This package powers the free Notice Period Calculator on **[URLCV](https://urlcv.com)** — the recruitment platform that helps agencies present candidates professionally.

---

## Features

- **Start date** — when your notice period begins (e.g. resignation date or next day)
- **Notice amount** — positive number in days, weeks, or calendar months
- **Working days only** — optional: count only Monday–Friday (skip weekends)
- **Last day of notice** — formatted result (e.g. "Friday, 28 March 2025")
- **Weekend note** — if the end date falls on Saturday or Sunday, a note explains that your last *working* day may be the previous Friday
- Frontend-only (Alpine.js) — no server round-trip, instant results

---

## Requirements

- PHP **8.2** or higher (for Laravel integration)
- Laravel with Blade and Alpine.js (for the tool UI)

---

## Installation

```bash
composer require urlcv/notice-period-calculator
```

---

## Usage

The tool is used as a Laravel tool registered in your app. Register the service provider (auto-discovered via Composer) and add `NoticePeriodCalculatorTool::class` to your `config/tools.php` `tools` array. The tool renders a single Blade view that runs entirely in the browser.

---

## Free online tool

Use the free online version at:

**[urlcv.com/tools/notice-period-calculator](https://urlcv.com/tools/notice-period-calculator)**

Enter your notice start date and period — get your last day instantly. No signup required.

---

## About URLCV

[URLCV](https://urlcv.com) is a recruitment platform for agencies. It helps recruiters present candidates professionally with branded shortlists, structured CV parsing, and candidate tracking.

Explore all free tools at **[urlcv.com/tools](https://urlcv.com/tools)**.

---

## License

MIT — see [LICENSE](LICENSE).
