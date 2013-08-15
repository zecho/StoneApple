Feature: Reading the blog
    In order to read a post
    As a user
    I need to be able to look at the list of posts

    Background:
        Given there is an article with title "In sheep's clothing" created on "2013-08-10"
        And there is an article with title "Time will tell" created on "2013-08-15"

    Scenario: a post cannot have a duplicate slug
        Then I cannot add a new article with title "In sheep's clothing"

    Scenario: the homepage shows the blogpost list
        When I go to "/"
        Then I should see "2" articles
        And I should see that one of the articles has the title "In sheep's clothing"
        And I should see that one of the articles has the title "Time will tell"

    Scenario: the newest post is listed first
        When I go to "/"
        Then I should see that the first post in the list is "Time will tell"

    Scenario: read a post
        When I go to "/post/in-sheep-s-clothing"
        Then I should see the article with title "In sheep's clothing"

    Scenario: the non-existing post
        When I go to "/post/the-glass-is-half-full"
        Then the page is not found
 