Feature: Posts list
    In order to find a post
    As a user
    I need to be able to look at the list

    Background:
        Given there is an article with title "In sheep's clothing"
        And there is an article with title "Time will tell"

    Scenario: a post cannot have a duplicate slug
        Then I cannot add a new article with title "In sheep's clothing"

    Scenario: read the post list
        When I go to "/post/list"
        Then I should see "2" articles
        And I should see that one of the articles has the title "In sheep's clothing"
        And I should see that one of the articles has the title "Time will tell"

    Scenario: read a post
        When I go to "/post/in-sheep-s-clothing"
        Then I should see the article with title "In sheep's clothing"