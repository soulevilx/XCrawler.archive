# XCrawler ( aka XGallery )

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/dc85a2bfa3b54b52908d5dc3836fd7ff)](https://www.codacy.com/gh/jooservices/XCrawler/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=jooservices/XCrawler&amp;utm_campaign=Badge_Grade)
[![Code Coverage](https://scrutinizer-ci.com/g/jooservices/XCrawler/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/jooservices/XCrawler/?branch=develop)
[![codecov](https://codecov.io/gh/jooservices/XCrawler/branch/develop/graph/badge.svg?token=3zBusDLsKa)](https://codecov.io/gh/jooservices/XCrawler)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jooservices/XCrawler/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/jooservices/XCrawler/?branch=develop)
[![Build Status](https://scrutinizer-ci.com/g/jooservices/XCrawler/badges/build.png?b=develop)](https://scrutinizer-ci.com/g/jooservices/XCrawler/build-status/develop)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=jooservices_XCrawler&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=jooservices_XCrawler)

A small project ( but took so long time ) based on Laravel for web Crawling ( with porn targeted ) data

## How to install

- Check [installation](docs/Install.md) document

## Requirements

## Database

- MySQL
    - Primary data
    - Telescope
- MongoDb
    - Logging

## Supervisor
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

## Contribute
- Create PR from `develop`
- Make sure you have UnitTest fully
- PR must be passed all required conditions
