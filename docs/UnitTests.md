# UnitTests
## Unit
- ### Crawlers
  - There are 2 types of Tests
    - 1 Test with mock fully for make sure parser working right as expected
    - 1 Test without mock for make sure parse still work with target. This test will not execute automatically while running on Git Actions
- ### Jobs
  - Make sure job executed as expected
    - Data created ...

In this project usually jobs are wrapped inside Service. Service will be used as central thing.
- ### Models
  - Test for model working as expected likely: Relationship
- ### Services
Service used in this project for central everything
  - OnejavService : Used for Onejav likely get `daily` or `release` data
  - Test will make sure data is created correctly, any event triggered and related thing
    - In logic we don't need Test crawled data because it was Tested in `Crawler`
