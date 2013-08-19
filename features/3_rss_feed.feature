Feature: rss feed
    In order to be notified of new posts
    As a user
    I should be able to add the feed to my feed-reader

    Scenario: rss w/o posts
        When I go to "/rss"
        Then I should see the blog-info in the rss-feed

    Scenario: rss with posts
        Given there is an article with title "In sheep's clothing" created on "2013-08-10"
        And there is an article with title "Time will tell" created on "2013-08-15"
        And there is an article with title "Too lazy for tagging" created on "2013-08-16"
        When I go to "/rss"
        Then I should see the blog-info in the rss-feed
        And I should see the article "In sheep's clothing" in the rss-feed
        And I should see the article "Time will tell" in the rss-feed
        And I should see the article "Too lazy for tagging" in the rss-feed
