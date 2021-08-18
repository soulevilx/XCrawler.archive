# XCrawler ( aka XGallery )

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/dc85a2bfa3b54b52908d5dc3836fd7ff)](https://www.codacy.com/gh/jooservices/XCrawler/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=jooservices/XCrawler&amp;utm_campaign=Badge_Grade)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=jooservices_XCrawler&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=jooservices_XCrawler)

A small project ( but took so long time ) based on Laravel for web Crawling ( with porn targeted ) data

## How to install

- Check [installation](docs/Install.md) document

## Requirements

## Database

- MySQL
    - Primary data
    - Telescope
- Supervisor
    - Redis

## Cache

- APCu

## Worker / Supervisor

- Horizon
    - `api` : For 3rd API request
        - maxProcesses: 2
    - `crawling` : For Crawling purpose
        - maxProcesses : 5
    - `default` : General usage
        - maxProcesses : 10

## [UnitTest](docs/UnitTest.md)

- `composer test` for full test All crawler must be provided with 2 tests
- Mock
- Without mock ( request directly to target server )
