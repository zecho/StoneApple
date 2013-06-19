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
        And there is an article with title "Too lazy for tagging"

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

    Scenario: single post w/ tags shows its tags
        When I go to "/post/time-will-tell"
        Then I should see "1" tags
        And I should see that one tag has label "durability"

    Scenario: single post w/ no-tags shows no-tags
        When I go to "/post/too-lazy-for-tagging"
        Then I should see "1" articles
        And I should see "0" tags