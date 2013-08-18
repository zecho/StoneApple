Feature: Adminstration login
    In order to add and remove content
    As an administrator
    I need to be able to log in

    Background:
        Given I there is an administrator with values
            | field    | value            |
            | username | toto             |
            | email    | toto@example.com |
            | password | secr3T!          |

    Scenario: not logged in => login screen
        When I access the admin interface
        Then I should be redirected
        And I should see the login screen

    Scenario: existing user (success)
        When I go to "/admin/login"
        And I submit the form with values
            | field         | value         |
            | form_username | toto          |
            | form_password | secr3T!       |
        Then I should be redirected
        And I should see the success message "Welcome toto."

    Scenario: wrong password (fail)
        When I go to "/admin/login"
        And I submit the form with values
            | field         | value         |
            | form_username | toto          |
            | form_password | wr0ngPwd!     |
        Then I should see the error message "Your username or password is incorrect."

    Scenario: wrong username (fail)
        When I go to "/admin/login"
        And I submit the form with values
            | field         | value         |
            | form_username | joske         |
            | form_password | secr3T!       |
        Then I should see the error message "Your username or password is incorrect."
