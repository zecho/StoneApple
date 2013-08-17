Feature: Adminstration login
    In order to add and remove content
    As an administrator
    I need to be able to log in

    Background:
        Given I there is an administrator with values
            | login  | email            | password |
            | toto   | toto@example.com | secr3T!  |

    Scenario: not logged in => login screen
        Given I am not logged in
        When I go to "/admin/"
        Then I should see the login screen

    Scenario: existing user (success)
        Given I am not logged in
        When I go to "/admin/"
        And I submit the form with values
            | field         | value         |
            | form_username | toto          |
            | form_password | secr3T!       |
        Then I should see the success message "Welcome toto."

    Scenario: wrong password (fail)
        Given I am not logged in
        When I go to "/admin/"
        And I submit the form with values
            | field         | value         |
            | form_username | toto          |
            | form_password | wr0ngPwd!     |
        Then I should see the error message "Error."

    Scenario: wrong username (fail)
        Given I am not logged in
        When I go to "/admin/"
        And I submit the form with values
            | field         | value         |
            | form_username | joske         |
            | form_password | secr3T!       |
        Then I should see the error message "Error."
