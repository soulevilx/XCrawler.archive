# CrawlerX ( aka XCrawler / XGallery )
A small project ( but took so long time ) based on Laravel for web Crawling ( with porn targeted ) data

## How to install
  - Check [installation](//docs/Install.md) document

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
## [UnitTest](//docs/UnitTest.md)
  - `composer test` for full test
    All crawler must be provided with 2 tests
  - Mock
  - Without mock ( request directly to target server )
