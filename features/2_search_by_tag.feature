Feature: Tagging posts
    In order to find similar posts rapidly
    As a user
    I should be able to filter posts by a specified tag

    Background:
        Given there is an article with title "In sheep's clothing" tagged with:
            | label         |
            | natural fiber |
            | durability    |
        And there is an article with title "Time will tell" tagged with:
            | label         |
            | durability    |
        And there is a tag with label "greenest grass"

    Scenario: 1 tag has no posts
        When I go to "/tag/greenest-grass"
        Then I should see the notice "There are no posts found for this tag."
        And I should see "0" articles

    Scenario: 1 tag has multiple posts
        When I go to "/tag/durability"
        Then I should see "2" articles
        And I should see that one of the articles has the title "In sheep's clothing"
        And I should see that one of the articles has the title "Time will tell"

    Scenario: the non-existing tag
        When I go to "/tag/the-glass-is-half-full"
        Then the page is not found
